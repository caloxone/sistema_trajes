<?php // $talla, $accion, $titulo ?>

<div class="form-card">
    <h2><?= $titulo ?></h2><input type="number" min="1" required>
<input type="email" required>
<input type="text" pattern="[0-9]{5,15}">


    <p>Registra una nueva talla para asignarla a los trajes.</p>

    <form action="index.php?c=tallas&a=<?= $accion ?>" method="POST">
        <?php if (!empty($talla['id'])): ?>
            <input type="hidden" name="id" value="<?= $talla['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Código / nombre de la talla *</label>
            <input type="text" name="talla" required
                   value="<?= htmlspecialchars($talla['talla']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion"><?= htmlspecialchars($talla['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar</button>
            <a href="index.php?c=tallas&a=index" class="btn btn-secondary">↩ Volver</a>
        </div>
    </form>
</div>
