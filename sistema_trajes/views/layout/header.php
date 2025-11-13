<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<head>
    <meta charset="UTF-8">
    <title>Sistema de trajes</title>
    <style>
        <style>
/* Contenedor base */
body {
    margin: 0;
    padding: 0;
}

/* Que el contenido no se pegue a los bordes en pantallas pequeñas */
@media (max-width: 768px) {
    .card,
    .form-card,
    .panel,
    .dash-container {
        padding: 12px !important;
        margin: 8px !important;
        box-shadow: none !important;
        border-radius: 8px !important;
    }

    table {
        font-size: 12px !important;
    }

    .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }

    .topbar a {
        display: inline-block;
        margin: 2px 4px 0 0;
        font-size: 12px;
    }
}

/* Tablas con scroll horizontal en móviles (ya lo usas pero lo reforzamos) */
.table-wrapper {
    overflow-x: auto;
}
</style>

        .topbar {
            background:#111827;
            color:#e5e7eb;
            padding:10px 16px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .topbar .logo {
            font-weight:600;
            letter-spacing:0.03em;
        }
        .topbar a {
            color:#e5e7eb;
            margin-left:10px;
            text-decoration:none;
            font-size:14px;
        }
        .topbar a:hover {
            text-decoration:underline;
        }
        .topbar-user {
            font-size:13px;
        }
    </style>
</head>
<body>
<div class="topbar">
    <div class="logo">👔 Sistema de trajes</div>
    <button onclick="toggleTema()" class="btn" style="margin-left:10px;">
    🌓 Tema
</button>
<style>
.dark-mode {
    background:#0f172a !important;
    color:#f1f5f9 !important;
}
.dark-mode table {
    color:#f1f5f9 !important;
}
.dark-mode .card {
    background:#1e293b !important;
}
</style>
<script>
function aplicarTema() {
    const tema = localStorage.getItem("temaSistema");
    if (tema === "oscuro") {
        document.body.classList.add("dark-mode");
    }
}

function toggleTema() {
    const esOscuro = document.body.classList.toggle("dark-mode");
    localStorage.setItem("temaSistema", esOscuro ? "oscuro" : "claro");
}

aplicarTema();
</script>

    <div>
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

<div style="padding:16px;">
