@extends('layouts.app')

@section('title', 'Protein in "' . $query . '" — Protein-In')
@section('description', 'How much protein is in ' . $query . '? See protein content per 100g for all matching foods.')

@section('content')
<h1>Protein in "{{ $query }}"</h1>
<p class="meta">{{ $foods->total() }} {{ Str::plural('result', $foods->total()) }} — sorted by protein content</p>

<div class="food-grid">
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
    <p>No results for "{{ $query }}". Try a different search.</p>
    @endforelse
</div>

{{ $foods->links() }}
@endsection
