@extends('layouts.app')

@section('title', 'Browse foods by category — Protein-In')

@section('content')
<nav style="font-size:0.8rem;color:#78716c;margin-bottom:1rem;">
    <a href="/" style="color:#78716c;">Home</a> &rsaquo; Browse
</nav>

<h1>Browse foods</h1>

@if($categories->count())
<h2 style="margin-top:1.5rem;">Categories</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem;margin-top:0.75rem;margin-bottom:2rem;">
    @foreach($categories as $category)
    <a href="{{ route('category.show', $category) }}" style="display:block;background:#fff;border:1px solid #e7e5e4;border-radius:8px;padding:0.85rem 1rem;color:inherit;text-decoration:none;">
        <div style="font-weight:600;font-size:0.95rem;">{{ $category->name }}</div>
        <div style="color:#78716c;font-size:0.8rem;margin-top:0.2rem;">{{ $category->foods_count }} {{ Str::plural('food', $category->foods_count) }}</div>
    </a>
    @endforeach
</div>
@endif

<h2>All foods <span style="font-weight:400;color:#78716c;font-size:1rem;">— A to Z</span></h2>
<p class="meta" style="margin-bottom:0.75rem;">{{ $foods->total() }} foods</p>

<div class="food-grid">
    @foreach($foods as $food)
    <a class="food-card" href="{{ route('foods.show', $food) }}" style="display:block;color:inherit;">
        <h3>{{ $food->name }}</h3>
        <div class="protein">{{ $food->protein_per_100g }}g protein per 100g</div>
        @if($food->protein_per_serving)
        <div style="color:#78716c;font-size:0.8rem;">{{ $food->protein_per_serving }}g per {{ $food->serving_size }}</div>
        @endif
    </a>
    @endforeach
</div>

{{ $foods->links() }}
@endsection
