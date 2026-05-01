@extends('layouts.admin')

@section('title', 'Logs — Admin')

@section('content')
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
    <h1 style="margin:0;">Laravel Logs</h1>
    <form method="POST" action="{{ route('admin.logs.clear') }}">
        @csrf
        <button type="submit" class="btn-import" onclick="return confirm('Clear the log file?')">Clear log</button>
    </form>
</div>

@if(session('cleared'))
<div class="import-result" style="margin-bottom:1rem;"><pre>Log cleared.</pre></div>
@endif

@if(empty($lines))
<p style="color:#78716c;">Log file is empty.</p>
@else
<div style="background:#1c1917;color:#d6d3d1;border-radius:8px;padding:1.25rem;font-family:monospace;font-size:0.75rem;line-height:1.6;overflow-x:auto;max-height:80vh;overflow-y:auto;">
    @foreach($lines as $line)
    <div style="
        @if(str_contains($line, '.ERROR') || str_contains($line, 'ERROR:')) color:#fca5a5;
        @elseif(str_contains($line, '.WARNING') || str_contains($line, 'WARNING:')) color:#fcd34d;
        @elseif(str_contains($line, '.INFO') || str_contains($line, 'INFO:')) color:#86efac;
        @else color:#d6d3d1;
        @endif
        white-space:pre-wrap;word-break:break-all;">{{ $line }}</div>
    @endforeach
</div>
@endif
@endsection
