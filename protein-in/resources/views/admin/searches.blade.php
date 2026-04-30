@extends('layouts.admin')

@section('title', 'Search Queries — Admin')

@section('content')
<h1>Search queries</h1>
<p style="color:#78716c;font-size:0.875rem;margin-bottom:1.5rem;">{{ $queries->total() }} unique queries recorded</p>

@if(session('import_result'))
<div class="import-result">
    <strong>Import result for "{{ session('import_query') }}":</strong>
    <pre>{{ session('import_result') }}</pre>
</div>
@endif

<table class="admin-table">
    <thead>
        <tr>
            <th>Query</th>
            <th>Times searched</th>
            <th>Had results?</th>
            <th>Import</th>
        </tr>
    </thead>
    <tbody>
    @foreach($queries as $row)
    <tr>
        <td>
            <a href="{{ route('search.show', $row->query) }}" target="_blank">{{ $row->query }}</a>
        </td>
        <td>{{ $row->total }}</td>
        <td>
            @if($row->total_results > 0)
                <span style="color:#166534;">Yes</span>
            @else
                <span style="color:#991b1b;">No</span>
            @endif
        </td>
        <td>
            @if($row->total_results == 0)
            <form method="POST" action="{{ route('admin.import') }}">
                @csrf
                <input type="hidden" name="query" value="{{ $row->query }}">
                <button type="submit" class="btn-import">Import from USDA</button>
            </form>
            @else
            <span style="color:#78716c;font-size:0.8rem;">—</span>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

{{ $queries->links() }}
@endsection
