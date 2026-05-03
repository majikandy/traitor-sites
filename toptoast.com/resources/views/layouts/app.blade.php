<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') — {{ config('toptoast.site_name') }}@else{{ config('toptoast.site_name') }} — {{ config('toptoast.tagline') }}@endif</title>
    <meta name="description" content="@hasSection('meta_description')@yield('meta_description')@else{{ config('toptoast.meta_description') }}@endif">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    @stack('styles')
</head>
<body class="@yield('body_class')">

<header class="site-header">
    <div class="container">
        <a href="/" class="site-logo">
            <span class="logo-icon">🍞</span>
            <span class="logo-text">{{ config('toptoast.site_name') }}</span>
        </a>

        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>

        <nav>
            <ul class="nav-list" id="nav-list">
                @foreach(config('toptoast.nav') as $url => $label)
                    <li>
                        <a href="{{ $url }}" class="{{ request()->is(ltrim($url, '/') ?: '/') || ($url !== '/' && request()->is(ltrim($url, '/') . '*')) ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>

<main class="site-main">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="/" class="site-logo">
                    <span class="logo-icon">🍞</span>
                    <span class="logo-text">{{ config('toptoast.site_name') }}</span>
                </a>
                <p>{{ config('toptoast.tagline') }}<br>Recipes, toppings, and the definitive toast price index.</p>
            </div>
            <div class="footer-links">
                <h4>Recipes</h4>
                <ul>
                    @foreach(config('toptoast.categories') as $slug => $label)
                        <li><a href="/recipes?cat={{ $slug }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-links">
                <h4>Site</h4>
                <ul>
                    @foreach(config('toptoast.nav') as $url => $label)
                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('toptoast.site_name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    const navToggle = document.querySelector('.nav-toggle');
    const navList = document.getElementById('nav-list');

    navToggle.addEventListener('click', function () {
        const isOpen = navList.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        navToggle.textContent = isOpen ? '✕' : '☰';
    });
</script>

@stack('scripts')
</body>
</html>
