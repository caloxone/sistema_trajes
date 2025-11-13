<?php /* Puedes copiar los mismos <style> de clientes/index.php */ ?>

<div class="card">
    <h1>Categorías de trajes</h1>
    <div class="card-subtitle">
        Administración de las categorías para clasificar los trajes (boda, fiesta, oficina, etc.).
    </div>

    <a href="index.php?c=categorias&a=crear" class="btn btn-primary">➕ Nueva categoría</a>

    <div class="table-wrapper">
        <table class="clientes">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><span class="badge"><?= htmlspecialchars($cat['nombre']) ?></span></td>
                    <td><?= htmlspecialchars($cat['descripcion']) ?></td>
                    <td class="acciones">
                        <a class="btn btn-warning"
                           href="index.php?c=categorias&a=editar&id=<?= $cat['id'] ?>">
                            ✏ Editar
                        </a>
                        <a class="btn btn-danger"
                           href="index.php?c=categorias&a=eliminar&id=<?= $cat['id'] ?>"
                           onclick="return confirm('¿Eliminar esta categoría?');">
                            🗑 Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

