@extends('layouts.app')

@section('title', 'About')
@section('meta_description', 'The story behind Top Toast — who we are, what we believe, and why toast deserves better.')

@section('content')

<div class="page-header">
    <div class="container">
        <h1>About Top Toast</h1>
        <p class="page-intro">Toast deserves better. We&rsquo;re here to prove it.</p>
    </div>
</div>

<div class="about-content">
    <div class="container">

        <div class="about-block">
            <h2>Origin Story</h2>
            <p>It started, as most things do, with a disappointing breakfast. Toast that was underdone. Toppings that were an afterthought. A café charging &pound;12 for something assembled with no care whatsoever.</p>
            <p>Top Toast exists because toast — done properly — is one of the great foods. Cheap, fast, endlessly variable, and deeply satisfying when you get it right. It just requires a little thought.</p>
            <p><em>This site is that thought, written down.</em></p>
        </div>

        <div class="about-block">
            <h2>The Mission</h2>
            <p>Three things:</p>
            <p><strong>Great recipes.</strong> Not ten thousand mediocre ones. A curated collection of toast combinations that actually work, with real instructions written by someone who has made them many times.</p>
            <p><strong>Honest prices.</strong> The Top Toast Price Index tracks what toast ingredients actually cost across UK supermarkets, updated weekly. Because avocados are expensive now, and you deserve to know which supermarket is gouging you.</p>
            <p><strong>No nonsense.</strong> No life stories before the recipe. No ads disguised as content. Just toast.</p>
        </div>

        <div class="about-block">
            <h2>The Rules</h2>
            <div class="rules-list">
                <div class="rule">
                    <span class="rule-number">01</span>
                    <div>
                        <h3>Bread quality matters enormously.</h3>
                        <p>The toast is the foundation. Good sourdough from a proper bakery will improve every recipe on this site. Pre-sliced white bread will not. We don&rsquo;t judge, but we note the difference.</p>
                    </div>
                </div>
                <div class="rule">
                    <span class="rule-number">02</span>
                    <div>
                        <h3>Actually toast the bread.</h3>
                        <p>Pale, barely-warmed bread is not toast. Toast should be golden. You should hear it when you tap it. The Maillard reaction is not optional — it&rsquo;s the point.</p>
                    </div>
                </div>
                <div class="rule">
                    <span class="rule-number">03</span>
                    <div>
                        <h3>Season properly.</h3>
                        <p>Flaky sea salt. Every time. On sweet things too. It is not optional. The difference between well-seasoned toast and under-seasoned toast is the difference between a good breakfast and a great one.</p>
                    </div>
                </div>
                <div class="rule">
                    <span class="rule-number">04</span>
                    <div>
                        <h3>Use good olive oil.</h3>
                        <p>Not a lot. Just a drizzle. But it should taste of something. Cheap olive oil is cooking fat. Decent olive oil is a flavour. The difference is worth the extra &pound;2.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-cta">
            <p>Right. Now go make some toast.</p>
            <a href="/recipes" class="btn btn-primary">Browse Recipes</a>
        </div>

    </div>
</div>

@endsection
