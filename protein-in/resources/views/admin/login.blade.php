<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Protein-In</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #1c1917; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border-radius: 10px; padding: 2.5rem 2rem; width: 100%; max-width: 340px; }
        h1 { font-size: 1.25rem; margin-bottom: 0.25rem; }
        p { color: #78716c; font-size: 0.875rem; margin-bottom: 1.5rem; }
        input { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d6d3d1; border-radius: 6px; font-size: 0.95rem; margin-bottom: 1rem; }
        button { width: 100%; padding: 0.65rem; background: #b45309; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 600; cursor: pointer; }
        button:hover { background: #92400e; }
        .error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.875rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="card">
    <h1>Protein-In Admin</h1>
    <p>Enter the admin password to continue.</p>
    @if($error)
    <div class="error">{{ $error }}</div>
    @endif
    <form method="POST">
        @csrf
        <input type="password" name="admin_password" placeholder="Password" autofocus>
        <button type="submit">Sign in</button>
    </form>
</div>
</body>
</html>
