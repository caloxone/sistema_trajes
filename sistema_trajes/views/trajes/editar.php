<?php
$traje = isset($traje) ? $traje : [];
$errores = isset($errores) ? $errores : [];
$categorias = isset($categorias) ? $categorias : [];
$telas = isset($telas) ? $telas : [];
$tallas = isset($tallas) ? $tallas : [];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2>Editar traje</h2>
            <p class="card-subtitle">Actualiza la información del traje seleccionado.</p>
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

    <form action="index.php?c=trajes&a=actualizar" method="POST">
        <input type="hidden" name="id" value="<?= $traje['id'] ?>">

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
                <input type="text" name="tipo" value="<?= htmlspecialchars($traje['tipo']) ?>">
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

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Actualizar</button>
            <a href="index.php?c=trajes&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
const ultimoTraje = <?= json_encode([
    'id' => $traje['id'],
    'codigo' => $traje['codigo'],
    'nombre' => $traje['nombre'],
    'precio' => $traje['precio_venta']
]) ?>;
localStorage.setItem('ultimoTrajeVisto', JSON.stringify(ultimoTraje));
</script>
