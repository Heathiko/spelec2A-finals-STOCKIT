<?php /** @var string $content */
/** @var string $title */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($title ?? 'Stockit Inventory')) ?></title>
    <!-- <link rel="stylesheet" href="/css/styles.css"> -->
    <style>
        :root {
            --bg: #0f1419;
            --panel: #1a2332;
            --accent: #3d9cf5;
            --accent-2: #22c55e;
            --text: #e8eef7;
            --muted: #94a3b8;
            --danger: #f87171;
            --border: #2d3a4f;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, sans-serif;
            background: linear-gradient(160deg, #0b1020 0%, #121a2b 55%, #0f1419 100%);
            color: var(--text);
            min-height: 100vh;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            background: rgba(15, 20, 25, 0.85);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        header h1 {
            margin: 0;
            font-size: 1.15rem;
            letter-spacing: 0.02em;
        }
        nav a {
            color: var(--muted);
            text-decoration: none;
            margin-left: 1rem;
            font-weight: 600;
        }
        nav a:hover, nav a.active { color: var(--accent); }
        main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
        }
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        th { color: var(--muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .btn {
            display: inline-block;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            border: none;
            background: var(--accent);
            color: #04101f;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-secondary { background: #334155; color: var(--text); }
        .btn-danger { background: var(--danger); color: #1f0a0a; }
        .errors {
            background: rgba(248, 113, 113, 0.12);
            border: 1px solid var(--danger);
            color: #fecaca;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        label { display: block; margin-bottom: 0.35rem; color: var(--muted); font-size: 0.9rem; }
        input, select, textarea {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.65rem 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #0f172a;
            color: var(--text);
        }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-low { background: rgba(248, 113, 113, 0.2); color: #fecaca; }
        .badge-medium { background: rgba(250, 204, 21, 0.2); color: #fde68a; }
        .badge-healthy { background: rgba(34, 197, 94, 0.2); color: #bbf7d0; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat {
            background: #111b2a;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem;
        }
        .stat strong { display: block; font-size: 1.4rem; margin-top: 0.35rem; }
    </style>
</head>
<body>
<header>
    <h1>Stockit Inventory</h1>
    <nav>
        <a href="/products">Products</a>
        <a href="/categories">Categories</a>
        <a href="/stock">Stock Report</a>
    </nav>
</header>
<main>
    <?= $content ?>
</main>
</body>
</html>
