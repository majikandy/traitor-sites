@if ($paginator->hasPages())
<nav style="display:flex;gap:0.25rem;margin-top:2rem;justify-content:center;flex-wrap:wrap;align-items:center;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#d6d3d1;font-size:0.875rem;">&lsaquo;</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#b45309;font-size:0.875rem;">&lsaquo;</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding:0.35rem 0.4rem;font-size:0.875rem;color:#78716c;">{{ $element }}</span>
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
        <a href="{{ $paginator->nextPageUrl() }}" style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#b45309;font-size:0.875rem;">&rsaquo;</a>
    @else
        <span style="padding:0.35rem 0.65rem;border:1px solid #e7e5e4;border-radius:4px;color:#d6d3d1;font-size:0.875rem;">&rsaquo;</span>
    @endif
</nav>
@endif
