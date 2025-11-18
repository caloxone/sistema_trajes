<?php
$cliente = isset($cliente) ? $cliente : [
    'id'        => '',
    'nombre'    => '',
    'ci_nit'    => '',
    'telefono'  => '',
    'correo'    => '',
    'direccion' => ''
];
$errores = isset($errores) ? $errores : [];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2><?= $titulo ?></h2>
            <p class="card-subtitle">Completa los datos del cliente para guardarlos en el sistema.</p>
        </div>
        <a href="index.php?c=clientes&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Corrige los siguientes campos:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=clientes&a=<?= $accion ?>" method="POST">
        <?php if (!empty($cliente['id'])): ?>
            <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label>Nombre completo *</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($cliente['nombre']) ?>">
            </div>

            <div class="form-group">
                <label>CI / NIT</label>
                <input type="text" name="ci_nit" pattern="[0-9]{5,15}"
                       placeholder="Solo números" value="<?= htmlspecialchars($cliente['ci_nit']) ?>">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" pattern="[0-9]{7,15}"
                       placeholder="Ej. 71234567" value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <textarea name="direccion" rows="3" placeholder="Barrio, calle, referencia"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="index.php?c=clientes&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
