@extends('layouts.admin')

@section('title', 'Admin — Protein-In')

@section('content')
<h1>Dashboard</h1>

<div style="display:flex;gap:2rem;margin:1.5rem 0;">
    <div class="stat-card">
        <div class="stat-num">{{ $foodCount }}</div>
        <div class="stat-label">Foods in database</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">{{ $searchCount }}</div>
        <div class="stat-label">Total searches</div>
    </div>
</div>

<h2>Legacy posts</h2>
<p style="color:#78716c;font-size:0.875rem;margin-bottom:0.75rem;">102 known URLs from the original protein-in.com (2010–2011). Fetches each from Wayback Machine and imports as published posts.</p>
<form method="POST" action="{{ route('admin.import-posts') }}">
    @csrf
    <button type="submit" class="btn-primary">Import all legacy posts from Wayback Machine</button>
</form>

@if(session('post_import_result'))
<div class="import-result" style="margin-top:1rem;">
    <pre>{{ session('post_import_result') }}</pre>
</div>
@endif

<form method="POST" action="{{ route('admin.export-posts') }}" style="margin-top:0.75rem;">
    @csrf
    <button type="submit" class="btn-import">Save to posts.json (hardcode for seeder)</button>
</form>

@if(session('post_export_result'))
<div class="import-result" style="margin-top:0.75rem;">
    <pre>{{ session('post_export_result') }}</pre>
</div>
@endif

<h2>Top zero-result searches</h2>
<p style="color:#78716c;font-size:0.875rem;margin-bottom:1rem;">People searched for these but we had no data. Import them below.</p>

@if($zeroResultSearches->count())
<table class="admin-table">
    <thead><tr><th>Query</th><th>Searches</th><th>Import</th></tr></thead>
    <tbody>
    @foreach($zeroResultSearches as $row)
    <tr>
        <td><strong>{{ $row->query }}</strong></td>
        <td>{{ $row->total }}</td>
        <td>
            <form method="POST" action="{{ route('admin.import') }}">
                @csrf
                <input type="hidden" name="query" value="{{ $row->query }}">
                <button type="submit" class="btn-import">Import from USDA</button>
            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@else
<p>No zero-result searches yet.</p>
@endif

@if(session('import_result'))
<div class="import-result">
    <strong>Import result for "{{ session('import_query') }}":</strong>
    <pre>{{ session('import_result') }}</pre>
</div>
@endif

<div style="margin-top:2rem;">
    <h2>Manual import</h2>
    <form method="POST" action="{{ route('admin.import') }}" style="display:flex;gap:0.5rem;align-items:flex-end;">
        @csrf
        <div>
            <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Search USDA for</label>
            <input type="text" name="query" placeholder="e.g. chicken breast" class="admin-input" required>
        </div>
        <button type="submit" class="btn-primary">Import</button>
    </form>
</div>
@endsection
