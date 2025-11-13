<?php
// Variables que llegan del controlador:
// $cliente  -> array con datos (vacío o con info)
// $accion   -> 'guardar' o 'actualizar'
// $titulo   -> texto del título
?>

<style>
    .form-card {
        max-width: 600px;
        margin: 20px auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .form-card h2 {
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 22px;
    }
    .form-card p {
        margin-top: 0;
        margin-bottom: 16px;
        color: #6b7280;
        font-size: 14px;
    }
    .form-group {
        margin-bottom: 12px;
    }
    .form-group label {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
        font-weight: 600;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        box-sizing: border-box;
    }
    .form-group textarea {
        resize: vertical;
        min-height: 60px;
    }
    .form-actions {
        margin-top: 16px;
        display: flex;
        gap: 8px;
    }
    .btn {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        transition: transform 0.1s ease, box-shadow 0.1s ease, background 0.15s ease;
    }
    .btn-primary {
        background: #22c55e;
        color: white;
    }
    .btn-primary:hover {
        background: #16a34a;
        box-shadow: 0 3px 8px rgba(34,197,94,0.4);
        transform: translateY(-1px);
    }
    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background: #d1d5db;
    }
</style>

<div class="form-card">
    <h2><?= $titulo ?></h2>
    <p>Completa los datos del cliente para guardarlos en el sistema.</p>

    <form action="index.php?c=clientes&a=<?= $accion ?>" method="POST">
        <?php if (!empty($cliente['id'])): ?>
            <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
        <?php endif; ?>
<input type="number" min="1" required>
<input type="email" required>
<input type="text" pattern="[0-9]{5,15}">

        <div class="form-group">
            <label>Nombre completo *</label>
            <input type="text" name="nombre" required
                   value="<?= htmlspecialchars($cliente['nombre']) ?>">
        </div>

        <div class="form-group">
            <label>CI / NIT</label>
            <input type="text" name="ci_nit"
                   value="<?= htmlspecialchars($cliente['ci_nit']) ?>">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono"
                   value="<?= htmlspecialchars($cliente['telefono']) ?>">
        </div>

        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="correo"
                   value="<?= htmlspecialchars($cliente['correo']) ?>">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <textarea name="direccion"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                💾 Guardar
            </button>
            <a href="index.php?c=clientes&a=index" class="btn btn-secondary">
                ↩ Volver
            </a>
        </div>
    </form>
</div>
