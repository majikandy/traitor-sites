@extends('layouts.app')

@section('title', $post->title . ' — Protein-In')
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))

@section('content')
<article>
    <h1>{{ $post->title }}</h1>
    <div class="meta">{{ $post->published_at->format('j F Y') }}</div>

    <div style="margin:1.5rem 0;line-height:1.8;">
        {!! nl2br(e($post->content)) !!}
    </div>

    @if($post->categories->count())
    <div style="margin-bottom:0.5rem;">
        @foreach($post->categories as $category)
        <a class="pill" href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
        @endforeach
    </div>
    @endif

    @if($post->tags->count())
    <div>
        @foreach($post->tags as $tag)
        <a class="pill" href="{{ route('tag.show', $tag) }}" style="background:#f0fdf4;color:#166534;">{{ $tag->name }}</a>
        @endforeach
    </div>
    @endif
</article>
@endsection
