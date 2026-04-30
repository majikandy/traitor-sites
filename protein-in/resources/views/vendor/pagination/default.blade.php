@if ($paginator->hasPages())
<nav style="display:flex;gap:0.25rem;margin-top:2rem;justify-content:center;flex-wrap:wrap;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#d6d3d1;font-size:0.875rem;display:flex;align-items:center;"><svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block"><path d="M6 1L1 6l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#b45309;font-size:0.875rem;display:flex;align-items:center;"><svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block"><path d="M6 1L1 6l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding:0.35rem 0.65rem;font-size:0.875rem;color:#78716c;">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="padding:0.35rem 0.65rem;border:1px solid #b45309;border-radius:4px;background:#b45309;color:#fff;font-size:0.875rem;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#1c1917;font-size:0.875rem;">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#b45309;font-size:0.875rem;display:flex;align-items:center;"><svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block"><path d="M1 1l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    @else
        <span style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#d6d3d1;font-size:0.875rem;display:flex;align-items:center;"><svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block"><path d="M1 1l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
    @endif
</nav>
@endif
