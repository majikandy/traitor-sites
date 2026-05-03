@extends('layouts.app')

@section('title', $currentCategory ? (config('toptoast.categories')[$currentCategory] ?? $currentCategory) . ' Recipes' : 'All Recipes')
@section('meta_description', 'Browse all Top Toast recipes — sweet, savoury, brunch, and quick toast ideas.')

@section('content')

<div class="page-header">
    <div class="container">
        <h1>
            @if($currentCategory && isset($categories[$currentCategory]))
                {{ $categories[$currentCategory] }} Recipes
            @else
                All Recipes
            @endif
        </h1>
        <p class="page-intro">
            @if($currentCategory)
                Showing {{ $recipes->count() }} {{ strtolower($categories[$currentCategory] ?? $currentCategory) }} recipe{{ $recipes->count() !== 1 ? 's' : '' }}.
            @else
                {{ $recipes->count() }} recipe{{ $recipes->count() !== 1 ? 's' : '' }} and counting. Toast, elevated.
            @endif
        </p>
    </div>
</div>

<div class="recipe-filters">
    <div class="container">
        <div class="filter-bar">
            <a href="/recipes" class="filter-chip {{ !$currentCategory ? 'active' : '' }}">All</a>
            @foreach($categories as $slug => $label)
                <a href="/recipes?cat={{ $slug }}" class="filter-chip {{ $currentCategory === $slug ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="recipes-listing">
    <div class="container">
        @if($recipes->isEmpty())
            <p style="text-align:center; color: var(--color-text-light); padding: 3rem 0;">
                No recipes in this category yet — check back soon.
            </p>
        @else
            <div class="recipe-grid">
                @foreach($recipes as $recipe)
                    <a href="{{ route('recipes.show', $recipe->slug) }}" class="recipe-card">
                        <div class="recipe-card-image" style="background-color: {{ $recipe->hero_color }}20;">
                            <span class="recipe-card-emoji">{!! $recipe->emoji_html !!}</span>
                        </div>
                        <div class="recipe-card-body">
                            <span class="recipe-card-category">{{ $categories[$recipe->category] ?? $recipe->category }}</span>
                            <h3>{{ $recipe->title }}</h3>
                            <p>{{ $recipe->description }}</p>
                            <div class="recipe-card-meta">
                                <span>&#x23F1; {{ $recipe->time_minutes }} min</span>
                                <span>{{ $recipe->difficulty }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
