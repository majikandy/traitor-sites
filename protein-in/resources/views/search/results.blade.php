@extends('layouts.app')

@section('title', 'Protein in "' . $query . '" — Protein-In')
@section('description', 'How much protein is in ' . $query . '? See protein content per 100g for all matching foods.')

@section('content')
<h1>Protein in "{{ $query }}"</h1>
<p class="meta">{{ $foods->total() }} {{ Str::plural('result', $foods->total()) }} — sorted by protein content</p>

<div class="food-grid">
    @forelse($foods as $food)
    <a class="food-card" href="{{ route('foods.show', $food) }}" style="display:block;color:inherit;">
        <h3>{{ $food->name }}</h3>
        <div class="protein">{{ $food->protein_per_100g }}g protein per 100g</div>
        @if($food->protein_per_serving)
        <div style="color:#78716c;font-size:0.8rem;">{{ $food->protein_per_serving }}g per {{ $food->serving_size }}</div>
        @endif
    </a>
    @empty
    <p>No results for "{{ $query }}". Try a different search.</p>
    @endforelse
</div>

{{ $foods->links() }}
@endsection
