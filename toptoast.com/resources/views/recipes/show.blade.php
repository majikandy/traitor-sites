@extends('layouts.app')

@section('title', $recipe->title)
@section('meta_description', $recipe->description)
@section('body_class', 'recipe-page')

@section('content')

<div class="recipe-hero" style="background-color: {{ $recipe->hero_color }};">
    <div class="container">
        <a href="/recipes" class="recipe-back">&larr; All Recipes</a>
        <span class="recipe-hero-emoji">{!! $recipe->emoji_html !!}</span>
        <div class="recipe-category-badge">{{ config('toptoast.categories')[$recipe->category] ?? $recipe->category }}</div>
        <h1>{{ $recipe->title }}</h1>
        <p class="recipe-hero-desc">{{ $recipe->description }}</p>
        <div class="recipe-hero-meta">
            <span class="meta-item">&#x23F1; {{ $recipe->time_minutes }} minutes</span>
            <span class="meta-item">{{ $recipe->difficulty }}</span>
            <span class="meta-item">{{ count($recipe->ingredients_json) }} ingredients</span>
        </div>
    </div>
</div>

<div class="container">
    <div class="recipe-content">

        <aside class="recipe-ingredients">
            <h2>Ingredients</h2>
            <ul>
                @foreach($recipe->ingredients_json as $ingredient)
                    <li>
                        <label class="ingredient-check">
                            <input type="checkbox">
                            <span>{{ $ingredient }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="recipe-method">
            <h2>Method</h2>
            <ol class="method-steps">
                @foreach($recipe->steps_json as $index => $step)
                    <li>
                        <span class="step-num">{{ $index + 1 }}</span>
                        <p>{{ $step }}</p>
                    </li>
                @endforeach
            </ol>

            @if($recipe->tip)
                <div class="recipe-tip">
                    <span class="tip-icon">&#x1F4A1;</span>
                    <div>
                        <strong>Pro Tip</strong>
                        <p>{{ $recipe->tip }}</p>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

@if($moreRecipes->isNotEmpty())
<section class="recipe-nav-bottom">
    <div class="container">
        <h2>More Recipes</h2>
        <div class="recipe-grid recipe-grid-small">
            @foreach($moreRecipes as $more)
                <a href="{{ route('recipes.show', $more->slug) }}" class="recipe-card">
                    <div class="recipe-card-image" style="background-color: {{ $more->hero_color }}20;">
                        <span class="recipe-card-emoji">{!! $more->emoji_html !!}</span>
                    </div>
                    <div class="recipe-card-body">
                        <span class="recipe-card-category">{{ config('toptoast.categories')[$more->category] ?? $more->category }}</span>
                        <h3>{{ $more->title }}</h3>
                        <p>{{ $more->description }}</p>
                        <div class="recipe-card-meta">
                            <span>&#x23F1; {{ $more->time_minutes }} min</span>
                            <span>{{ $more->difficulty }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
