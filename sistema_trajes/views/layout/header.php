<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de trajes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            color: #0f172a;
        }

        .page-wrapper {
            padding: 24px clamp(12px, 4vw, 40px);
        }

        .topbar {
            background: #111827;
            color: #e5e7eb;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .topbar .logo {
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topbar a {
            color: inherit;
            text-decoration: none;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .topbar a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .topbar-user {
            font-size: 13px;
            margin-right: 8px;
            color: #cbd5f5;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            cursor: pointer;
            font-weight: 600;
            transition: opacity 0.2s, transform 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .btn-warning { background: #facc15; color: #713f12; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-success { background: #22c55e; color: #fff; }
        .btn-ghost { background: transparent; color: inherit; }
        .btn-small { padding: 4px 10px; font-size: 12px; border-radius: 6px; }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: clamp(16px, 3vw, 32px);
            box-shadow: 0 25px 50px -12px rgb(15 23 42 / 0.15);
            margin-bottom: 24px;
        }

        .card h3 {
            margin-top: 0;
            color: #0f172a;
        }

        .card-subtitle {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #cbd5f5;
            font-size: 14px;
            background: #f8fafc;
            resize: vertical;
        }

        .form-card {
            background: #fff;
            border-radius: 18px;
            padding: clamp(20px, 4vw, 36px);
            box-shadow: 0 20px 40px -15px rgb(15 23 42 / 0.12);
            max-width: 960px;
            margin: 0 auto 24px;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 16px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e2e8f0;
            color: #0f172a;
            font-size: 12px;
            font-weight: 600;
        }

        .acciones {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .acciones .btn {
            padding: 6px 12px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgb(15 23 42 / 0.15);
        }

        th, td {
            padding: 14px;
            text-align: left;
        }

        th {
            background: #0f172a;
            color: #e5e7eb;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        tr:hover {
            background: #dbeafe;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 600;
            font-size: 12px;
        }

        .text-right { text-align: right; }

        .section-title h2 {
            margin: 0;
            font-size: 1.4rem;
        }

        .stats-grid,
        .dashboard-panels {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .dashboard-panels {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 10px 30px -12px rgb(15 23 42 / 0.15);
            position: relative;
            overflow: hidden;
        }

        .stat-card small {
            display: block;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }

        .stat-card h3 {
            margin: 6px 0 0;
            font-size: 24px;
        }

        .panel {
            background: #fff;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 15px 30px -18px rgb(15 23 42 / 0.2);
            margin-bottom: 24px;
        }

        .panel h4 {
            margin: 0 0 6px;
            font-size: 18px;
        }

        .panel-sub {
            margin: 0 0 12px;
            font-size: 14px;
            color: #64748b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .info-item span {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-title h1 {
            margin: 0;
            font-size: clamp(1.5rem, 2vw, 2.2rem);
        }

        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            th, td {
                white-space: nowrap;
            }
        }

        /* Tema oscuro */
        .dark-mode {
            background: #0f172a;
            color: #f1f5f9;
        }

        .dark-mode .card,
        .dark-mode table {
            background: #1e293b;
            color: inherit;
        }

        .dark-mode th {
            background: #020617;
        }

        .dark-mode tr:nth-child(even) {
            background: #0f172a;
        }

        .dark-mode .card,
        .dark-mode .form-card,
        .dark-mode .stat-card,
        .dark-mode .panel,
        .dark-mode table {
            background: #1e293b;
            box-shadow: none;
            color: inherit;
        }

        .dark-mode th {
            background: #020617;
        }

        .dark-mode tr:nth-child(even) {
            background: #0f172a;
        }

        .dark-mode .tag,
        .dark-mode .badge {
            background: rgba(148, 163, 184, 0.2);
            color: #f8fafc;
        }

        .dark-mode .card-subtitle,
        .dark-mode .panel-sub,
        .dark-mode .info-item span {
            color: #cbd5f5;
        }

        .dark-mode .btn-secondary {
            background: #334155;
            color: #e2e8f0;
        }

        .dark-mode .alert-error {
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
            border-color: rgba(239, 68, 68, 0.35);
        }
    </style>
</head>
<body>
<div class="topbar">
    <div class="logo">👔 Sistema de trajes</div>
    <div class="topbar-nav">
        <button onclick="toggleTema()" class="btn" type="button">🌓 Tema</button>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <span class="topbar-user">
                <?= htmlspecialchars($_SESSION['nombre']) ?> (<?= htmlspecialchars($_SESSION['rol']) ?>)
            </span>
            <a href="index.php?c=dashboard&a=index">Dashboard</a>
            <a href="index.php?c=trajes&a=index">Trajes</a>
            <a href="index.php?c=clientes&a=index">Clientes</a>
            <a href="index.php?c=ventas&a=index">Ventas</a>
            <a href="index.php?c=categorias&a=index">Categorías</a>
            <a href="index.php?c=telas&a=index">Telas</a>
            <a href="index.php?c=tallas&a=index">Tallas</a>
            <a href="index.php?c=auth&a=logout">Salir</a>
        <?php endif; ?>
    </div>
</div>

<div class="page-wrapper">
<script>
function aplicarTema() {
    const tema = localStorage.getItem('temaSistema');
    if (tema === 'oscuro') {
        document.body.classList.add('dark-mode');
    }
}

function toggleTema() {
    const esOscuro = document.body.classList.toggle('dark-mode');
    localStorage.setItem('temaSistema', esOscuro ? 'oscuro' : 'claro');
}

aplicarTema();
</script>
