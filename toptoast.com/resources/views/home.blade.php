@extends('layouts.app')

@section('title', 'Toast. Elevated.')

@section('content')

<section class="hero">
    <div class="container">
        <span class="hero-tag">&#x1F35E; The Home of Serious Toast</span>
        <h1>Toast is not<br><span class="highlight">just breakfast.</span></h1>
        <p class="hero-sub">Recipes that treat toast with the respect it deserves. Plus the definitive price index tracking what toast really costs across UK supermarkets.</p>
        <a href="/recipes" class="btn btn-primary">Browse All Recipes</a>
    </div>
</section>

@if($recipes->isNotEmpty())
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Recipes</h2>
            <a href="/recipes" class="view-all">View all &rarr;</a>
        </div>
        <div class="recipe-grid">
            @foreach($recipes as $recipe)
                <a href="{{ route('recipes.show', $recipe->slug) }}" class="recipe-card">
                    <div class="recipe-card-image" style="background-color: {{ $recipe->hero_color }}20;">
                        <span class="recipe-card-emoji">{!! $recipe->emoji_html !!}</span>
                    </div>
                    <div class="recipe-card-body">
                        <span class="recipe-card-category">{{ config('toptoast.categories')[$recipe->category] ?? $recipe->category }}</span>
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
    </div>
</section>
@endif

<section class="categories-section">
    <div class="container">
        <h2>Browse by Category</h2>
        <div class="category-grid">
            <a href="/recipes?cat=sweet" class="category-card">
                <span class="category-emoji">&#x1F36F;</span>
                <h3>Sweet</h3>
                <p>Honey, fruit, ricotta. Toast as a treat.</p>
            </a>
            <a href="/recipes?cat=savoury" class="category-card">
                <span class="category-emoji">&#x1F951;</span>
                <h3>Savoury</h3>
                <p>Avo, mushrooms, burrata. Toast done properly.</p>
            </a>
            <a href="/recipes?cat=brunch" class="category-card">
                <span class="category-emoji">&#x1F373;</span>
                <h3>Brunch</h3>
                <p>Weekend-worthy. Eggs, French toast, the works.</p>
            </a>
            <a href="/recipes?cat=quick" class="category-card">
                <span class="category-emoji">&#x26A1;</span>
                <h3>Quick &amp; Easy</h3>
                <p>Five minutes. No excuses. Maximum flavour.</p>
            </a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>The Toast Price Index</h2>
            <p>We track the real cost of toast ingredients across UK supermarkets, every week. Because inflation is real and avocados are getting expensive.</p>
            <br>
            <a href="/price-index" class="btn" style="background:#fff; color: #e85d26; margin-top:1rem;">View the Index &rarr;</a>
        </div>
    </div>
</section>

@endsection
