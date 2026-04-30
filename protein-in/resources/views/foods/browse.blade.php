@extends('layouts.app')

@section('title', 'Browse all foods — Protein-In')

@section('content')
<h1>Browse all foods</h1>
<p class="meta">{{ $foods->total() }} foods, sorted A–Z</p>

<div class="food-grid">
    @foreach($foods as $food)
    <a class="food-card" href="{{ route('foods.show', $food) }}" style="display:block;color:inherit;">
        <h3>{{ $food->name }}</h3>
        <div class="protein">{{ $food->protein_per_100g }}g protein per 100g</div>
        @if($food->protein_per_serving)
        <div style="color:#78716c;font-size:0.8rem;">{{ $food->protein_per_serving }}g per {{ $food->serving_size }}</div>
        @endif
    </a>
    @endforeach
</div>

{{ $foods->links() }}
@endsection
