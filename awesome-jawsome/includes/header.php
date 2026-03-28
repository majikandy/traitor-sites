<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$depth = substr_count(trim($_SERVER['SCRIPT_NAME'], '/'), '/') - 1;
$root = $depth > 0 ? str_repeat('../', $depth) : './';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?>Awesome Jawsome</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --deep:    #0a1628;
            --ocean:   #0d3b6e;
            --wave:    #1565c0;
            --foam:    #e3f2fd;
            --accent:  #ff6f00;
            --danger:  #d32f2f;
            --text:    #eceff1;
            --muted:   #90a4ae;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--deep);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---- NAV ---- */
        header {
            background: linear-gradient(135deg, var(--deep) 0%, var(--ocean) 100%);
            border-bottom: 3px solid var(--accent);
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.5);
        }
        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .logo {
            font-size: 1.6rem;
            font-weight: 900;
            text-decoration: none;
            color: var(--text);
            letter-spacing: -1px;
        }
        .logo span { color: var(--accent); }
        nav a {
            color: var(--muted);
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        nav a:hover, nav a.active { color: var(--accent); }

        /* ---- HERO / PAGE HEADER ---- */
        .hero {
            background: linear-gradient(180deg, var(--ocean) 0%, var(--deep) 100%);
            padding: 4rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '🦈';
            position: absolute;
            font-size: 12rem;
            opacity: 0.05;
            top: -2rem;
            right: -2rem;
            pointer-events: none;
        }
        .hero h1 { font-size: 2.8rem; font-weight: 900; margin-bottom: 0.5rem; }
        .hero h1 span { color: var(--accent); }
        .hero p { color: var(--muted); font-size: 1.15rem; max-width: 600px; margin: 0 auto; }

        /* ---- MAIN ---- */
        main {
            flex: 1;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            padding: 2.5rem 2rem;
        }

        /* ---- CARDS ---- */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .card {
            background: linear-gradient(135deg, #0d2240 0%, #0a1628 100%);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(21,101,192,0.3);
        }
        .card .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-great-white  { background: var(--danger); color: #fff; }
        .badge-hammerhead   { background: #6a1b9a; color: #fff; }
        .badge-tiger        { background: #e65100; color: #fff; }
        .badge-bull         { background: #1b5e20; color: #fff; }
        .badge-whale        { background: var(--wave); color: #fff; }
        .badge-other        { background: var(--muted); color: var(--deep); }
        .card h3 { font-size: 1.1rem; margin-bottom: 0.4rem; }
        .card .meta { color: var(--muted); font-size: 0.85rem; margin-bottom: 0.6rem; }
        .card p { font-size: 0.93rem; line-height: 1.6; color: #b0bec5; }
        .card .read-more {
            display: inline-block;
            margin-top: 1rem;
            color: var(--accent);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
        }

        /* ---- SECTION TITLES ---- */
        .section-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        .section-title span { color: var(--accent); }
        .section-sub { color: var(--muted); margin-bottom: 1.5rem; font-size: 0.95rem; }

        /* ---- BUTTONS ---- */
        .btn {
            display: inline-block;
            padding: 0.65rem 1.4rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn:hover { opacity: 0.85; transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-outline { background: transparent; color: var(--accent); border: 2px solid var(--accent); }

        /* ---- FORMS ---- */
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.9rem; color: var(--muted); margin-bottom: 0.4rem; font-weight: 600; }
        input[type=text], input[type=email], textarea, select {
            width: 100%;
            padding: 0.65rem 0.9rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
        }
        select option { background: var(--ocean); }
        textarea { resize: vertical; min-height: 110px; }

        /* ---- ALERTS ---- */
        .alert { padding: 1rem 1.2rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 500; }
        .alert-success { background: rgba(76,175,80,0.15); border: 1px solid #4caf50; color: #a5d6a7; }
        .alert-error   { background: rgba(211,47,47,0.15);  border: 1px solid #d32f2f; color: #ef9a9a; }

        /* ---- FOOTER ---- */
        footer {
            background: #060d1a;
            border-top: 1px solid rgba(255,255,255,0.06);
            text-align: center;
            padding: 1.5rem;
            color: var(--muted);
            font-size: 0.85rem;
        }
        footer span { color: var(--accent); }

        /* ---- DIVIDER ---- */
        .divider { border: none; border-top: 1px solid rgba(255,255,255,0.07); margin: 2rem 0; }
    </style>
</head>
<body>
<header>
    <div class="nav-inner">
        <a class="logo" href="/">Awesome <span>Jawsome</span></a>
        <nav>
            <a href="/">Home</a>
            <a href="/sightings/">Sightings</a>
            <a href="/sightings/submit">Report</a>
            <a href="/gallery/">Gallery</a>
            <a href="/about/">About</a>
        </nav>
    </div>
</header>
