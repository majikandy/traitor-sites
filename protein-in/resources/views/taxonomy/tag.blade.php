@extends('layouts.app')

@section('title', $tag->name . ' — Protein-In')
@section('description', 'Foods and articles tagged ' . $tag->name . ' on Protein-In.')

@section('content')
<h1>{{ $tag->name }}</h1>

@if($foods->count())
<h2>Foods</h2>
<div class="food-grid">
    @foreach($foods as $food)
    <a class="food-card" href="{{ route('foods.show', $food) }}" style="display:block;color:inherit;">
        <h3>{{ $food->name }}</h3>
        <div class="protein">{{ $food->protein_per_100g }}g protein per 100g</div>
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
