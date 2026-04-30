<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Protein-In — Find the protein in any food')</title>
    <meta name="description" content="@yield('description', 'Search any food and find out how much protein it contains. The protein-first nutrition database.')">
    <link rel="icon" type="image/png" href="/favicon-32x32.png">
    <link rel="sitemap" type="application/xml" href="/sitemap.xml">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #fafaf9; color: #1c1917; line-height: 1.6; }
        a { color: #b45309; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 900px; margin: 0 auto; padding: 0 1.25rem; }

        /* Compact header — only shown on non-homepage */
        header { background: #1c1917; color: #fff; padding: 0.75rem 0; }
        header .wrap { display: flex; align-items: center; gap: 1rem; }
        header .logo { display: flex; align-items: center; gap: 0.6rem; text-decoration: none; }
        header .logo img { height: 32px; width: auto; }
        header .logo span { color: #fbbf24; font-weight: 800; font-size: 1.1rem; letter-spacing: -0.02em; }
        header nav { display: flex; gap: 1.25rem; margin-left: auto; }
        header nav a { color: #d6d3d1; font-size: 0.875rem; }
        .header-search { flex: 1; max-width: 360px; margin-left: 1.5rem; }
        .header-search form { display: flex; }
        .header-search input { flex: 1; padding: 0.4rem 0.75rem; border-radius: 6px 0 0 6px; border: none; font-size: 0.9rem; min-width: 0; }
        .header-search button { padding: 0.4rem 0.9rem; background: #b45309; color: #fff; border: none; border-radius: 0 6px 6px 0; font-size: 0.875rem; cursor: pointer; white-space: nowrap; }

        main { padding: 2rem 0; }
        footer { border-top: 1px solid #e7e5e4; padding: 1.5rem 0; text-align: center; color: #78716c; font-size: 0.875rem; margin-top: 3rem; }
        .pill { display: inline-block; background: #fef3c7; color: #92400e; border-radius: 4px; padding: 0.15rem 0.5rem; font-size: 0.8rem; margin: 0.15rem; }
        .protein-badge { font-size: 1.5rem; font-weight: 700; color: #b45309; }
        .food-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; margin-top: 1.5rem; }
        .food-card { background: #fff; border: 1px solid #e7e5e4; border-radius: 8px; padding: 1rem; }
        .food-card h3 { font-size: 1rem; margin-bottom: 0.25rem; }
        .food-card .protein { color: #b45309; font-weight: 700; }
        h1 { font-size: 1.75rem; margin-bottom: 0.5rem; }
        h2 { font-size: 1.25rem; margin: 1.5rem 0 0.75rem; }
        .meta { color: #78716c; font-size: 0.875rem; margin-bottom: 1rem; }
    </style>
    @stack('styles')
</head>
<body>
@unless(request()->is('/'))
<header>
    <div class="wrap">
        <a class="logo" href="/">
            <img src="/logo.png" alt="Protein-In">
        </a>
        <div class="header-search">
            <form action="/search" method="GET">
                <input type="text" name="q" placeholder="Search a food…" value="{{ request('q') }}">
                <button type="submit">Search</button>
            </form>
        </div>
        <nav>
            <a href="/foods">Browse</a>
            <a href="/blog">Blog</a>
        </nav>
    </div>
</header>
@endunless
<main>
    <div class="wrap">
        @yield('content')
    </div>
</main>
<footer>
    <div class="wrap">
        &copy; {{ date('Y') }} Protein-In &mdash; Calories are so last year
    </div>
</footer>
</body>
</html>
