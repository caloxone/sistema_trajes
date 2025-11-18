<?php
if (!isset($traje)) {
    $traje = [
        'codigo'        => '',
        'nombre'        => '',
        'id_categoria'  => '',
        'id_tela'       => '',
        'id_talla'      => '',
        'numero_piezas' => 2,
        'color'         => '',
        'tipo'          => '',
        'precio_venta'  => '',
        'stock'         => ''
    ];
}
$errores = isset($errores) ? $errores : [];
$categorias = isset($categorias) ? $categorias : [];
$telas = isset($telas) ? $telas : [];
$tallas = isset($tallas) ? $tallas : [];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2>Registrar nuevo traje</h2>
            <p class="card-subtitle">Ingresa la información básica del traje y sus referencias.</p>
        </div>
        <a href="index.php?c=trajes&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Corrige los datos antes de guardar:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=trajes&a=guardar" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" required value="<?= htmlspecialchars($traje['codigo']) ?>">
            </div>
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($traje['nombre']) ?>">
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $traje['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($categorias)): ?>
                    <small class="card-subtitle">Registra una categoría primero.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Tela</label>
                <select name="id_tela">
                    <option value="">Sin tela</option>
                    <?php foreach ($telas as $tela): ?>
                        <option value="<?= $tela['id'] ?>" <?= $traje['id_tela'] == $tela['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tela['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($telas)): ?>
                    <small class="card-subtitle">Aún no hay telas registradas.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Talla</label>
                <select name="id_talla">
                    <option value="">Sin talla</option>
                    <?php foreach ($tallas as $talla): ?>
                        <option value="<?= $talla['id'] ?>" <?= $traje['id_talla'] == $talla['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($talla['talla']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($tallas)): ?>
                    <small class="card-subtitle">Registra tallas para asignarlas.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Número de piezas *</label>
                <input type="number" name="numero_piezas" min="1" required value="<?= htmlspecialchars($traje['numero_piezas']) ?>">
            </div>
            <div class="form-group">
                <label>Color</label>
                <input type="text" name="color" value="<?= htmlspecialchars($traje['color']) ?>">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo" placeholder="Ej. Clásico, Slim" value="<?= htmlspecialchars($traje['tipo']) ?>">
            </div>
            <div class="form-group">
                <label>Precio de venta (Bs.) *</label>
                <input type="number" name="precio_venta" min="0" step="0.01" required value="<?= htmlspecialchars($traje['precio_venta']) ?>">
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" min="0" required value="<?= htmlspecialchars($traje['stock']) ?>">
            </div>
        </div>

        <p class="card-subtitle">Selecciona los catálogos existentes; si lo dejas vacío, se guardará sin asignar.</p>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar traje</button>
            <a href="index.php?c=trajes&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
