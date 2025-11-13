<?php // $tela, $accion, $titulo ?>

<div class="form-card">
    <h2><?= $titulo ?></h2>
    <input type="number" min="1" required>
<input type="email" required>
<input type="text" pattern="[0-9]{5,15}">

    <p>Registra un tipo de tela para asociarlo a los trajes.</p>
<input type="number" min="1" required>
<input type="email" required>
<input type="text" pattern="[0-9]{5,15}">

    <form action="index.php?c=telas&a=<?= $accion ?>" method="POST">
        <?php if (!empty($tela['id'])): ?>
            <input type="hidden" name="id" value="<?= $tela['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nombre de la tela *</label>
            <input type="text" name="nombre" required
                   value="<?= htmlspecialchars($tela['nombre']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion"><?= htmlspecialchars($tela['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar</button>
            <a href="index.php?c=telas&a=index" class="btn btn-secondary">↩ Volver</a>
        </div>
    </form>
</div>
