<?php
// Variables recibidas: $usuarioRecordado, $error
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Sistema de trajes</title>
    <style>
        body {
            margin:0;
            padding:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color:#f9fafb;
        }
        .login-card {
            width: 100%;
            max-width: 380px;
            background:#0b1120;
            padding:24px 24px 20px;
            border-radius:16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
            position:relative;
            overflow:hidden;
        }
        .login-card::before {
            content:"";
            position:absolute;
            inset:-40px;
            background: radial-gradient(circle at top left, rgba(96,165,250,0.25), transparent 55%);
            opacity:0.7;
            pointer-events:none;
        }
        .login-card h1 {
            margin:0 0 4px;
            font-size:22px;
        }
        .login-subtitle {
            margin:0 0 18px;
            font-size:13px;
            color:#9ca3af;
        }
        .form-group {
            margin-bottom:12px;
            position:relative;
            z-index:1;
        }
        .form-group label {
            display:block;
            margin-bottom:4px;
            font-size:13px;
            font-weight:500;
        }
        .form-group input {
            width:100%;
            padding:8px 10px;
            border-radius:10px;
            border:1px solid #1f2937;
            background:#020617;
            color:#e5e7eb;
            font-size:14px;
            box-sizing:border-box;
            outline:none;
        }
        .form-group input:focus {
            border-color:#3b82f6;
            box-shadow:0 0 0 1px rgba(59,130,246,0.6);
        }
        .form-row {
            display:flex;
            align-items:center;
            justify-content:space-between;
            font-size:13px;
            margin-top:4px;
        }
        .checkbox-label {
            display:flex;
            align-items:center;
            gap:6px;
            cursor:pointer;
        }
        .checkbox-label input[type="checkbox"] {
            width:14px;
            height:14px;
        }
        .error-msg {
            background: rgba(239,68,68,0.1);
            border:1px solid rgba(239,68,68,0.6);
            color:#fecaca;
            font-size:12px;
            padding:6px 8px;
            border-radius:8px;
            margin-bottom:10px;
        }
        .btn {
            width:100%;
            margin-top:12px;
            padding:9px 12px;
            border-radius:999px;
            border:none;
            font-size:14px;
            font-weight:500;
            cursor:pointer;
            background:linear-gradient(90deg,#22c55e,#16a34a);
            color:white;
            box-shadow:0 8px 18px rgba(34,197,94,0.4);
            transition:transform 0.1s ease, box-shadow 0.1s ease, filter 0.15s ease;
        }
        .btn:hover {
            filter:brightness(1.05);
            transform:translateY(-1px);
            box-shadow:0 10px 22px rgba(34,197,94,0.55);
        }
        .footer-text {
            text-align:center;
            margin-top:8px;
            font-size:11px;
            color:#6b7280;
            position:relative;
            z-index:1;
        }
    </style>
</head>
<body>
<div class="login-card">
    <h1>Acceso al sistema</h1>
    <p class="login-subtitle">Sistema de venta e inventario de trajes.</p>

    <?php if ($error): ?>
        <div class="error-msg">
            Usuario o contraseña incorrectos. Inténtalo de nuevo.
        </div>
    <?php endif; ?>

    <form action="index.php?c=auth&a=ingresar" method="POST">
        <div class="form-group">
            <label for="usuario">Usuario</label>
            <input
                type="text"
                id="usuario"
                name="usuario"
                value="<?= htmlspecialchars($usuarioRecordado) ?>"
                required
                autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password">
        </div>

        <div class="form-row">
            <label class="checkbox-label">
                <input type="checkbox" name="recordar"
                    <?= $usuarioRecordado ? 'checked' : '' ?>>
                <span>Recordar usuario</span>
            </label>
            <span style="color:#6b7280;">Admin: admin / 123456</span>
        </div>

        <button type="submit" class="btn">
            Ingresar
        </button>
    </form>

    <div class="footer-text">
        Proyecto MVC · PHP & MySQL · XAMPP
    </div>
</div>
</body>
</html>
