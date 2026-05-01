@extends('layouts.admin')

@section('title', 'Nutrition Backfill — Admin')

@section('content')
<h1>Nutrition Backfill</h1>

<div style="display:flex;gap:2rem;margin:1.5rem 0;">
    <div class="stat-card">
        <div class="stat-num">{{ $total }}</div>
        <div class="stat-label">Total foods</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">{{ $withFdcId }}</div>
        <div class="stat-label">Have USDA ID (backfillable)</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">{{ $total - $withFdcId }}</div>
        <div class="stat-label">No USDA ID (manual only)</div>
    </div>
</div>

<p style="color:#78716c;font-size:0.875rem;margin-bottom:1.5rem;">
    Backfill fetches full nutrient data from USDA FoodData Central for every food that has a stored USDA ID.
    Foods without a USDA ID were imported before ID tracking was added — re-importing them via the dashboard will pick up the ID.
</p>

<form method="POST" action="{{ route('admin.nutrition-backfill.run') }}" style="margin-bottom:2rem;">
    @csrf
    <button type="submit" class="btn-primary" onclick="return confirm('This will make {{ $withFdcId }} API calls to USDA and may take a minute. Continue?')">
        Run backfill for {{ $withFdcId }} foods
    </button>
</form>

@if(session('backfill_result'))
<div class="import-result" style="margin-bottom:2rem;">
    <pre>{{ session('backfill_result') }}</pre>
</div>
@endif

<h2>Coverage</h2>
<table class="admin-table">
    <thead>
        <tr>
            <th>Nutrient</th>
            <th style="text-align:right;">Filled</th>
            <th style="text-align:right;">Missing</th>
            <th style="min-width:200px;">Coverage</th>
        </tr>
    </thead>
    <tbody>
    @foreach($coverage as $row)
    @php $pct = $row['total'] ? round($row['filled'] / $row['total'] * 100) : 0; @endphp
    <tr>
        <td>{{ $row['label'] }}</td>
        <td style="text-align:right;">{{ number_format($row['filled']) }}</td>
        <td style="text-align:right;color:{{ $row['missing'] > 0 ? '#b45309' : '#16a34a' }};">{{ number_format($row['missing']) }}</td>
        <td>
            <div style="background:#e7e5e4;border-radius:4px;height:8px;overflow:hidden;">
                <div style="background:{{ $pct > 75 ? '#16a34a' : ($pct > 25 ? '#b45309' : '#dc2626') }};height:100%;width:{{ $pct }}%;"></div>
            </div>
            <span style="font-size:0.75rem;color:#78716c;">{{ $pct }}%</span>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection
