@extends('layouts.app')

@section('title', $ingredient->name . ' — Price History')
@section('meta_description', 'Price history for ' . $ingredient->name . ' across UK supermarkets — Top Toast Price Index.')

@section('content')

<div class="page-header">
    <div class="container">
        <a href="{{ route('price-index.index') }}" class="pi-back-link">&larr; Price Index</a>
        <h1>{{ $ingredient->emoji }} {{ $ingredient->name }}</h1>
        <p class="page-intro">52-week price history across all tracked supermarkets.</p>
    </div>
</div>

{{-- =============================================
     STATS BAR
     ============================================= --}}
<section class="pi-section">
    <div class="container">
        @if($minPrice !== null && $maxPrice !== null)
            <div class="pi-stats-bar">
                <div class="pi-stat-block">
                    <div class="pi-stat-label">Price Range</div>
                    <div class="pi-stat-num">&pound;{{ number_format($minPrice / 100, 2) }} &ndash; &pound;{{ number_format($maxPrice / 100, 2) }}</div>
                </div>
                @if($cheapestRetailer)
                    <div class="pi-stat-block pi-stat-good">
                        <div class="pi-stat-label">Cheapest Retailer</div>
                        <div class="pi-stat-num">&#x1F451; {{ $cheapestRetailer->name }}</div>
                    </div>
                @endif
                @if($mostExpensiveRetailer && $mostExpensiveRetailer->id !== $cheapestRetailer?->id)
                    <div class="pi-stat-block pi-stat-bad">
                        <div class="pi-stat-label">Most Expensive</div>
                        <div class="pi-stat-num">{{ $mostExpensiveRetailer->name }}</div>
                    </div>
                @endif
                <div class="pi-stat-block">
                    <div class="pi-stat-label">Price Spread</div>
                    <div class="pi-stat-num">{{ $maxPrice - $minPrice }}p</div>
                </div>
            </div>
        @else
            <p class="pi-empty">No price data available for this ingredient yet.</p>
        @endif
    </div>
</section>

{{-- =============================================
     FULL-WIDTH CHART
     ============================================= --}}
<section class="pi-section">
    <div class="container">
        <div class="pi-chart-card pi-chart-full">
            <div class="pi-chart-heading">
                <span>Price History — {{ $ingredient->name }} ({{ $ingredient->unit }})</span>
            </div>
            @if($byRetailer->isEmpty())
                <p class="pi-empty">No price observations recorded yet.</p>
            @else
                <canvas id="ingredient-chart" height="120" aria-label="Full price history chart for {{ $ingredient->name }}"></canvas>
            @endif
        </div>
    </div>
</section>

{{-- =============================================
     PER-RETAILER DATA TABLE
     ============================================= --}}
@if($byRetailer->isNotEmpty())
<section class="pi-section">
    <div class="container">
        <h2 class="pi-section-title">Observation Log</h2>
        @foreach($byRetailer as $retailerId => $observations)
            @php $retailer = $observations->first()->retailer; @endphp
            <div class="pi-table-card" style="margin-bottom:1.5rem;">
                <div class="pi-table-heading">
                    @if($retailer)
                        <span class="pi-retailer-dot" style="background:{{ $retailer->color ?? '#ccc' }};"></span>
                        <span>{{ $retailer->name }}</span>
                    @else
                        <span>Unknown Retailer</span>
                    @endif
                </div>
                <table class="pi-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($observations->sortByDesc('observed_on') as $obs)
                            <tr>
                                <td class="pi-date">{{ Carbon\Carbon::parse($obs->observed_on)->format('j M Y') }}</td>
                                <td><span class="pi-price">&pound;{{ number_format($obs->price_pence / 100, 2) }}</span></td>
                                <td class="pi-date">{{ $obs->source ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</section>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('ingredient-chart');
    if (!canvas) return;

    fetch('/price-index/api/chart/{{ $ingredient->slug }}')
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (data) {
            if (!data.labels || data.labels.length === 0) {
                canvas.parentElement.insertAdjacentHTML('beforeend', '<p class="pi-empty">No chart data available yet.</p>');
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
                                font: { family: 'DM Sans, sans-serif', size: 13 },
                                color: '#6b5e54',
                                boxWidth: 14,
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
                                font: { family: 'DM Sans, sans-serif', size: 12 },
                                color: '#6b5e54',
                                maxTicksLimit: 12,
                            },
                            grid: { color: '#eeddd030' },
                        },
                        y: {
                            ticks: {
                                font: { family: 'ui-monospace, monospace', size: 12 },
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
            console.error('Chart load failed:', err);
        });
}());
</script>
@endpush
