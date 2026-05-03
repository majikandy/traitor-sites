@extends('layouts.app')

@section('title', 'Toast Price Index')
@section('meta_description', 'The Top Toast Price Index — tracking what toast ingredients really cost across UK supermarkets, week by week.')

@push('styles')
<style>
    .site-main { background: var(--color-bg); }
</style>
@endpush

@section('content')

{{-- =============================================
     HERO STAT
     ============================================= --}}
<section class="pi-hero">
    <div class="container">
        <div class="pi-hero-inner">
            <div class="pi-hero-label">THE TOAST INFLATION INDEX</div>
            @if($latestSnapshot)
                <div class="pi-stat-value">
                    {{ number_format((float) $latestSnapshot->index_value, 2) }}
                </div>
                <div class="pi-hero-baseline">baseline: 100.00 &bull; {{ Carbon\Carbon::parse(config('toptoast.index_baseline_date'))->format('M Y') }}</div>
                <div class="pi-hero-meta">
                    <span class="pi-stat-delta {{ (float) $latestSnapshot->index_value >= 100 ? 'pi-delta-up' : 'pi-delta-down' }}">
                        {{ (float) $latestSnapshot->index_value >= 100 ? '&#9650;' : '&#9660;' }}
                        {{ number_format(abs((float) $latestSnapshot->index_value - 100), 2) }} pts vs baseline
                    </span>
                    &ensp;&bull;&ensp;
                    <span class="pi-basket-cost">
                        Basket: <strong>&pound;{{ number_format($latestSnapshot->basket_cost_pence / 100, 2) }}</strong>
                    </span>
                    &ensp;&bull;&ensp;
                    <span class="pi-snapshot-date">
                        Updated {{ Carbon\Carbon::parse($latestSnapshot->snapshot_date)->format('j M Y') }}
                    </span>
                </div>

                @if($recentSnapshots->count() > 1)
                    <div class="pi-trend">
                        @foreach($recentSnapshots->reverse() as $snap)
                            <div class="pi-trend-point">
                                <span class="pi-trend-date">{{ Carbon\Carbon::parse($snap->snapshot_date)->format('d M') }}</span>
                                <span class="pi-trend-val">{{ number_format((float) $snap->index_value, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="pi-stat-value pi-loading">—</div>
                <div class="pi-empty" style="color:rgba(255,255,255,0.6); margin-top:1rem;">
                    Loading first data&hellip; Run <code>php artisan prices:import</code> to populate.
                </div>
            @endif
        </div>
    </div>
</section>

{{-- =============================================
     BIGGEST MOVERS
     ============================================= --}}
@if(count($biggestMovers) > 0)
<section class="pi-section">
    <div class="container">
        <h2 class="pi-section-title">Biggest Movers <span class="pi-section-sub">7-day change</span></h2>
        <div class="pi-movers-grid">
            @foreach($biggestMovers as $mover)
                <div class="pi-mover-card {{ $mover['change_pence'] >= 0 ? 'pi-mover-up' : 'pi-mover-down' }}">
                    <div class="pi-mover-emoji">{{ $mover['ingredient']->emoji }}</div>
                    <div class="pi-mover-name">{{ $mover['ingredient']->name }}</div>
                    <div class="pi-mover-change">
                        {{ $mover['change_pence'] >= 0 ? '+' : '' }}{{ $mover['change_pct'] }}%
                    </div>
                    <div class="pi-mover-pence">
                        {{ $mover['change_pence'] >= 0 ? '+' : '' }}{{ $mover['change_pence'] }}p
                    </div>
                    <div class="pi-mover-price">
                        now &pound;{{ number_format($mover['latest_pence'] / 100, 2) }}
                    </div>
                    <a href="{{ route('price-index.ingredient', $mover['ingredient']->slug) }}" class="pi-mover-link">View history &rarr;</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =============================================
     SUPERMARKET LEAGUE TABLE
     ============================================= --}}
<section class="pi-section">
    <div class="container">
        <h2 class="pi-section-title">Supermarket League Table <span class="pi-section-sub">latest prices</span></h2>

        @if($ingredients->isEmpty())
            <p class="pi-empty">No ingredients configured yet.</p>
        @else
            @foreach($ingredients as $ingredient)
                <div class="pi-table-card">
                    <div class="pi-table-heading">
                        <span class="pi-table-emoji">{{ $ingredient->emoji }}</span>
                        <span>{{ $ingredient->name }}</span>
                        <span class="pi-table-unit">({{ $ingredient->unit }})</span>
                        <a href="{{ route('price-index.ingredient', $ingredient->slug) }}" class="pi-table-link">History &rarr;</a>
                    </div>

                    @if($ingredient->priceObservations->isEmpty())
                        <p class="pi-empty">No prices recorded yet &mdash; add via CSV import.</p>
                    @else
                        @php
                            $sortedObs = $ingredient->priceObservations->sortBy('price_pence');
                            $cheapestPrice = $sortedObs->first()->price_pence;
                        @endphp
                        <table class="pi-table">
                            <thead>
                                <tr>
                                    <th>Retailer</th>
                                    <th>Price</th>
                                    <th>Last updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sortedObs as $obs)
                                    <tr class="{{ $obs->price_pence === $cheapestPrice ? 'pi-row-cheapest' : '' }}">
                                        <td>
                                            @if($obs->retailer)
                                                <span class="pi-retailer-dot" style="background:{{ $obs->retailer->color ?? '#ccc' }};"></span>
                                                {{ $obs->retailer->name }}
                                            @else
                                                Unknown
                                            @endif
                                        </td>
                                        <td>
                                            @if($obs->price_pence === $cheapestPrice)
                                                <span class="pi-crown" title="Cheapest">&#x1F451;</span>
                                            @endif
                                            <span class="pi-price">&pound;{{ number_format($obs->price_pence / 100, 2) }}</span>
                                        </td>
                                        <td class="pi-date">{{ Carbon\Carbon::parse($obs->observed_on)->format('j M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</section>

{{-- =============================================
     PRICE HISTORY CHARTS
     ============================================= --}}
@if($ingredients->isNotEmpty())
<section class="pi-section">
    <div class="container">
        <h2 class="pi-section-title">Price History <span class="pi-section-sub">52-week trends</span></h2>
        <div class="pi-charts-grid">
            @foreach($ingredients as $ingredient)
                <div class="pi-chart-card">
                    <div class="pi-chart-heading">
                        <span>{{ $ingredient->emoji }} {{ $ingredient->name }}</span>
                    </div>
                    <canvas id="chart-{{ $ingredient->slug }}" height="200" aria-label="Price history chart for {{ $ingredient->name }}"></canvas>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =============================================
     IMPORT CTA
     ============================================= --}}
<section class="pi-section pi-import-section">
    <div class="container">
        <div class="pi-import-box">
            <h3>&#x1F4CA; Add Price Data</h3>
            <p>Prices are imported via CSV. Run the following Artisan command to import a file:</p>
            <code class="pi-command">php artisan prices:import path/to/prices.csv</code>
            <p class="pi-import-note">CSV format: <code>ingredient_slug, retailer_slug, price_pence, observed_on</code></p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const slugs = @json($ingredients->pluck('slug'));

    slugs.forEach(function (slug) {
        const canvas = document.getElementById('chart-' + slug);
        if (!canvas) return;

        fetch('/price-index/api/chart/' + slug)
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                if (!data.labels || data.labels.length === 0) {
                    canvas.parentElement.insertAdjacentHTML('beforeend', '<p class="pi-empty">No historical data yet.</p>');
                    canvas.style.display = 'none';
                    return;
                }

                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets,
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { family: 'DM Sans, sans-serif', size: 12 },
                                    color: '#6b5e54',
                                    boxWidth: 12,
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (ctx) {
                                        if (ctx.parsed.y === null) return null;
                                        return ctx.dataset.label + ': £' + (ctx.parsed.y / 100).toFixed(2);
                                    },
                                },
                            },
                        },
                        scales: {
                            x: {
                                ticks: {
                                    font: { family: 'DM Sans, sans-serif', size: 11 },
                                    color: '#6b5e54',
                                    maxTicksLimit: 8,
                                },
                                grid: { color: '#eeddd030' },
                            },
                            y: {
                                ticks: {
                                    font: { family: 'ui-monospace, monospace', size: 11 },
                                    color: '#6b5e54',
                                    callback: function (val) { return '£' + (val / 100).toFixed(2); },
                                },
                                grid: { color: '#eeddd060' },
                            },
                        },
                    },
                });
            })
            .catch(function (err) {
                console.error('Chart load failed for ' + slug + ':', err);
                canvas.parentElement.insertAdjacentHTML('beforeend', '<p class="pi-empty">Chart unavailable.</p>');
                canvas.style.display = 'none';
            });
    });
}());
</script>
@endpush
