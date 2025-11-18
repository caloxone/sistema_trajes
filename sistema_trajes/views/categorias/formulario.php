<?php
// $categoria, $accion, $titulo
?>

<?php /* puedes reutilizar los estilos de formulario de clientes/formulario.php */ ?>

<div class="form-card">
    <h2><?= $titulo ?></h2>
    <p>Define una categoría para agrupar los diferentes tipos de trajes.</p>

    <form action="index.php?c=categorias&a=<?= $accion ?>" method="POST">
        <?php if (!empty($categoria['id'])): ?>
            <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nombre de la categoría *</label>
            <input type="text" name="nombre" required
                   value="<?= htmlspecialchars($categoria['nombre']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion"><?= htmlspecialchars($categoria['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar</button>
            <a href="index.php?c=categorias&a=index" class="btn btn-secondary">↩ Volver</a>
        </div>
    </form>
</div>
