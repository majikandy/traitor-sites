@extends('layouts.app')

@section('title', $post->title . ' — Protein-In')
@section('description', $post->excerpt ?: 'Read about ' . $post->title . ' on Protein-In, the protein-first nutrition resource.')

@section('content')
<nav style="font-size:0.8rem;color:#78716c;margin-bottom:1rem;">
    <a href="/" style="color:#78716c;">Home</a> &rsaquo;
    <a href="{{ route('blog.index') }}" style="color:#78716c;">Blog</a> &rsaquo;
    {{ $post->title }}
</nav>

<article>
    <h1>{{ $post->title }}</h1>
    <div class="meta" style="margin-bottom:1.5rem;">{{ $post->published_at->format('j F Y') }}</div>

    @if($post->status === 'stub')
    <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <p style="color:#92400e;margin-bottom:0.5rem;font-weight:600;">Original content coming soon</p>
        <p style="color:#78716c;font-size:0.9rem;">This article was part of the original Protein-In archive. We're rebuilding the content — check back soon, or explore related articles below.</p>
    </div>
    @else
    <div style="margin:1.5rem 0;line-height:1.8;">
        {!! $post->content !!}
    </div>
    @endif

    @if($post->categories->count())
    <div style="margin-bottom:0.5rem;">
        @foreach($post->categories as $category)
        <a class="pill" href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
        @endforeach
    </div>
    @endif

    @if($post->tags->count())
    <div style="margin-bottom:1.5rem;">
        @foreach($post->tags as $tag)
        <a class="pill" href="{{ route('tag.show', $tag) }}" style="background:#f0fdf4;color:#166534;">{{ $tag->name }}</a>
        @endforeach
    </div>
    @endif
</article>

@if($related->count())
<div style="margin-top:2.5rem;border-top:1px solid #e7e5e4;padding-top:1.5rem;">
    <h2 style="margin-bottom:1rem;">More articles</h2>
    <ul style="list-style:none;display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:0.75rem;">
        @foreach($related as $rel)
        <li>
            <a href="{{ route('post.show', [$rel->published_at->format('Y'), $rel->published_at->format('m'), $rel->published_at->format('d'), $rel->slug]) }}"
               style="display:block;background:#fff;border:1px solid #e7e5e4;border-radius:8px;padding:0.85rem 1rem;color:#1c1917;font-size:0.9rem;font-weight:500;">
                {{ $rel->title }}
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif
@endsection
