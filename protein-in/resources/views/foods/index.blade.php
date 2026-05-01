@extends('layouts.app')

@section('title', 'Protein-In — Find the protein in any food')

@push('styles')
<style>
    body { display: flex; flex-direction: column; min-height: 100vh; }
    main { flex: 1; }

    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 5vh 1.25rem 3rem;
        text-align: center;
    }
    .hero img {
        width: min(280px, 55vw);
        height: auto;
        margin-bottom: 2rem;
    }
    .hero-search {
        width: 100%;
        max-width: 580px;
    }
    .hero-search form {
        display: flex;
        border: 2px solid #d6d3d1;
        border-radius: 50px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: box-shadow 0.15s, border-color 0.15s;
    }
    .hero-search form:focus-within {
        border-color: #b45309;
        box-shadow: 0 2px 18px rgba(180,83,9,0.15);
    }
    .hero-search input {
        flex: 1;
        padding: 0.85rem 1.5rem;
        border: none;
        font-size: 1.1rem;
        outline: none;
        background: transparent;
        min-width: 0;
    }
    .hero-search button {
        padding: 0.85rem 1.75rem;
        background: #b45309;
        color: #fff;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 0 48px 48px 0;
        white-space: nowrap;
    }
    .hero-search button:hover { background: #92400e; }
    .hero-tagline {
        margin-top: 1rem;
        color: #78716c;
        font-size: 0.95rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .top-foods { margin-top: 3rem; }
    .top-foods h2 { font-size: 1rem; color: #78716c; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 1rem; }
    .food-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; }
    .food-card { background: #fff; border: 1px solid #e7e5e4; border-radius: 8px; padding: 1rem; display: block; color: inherit; transition: border-color 0.1s, box-shadow 0.1s; }
    .food-card:hover { border-color: #b45309; box-shadow: 0 2px 8px rgba(180,83,9,0.1); text-decoration: none; }
    .food-card h3 { font-size: 0.95rem; margin-bottom: 0.2rem; }
    .food-card .protein { color: #b45309; font-weight: 700; font-size: 1.1rem; }
    .food-card .per { color: #a8a29e; font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="hero">
    <img src="/logo.png" alt="Protein-In — Calories are so last year">

    <div class="hero-search">
        <form action="/search" method="GET">
            <input type="text" name="q" placeholder="Chicken breast, lentils, greek yogurt…" autofocus>
            <button type="submit">Find protein</button>
        </form>
    </div>
    <p class="hero-tagline">Calories are so last year</p>
</div>

@if($recentlyViewed->count())
<div class="top-foods">
    <h2>Recently viewed</h2>
    <div class="food-grid">
        @foreach($recentlyViewed as $food)
        <a class="food-card" href="{{ route('foods.show', $food) }}">
            <h3>{{ $food->name }}</h3>
            <div class="protein">{{ $food->protein_per_100g }}g</div>
            <div class="per">protein per 100g</div>
        </a>
        @endforeach
    </div>
</div>
@endif

@if($popular->count())
<div class="top-foods">
    <h2>Most popular</h2>
    <div class="food-grid">
        @foreach($popular as $food)
        <a class="food-card" href="{{ route('foods.show', $food) }}">
            <h3>{{ $food->name }}</h3>
            <div class="protein">{{ $food->protein_per_100g }}g</div>
            <div class="per">protein per 100g</div>
        </a>
        @endforeach
    </div>
</div>
@endif

@if($foods->count())
<div class="top-foods">
    <h2>Highest protein foods</h2>
    <div class="food-grid">
        @foreach($foods as $food)
        <a class="food-card" href="{{ route('foods.show', $food) }}">
            <h3>{{ $food->name }}</h3>
            <div class="protein">{{ $food->protein_per_100g }}g</div>
            <div class="per">protein per 100g</div>
        </a>
        @endforeach
    </div>
    {{ $foods->links() }}
</div>
@endif
@endsection
