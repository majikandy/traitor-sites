@extends('layouts.app')

@section('title', $category->name . ' — Protein-In')
@section('description', 'Foods and articles in the ' . $category->name . ' category on Protein-In.')

@section('content')
<nav style="font-size:0.8rem;color:#78716c;margin-bottom:1rem;">
    <a href="/" style="color:#78716c;">Home</a> &rsaquo;
    <a href="{{ route('foods.browse') }}" style="color:#78716c;">Browse</a> &rsaquo;
    {{ $category->name }}
</nav>

<h1>{{ $category->name }}</h1>
@if($category->description)
<p class="meta">{{ $category->description }}</p>
@endif

@if($foods->count())
<h2>Foods</h2>
<div class="food-grid">
    @foreach($foods as $food)
    <a class="food-card" href="{{ route('foods.show', $food) }}" style="display:block;color:inherit;">
        @if($food->image_url)
        <img src="{{ $food->image_url }}" alt="{{ $food->name }}" style="width:100%;height:80px;object-fit:contain;margin-bottom:0.5rem;border-radius:4px;background:#fafaf9;">
        @endif
        <h3>{{ $food->name }}</h3>
        <div class="protein">{{ $food->protein_per_100g }}g <span style="font-weight:400;color:#78716c;font-size:0.8rem;">protein / 100g</span></div>
        @if($food->calories_per_100g)
        <div style="color:#78716c;font-size:0.8rem;">{{ $food->calories_per_100g }} kcal</div>
        @endif
    </a>
    @endforeach
</div>
{{ $foods->links() }}
@endif

@if($posts->count())
<h2>Articles</h2>
<ul style="list-style:none;">
    @foreach($posts as $post)
    <li style="padding:0.5rem 0;border-bottom:1px solid #e7e5e4;">
        <a href="{{ route('post.show', [$post->published_at->format('Y'), $post->published_at->format('m'), $post->published_at->format('d'), $post->slug]) }}">
            {{ $post->title }}
        </a>
        <span class="meta" style="margin-left:0.5rem;">{{ $post->published_at->format('j M Y') }}</span>
    </li>
    @endforeach
</ul>
{{ $posts->links() }}
@endif
@endsection
