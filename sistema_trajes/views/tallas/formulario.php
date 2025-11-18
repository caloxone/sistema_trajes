<?php
$talla = isset($talla) ? $talla : [
    'id' => '',
    'talla' => '',
    'descripcion' => ''
];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2><?= $titulo ?></h2>
            <p class="card-subtitle">Registra una nueva talla para asignarla a los trajes.</p>
        </div>
        <a href="index.php?c=tallas&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <form action="index.php?c=tallas&a=<?= $accion ?>" method="POST">
        <?php if (!empty($talla['id'])): ?>
            <input type="hidden" name="id" value="<?= $talla['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Código / nombre de la talla *</label>
            <input type="text" name="talla" required value="<?= htmlspecialchars($talla['talla']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3"><?= htmlspecialchars($talla['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="index.php?c=tallas&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
