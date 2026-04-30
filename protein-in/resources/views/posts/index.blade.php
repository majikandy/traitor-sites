@extends('layouts.app')

@section('title', 'Blog — Protein-In')
@section('description', 'Articles about protein, nutrition, fitness and diet from Protein-In.')

@section('content')
<h1>Blog</h1>
<p class="meta">{{ $posts->total() }} {{ Str::plural('article', $posts->total()) }}</p>

@forelse($posts as $post)
<article style="padding:1.25rem 0;border-bottom:1px solid #e7e5e4;">
    <h2 style="font-size:1.15rem;margin:0 0 0.25rem;">
        <a href="{{ route('post.show', [$post->published_at->format('Y'), $post->published_at->format('m'), $post->published_at->format('d'), $post->slug]) }}">
            {{ $post->title }}
        </a>
    </h2>
    <div class="meta">{{ $post->published_at->format('j F Y') }}</div>
    @if($post->excerpt)
    <p style="margin-top:0.4rem;color:#44403c;">{{ $post->excerpt }}</p>
    @endif
    @foreach($post->categories as $category)
        <a class="pill" href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
    @endforeach
</article>
@empty
<p style="margin-top:2rem;color:#78716c;">No posts yet — check back soon.</p>
@endforelse

{{ $posts->links() }}
@endsection
