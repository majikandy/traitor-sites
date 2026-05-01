<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Protein-In')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f5f5f4; color: #1c1917; line-height: 1.6; display: flex; min-height: 100vh; }
        a { color: #b45309; text-decoration: none; }
        a:hover { text-decoration: underline; }
        nav { width: 200px; background: #1c1917; color: #fff; padding: 1.5rem 1rem; flex-shrink: 0; }
        nav h2 { color: #fbbf24; font-size: 1rem; margin-bottom: 1.5rem; }
        nav a { display: block; color: #d6d3d1; padding: 0.4rem 0.5rem; border-radius: 4px; font-size: 0.9rem; margin-bottom: 0.25rem; }
        nav a:hover { background: #292524; color: #fff; text-decoration: none; }
        nav a.active { background: #292524; color: #fbbf24; }
        main { flex: 1; padding: 2rem; max-width: 960px; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        h2 { font-size: 1.1rem; margin: 1.5rem 0 0.5rem; }
        .stat-card { background: #fff; border: 1px solid #e7e5e4; border-radius: 8px; padding: 1.25rem 1.5rem; min-width: 140px; }
        .stat-num { font-size: 2rem; font-weight: 700; color: #b45309; }
        .stat-label { color: #78716c; font-size: 0.875rem; }
        .admin-table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e7e5e4; border-radius: 8px; overflow: hidden; margin-top: 0.5rem; }
        .admin-table th { background: #f5f5f4; text-align: left; padding: 0.6rem 1rem; font-size: 0.8rem; color: #78716c; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e7e5e4; }
        .admin-table td { padding: 0.6rem 1rem; border-bottom: 1px solid #f5f5f4; font-size: 0.9rem; }
        .admin-table tr:last-child td { border-bottom: none; }
        .btn-import { background: #f5f0e8; color: #92400e; border: 1px solid #d6b895; padding: 0.3rem 0.75rem; border-radius: 4px; font-size: 0.8rem; cursor: pointer; }
        .btn-import:hover { background: #fef3c7; }
        .btn-primary { background: #b45309; color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 6px; font-size: 0.9rem; cursor: pointer; }
        .btn-primary:hover { background: #92400e; }
        .admin-input { padding: 0.5rem 0.75rem; border: 1px solid #d6d3d1; border-radius: 6px; font-size: 0.9rem; width: 250px; }
        .import-result { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 1rem; margin: 1rem 0; }
        .import-result pre { margin-top: 0.5rem; font-size: 0.8rem; white-space: pre-wrap; }
    </style>
</head>
<body>
<nav>
    <h2>Protein-In Admin</h2>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('admin.searches') }}" class="{{ request()->routeIs('admin.searches') ? 'active' : '' }}">Search Queries</a>
    <a href="{{ route('admin.migrations') }}" class="{{ request()->routeIs('admin.migrations') ? 'active' : '' }}">Migrations</a>
    <a href="{{ route('admin.nutrition-backfill') }}" class="{{ request()->routeIs('admin.nutrition-backfill') ? 'active' : '' }}">Nutrition Backfill</a>
    <a href="/" target="_blank" style="margin-top:2rem;color:#78716c;">View site &rarr;</a>
</nav>
<main>
    @yield('content')
</main>
</body>
</html>
