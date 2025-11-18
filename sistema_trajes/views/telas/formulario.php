<?php
$tela = isset($tela) ? $tela : [
    'id' => '',
    'nombre' => '',
    'descripcion' => ''
];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2><?= $titulo ?></h2>
            <p class="card-subtitle">Registra un tipo de tela para asociarlo a los trajes.</p>
        </div>
        <a href="index.php?c=telas&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <form action="index.php?c=telas&a=<?= $accion ?>" method="POST">
        <?php if (!empty($tela['id'])): ?>
            <input type="hidden" name="id" value="<?= $tela['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nombre de la tela *</label>
            <input type="text" name="nombre" required value="<?= htmlspecialchars($tela['nombre']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3"><?= htmlspecialchars($tela['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="index.php?c=telas&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
