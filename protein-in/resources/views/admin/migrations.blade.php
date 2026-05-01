@extends('layouts.admin')

@section('title', 'Migrations — Admin')

@section('content')
<h1>Migrations</h1>

<form method="POST" action="{{ route('admin.migrations.run') }}" style="margin-bottom:1.5rem;">
    @csrf
    <button type="submit" class="btn-primary">Run pending migrations</button>
</form>

@if(session('migrate_output'))
<div class="import-result" style="margin-bottom:1.5rem;">
    <pre>{{ session('migrate_output') }}</pre>
</div>
@endif

@if($pending->count())
<h2 style="color:#b45309;">Pending ({{ $pending->count() }})</h2>
<table class="admin-table" style="margin-bottom:2rem;">
    <thead><tr><th>Migration</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($pending as $name)
    <tr>
        <td>{{ $name }}</td>
        <td><span style="color:#b45309;font-weight:600;">pending</span></td>
    </tr>
    @endforeach
    </tbody>
</table>
@else
<p style="color:#16a34a;font-weight:600;margin-bottom:2rem;">All migrations have run.</p>
@endif

<h2>Ran ({{ $ran->count() }})</h2>
<table class="admin-table">
    <thead><tr><th>Migration</th><th>Batch</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($ran as $m)
    <tr>
        <td>{{ $m->migration }}</td>
        <td style="text-align:center;">{{ $m->batch }}</td>
        <td><span style="color:#16a34a;font-weight:600;">ran</span></td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection
