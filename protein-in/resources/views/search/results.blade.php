@extends('layouts.app')

@section('title', 'Protein in "' . $query . '" — Protein-In')
@section('description', 'How much protein is in ' . $query . '? See protein content per 100g for all matching foods.')

@section('content')
<h1>Protein in "{{ $query }}"</h1>

@if($needsImport)
<div id="import-status" style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;color:#b45309;font-size:0.95rem;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;animation:spin 1s linear infinite;">
        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
    </svg>
    <span id="import-msg">Searching our database…</span>
</div>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>
@else
<p class="meta" id="result-count">{{ $foods->total() }} {{ Str::plural('result', $foods->total()) }} — sorted by popularity</p>
@endif

<div id="results-grid" class="food-grid">
    @forelse($foods as $food)
    <a class="food-card" href="{{ route('foods.show', $food) }}" style="display:block;color:inherit;">
        @if($food->image_url)
        <img src="{{ $food->image_url }}" alt="{{ $food->name }}" style="width:100%;height:80px;object-fit:contain;margin-bottom:0.5rem;border-radius:4px;background:#fafaf9;">
        @endif
        <h3>{{ $food->name }}</h3>
        <div class="protein">{{ $food->protein_per_100g }}g <span style="font-weight:400;color:#78716c;font-size:0.8rem;">protein / 100g</span></div>
        @if($food->calories_per_100g)
        <div style="color:#78716c;font-size:0.8rem;">{{ $food->calories_per_100g }} kcal</div>
        @endif
        @if($food->carbs_per_100g || $food->fat_per_100g || $food->fibre_per_100g)
        <div style="color:#a8a29e;font-size:0.75rem;margin-top:0.25rem;">
            @if($food->carbs_per_100g)C: {{ $food->carbs_per_100g }}g @endif
            @if($food->fat_per_100g)F: {{ $food->fat_per_100g }}g @endif
            @if($food->fibre_per_100g)Fi: {{ $food->fibre_per_100g }}g @endif
        </div>
        @endif
    </a>
    @empty
    @if(!$needsImport)
    <p>No results for "{{ $query }}". Try a different search.</p>
    @endif
    @endforelse
</div>

{{ $foods->links() }}

@if($needsImport)
<script>
(function () {
    const messages = [
        'Searching our database…',
        'Checking spelling variants…',
        'Finding matches…',
        'Fetching from USDA…',
        'Calculating macros…',
        'Extracting protein data…',
        'Cross-referencing nutrients…',
        'Almost there…',
    ];
    let i = 0;
    const el = document.getElementById('import-msg');
    const timer = setInterval(() => {
        i = (i + 1) % messages.length;
        el.textContent = messages[i];
    }, 600);

    fetch('{{ route('search.backfill', rawurlencode($query)) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        clearInterval(timer);
        // Snap straight to fresh results
        window.location.reload();
    })
    .catch(() => {
        clearInterval(timer);
        window.location.reload();
    });
})();
</script>
@endif
@endsection
