<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/foods') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/blog') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    {{-- Food pages --}}
    @foreach($foods as $food)
    <url>
        <loc>{{ url('/foods/' . $food->slug) }}</loc>
        <lastmod>{{ $food->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- Blog posts --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ url('/' . $post->published_at->format('Y/m/d') . '/' . $post->slug) }}</loc>
        <lastmod>{{ $post->published_at->toAtomString() }}</lastmod>
        <changefreq>never</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- Category pages --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ url('/category/' . $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Tag pages --}}
    @foreach($tags as $tag)
    <url>
        <loc>{{ url('/tag/' . $tag->slug) }}</loc>
        <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

</urlset>
