@extends('layouts.app')

@section('title', $food->name . ' — Protein Content — Protein-In')
@section('description', 'How much protein is in ' . $food->name . '? ' . $food->protein_per_100g . 'g per 100g.')

@section('content')
<h1>{{ $food->name }}</h1>

<div style="display:flex;gap:2rem;margin:1.5rem 0;flex-wrap:wrap;">
    <div style="text-align:center;">
        <div class="protein-badge">{{ $food->protein_per_100g }}g</div>
        <div class="meta">protein per 100g</div>
    </div>
    @if($food->protein_per_serving)
    <div style="text-align:center;">
        <div class="protein-badge">{{ $food->protein_per_serving }}g</div>
        <div class="meta">protein per {{ $food->serving_size }}</div>
    </div>
    @endif
    @if($food->calories_per_100g)
    <div style="text-align:center;">
        <div style="font-size:1.5rem;font-weight:700;">{{ $food->calories_per_100g }}</div>
        <div class="meta">kcal per 100g</div>
    </div>
    @endif
</div>

@if($food->fat_per_100g || $food->carbs_per_100g || $food->fibre_per_100g)
<table style="border-collapse:collapse;margin-bottom:1.5rem;">
    <tr><th style="text-align:left;padding:0.3rem 1rem 0.3rem 0;color:#78716c;">Macros per 100g</th><th></th></tr>
    @if($food->fat_per_100g)<tr><td>Fat</td><td>{{ $food->fat_per_100g }}g</td></tr>@endif
    @if($food->carbs_per_100g)<tr><td>Carbs</td><td>{{ $food->carbs_per_100g }}g</td></tr>@endif
    @if($food->fibre_per_100g)<tr><td>Fibre</td><td>{{ $food->fibre_per_100g }}g</td></tr>@endif
</table>
@endif

@if($food->description)
<p style="margin-bottom:1.5rem;">{{ $food->description }}</p>
@endif

@if($food->categories->count())
<div style="margin-bottom:0.75rem;">
    @foreach($food->categories as $category)
    <a class="pill" href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
    @endforeach
</div>
@endif

@if($food->tags->count())
<div>
    @foreach($food->tags as $tag)
    <a class="pill" href="{{ route('tag.show', $tag) }}" style="background:#f0fdf4;color:#166534;">{{ $tag->name }}</a>
    @endforeach
</div>
@endif
@endsection
