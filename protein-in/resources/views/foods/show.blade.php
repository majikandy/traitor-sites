@extends('layouts.app')

@section('title', $food->name . ' — Protein Content — Protein-In')
@section('description', 'How much protein is in ' . $food->name . '? ' . $food->protein_per_100g . 'g per 100g.')

@section('content')
<div style="display:flex;gap:2rem;align-items:flex-start;flex-wrap:wrap;margin-bottom:1.5rem;">
    <div id="food-image-wrap" style="width:120px;height:120px;flex-shrink:0;{{ $food->image_url ? '' : 'display:none;' }}">
        <img id="food-image" src="{{ $food->image_url ?? '' }}" alt="{{ $food->name }}" style="width:120px;height:120px;object-fit:contain;border-radius:8px;border:1px solid #e7e5e4;background:#fff;">
    </div>
    <div>
        <h1 style="margin-bottom:0.5rem;">{{ $food->name }}</h1>
        @if($food->description)
        <p style="color:#78716c;font-size:0.9rem;">{{ $food->description }}</p>
        @endif
    </div>
</div>

<table style="border-collapse:collapse;margin-bottom:1.5rem;width:100%;max-width:360px;">
    <thead>
        <tr style="border-bottom:2px solid #e7e5e4;">
            <th style="text-align:left;padding:0.4rem 1rem 0.4rem 0;color:#78716c;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;">Nutrient</th>
            <th style="text-align:right;padding:0.4rem 0;color:#78716c;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;">Per 100g</th>
            @if($food->protein_per_serving)<th style="text-align:right;padding:0.4rem 0 0.4rem 1rem;color:#78716c;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;">Per {{ $food->serving_size }}</th>@endif
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #f5f5f4;background:#fef3c7;">
            <td style="padding:0.5rem 1rem 0.5rem 0;font-weight:700;">Protein</td>
            <td style="text-align:right;font-weight:700;color:#b45309;">{{ $food->protein_per_100g }}g</td>
            @if($food->protein_per_serving)<td style="text-align:right;padding-left:1rem;color:#b45309;font-weight:700;">{{ $food->protein_per_serving }}g</td>@endif
        </tr>
        @if($food->calories_per_100g)
        <tr style="border-bottom:1px solid #f5f5f4;">
            <td style="padding:0.5rem 1rem 0.5rem 0;">Calories</td>
            <td style="text-align:right;">{{ $food->calories_per_100g }} kcal</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
        @if($food->fat_per_100g)
        <tr style="border-bottom:1px solid #f5f5f4;">
            <td style="padding:0.5rem 1rem 0.5rem 0;">Fat</td>
            <td style="text-align:right;">{{ $food->fat_per_100g }}g</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
        @if(isset($food->saturated_fat_per_100g) && $food->saturated_fat_per_100g)
        <tr style="border-bottom:1px solid #f5f5f4;">
            <td style="padding:0.5rem 1rem 0.5rem 1rem;color:#78716c;font-size:0.9rem;">of which saturates</td>
            <td style="text-align:right;color:#78716c;font-size:0.9rem;">{{ $food->saturated_fat_per_100g }}g</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
        @if($food->carbs_per_100g)
        <tr style="border-bottom:1px solid #f5f5f4;">
            <td style="padding:0.5rem 1rem 0.5rem 0;">Carbohydrates</td>
            <td style="text-align:right;">{{ $food->carbs_per_100g }}g</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
        @if(isset($food->sugar_per_100g) && $food->sugar_per_100g)
        <tr style="border-bottom:1px solid #f5f5f4;">
            <td style="padding:0.5rem 1rem 0.5rem 1rem;color:#78716c;font-size:0.9rem;">of which sugars</td>
            <td style="text-align:right;color:#78716c;font-size:0.9rem;">{{ $food->sugar_per_100g }}g</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
        @if($food->fibre_per_100g)
        <tr style="border-bottom:1px solid #f5f5f4;">
            <td style="padding:0.5rem 1rem 0.5rem 0;">Fibre</td>
            <td style="text-align:right;">{{ $food->fibre_per_100g }}g</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
        @if(isset($food->salt_per_100g) && $food->salt_per_100g)
        <tr>
            <td style="padding:0.5rem 1rem 0.5rem 0;">Salt</td>
            <td style="text-align:right;">{{ $food->salt_per_100g }}g</td>
            @if($food->protein_per_serving)<td></td>@endif
        </tr>
        @endif
    </tbody>
</table>

@if($food->categories->count())
<div style="margin-bottom:0.75rem;">
    @foreach($food->categories as $category)
    <a class="pill" href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
    @endforeach
</div>
@endif

@if($food->tags->count())
<div>
    @foreach($food->tags as $tag)
    <a class="pill" href="{{ route('tag.show', $tag) }}" style="background:#f0fdf4;color:#166534;">{{ $tag->name }}</a>
    @endforeach
</div>
@endif

@if($food->image_url === null)
<script>
fetch('{{ route('foods.image', $food) }}')
    .then(r => r.json())
    .then(data => {
        if (data.image_url) {
            const img = document.getElementById('food-image');
            const wrap = document.getElementById('food-image-wrap');
            img.src = data.image_url;
            wrap.style.display = '';
        }
    })
    .catch(() => {});
</script>
@endif
@endsection
