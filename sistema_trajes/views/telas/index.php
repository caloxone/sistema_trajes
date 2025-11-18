<div class="card">
    <div class="section-title">
        <div>
            <h1>Tipos de tela</h1>
            <p class="card-subtitle">Catálogo de telas disponibles para los trajes.</p>
        </div>
        <div class="acciones">
            <a href="index.php?c=telas&a=crear" class="btn btn-primary">➕ Nueva tela</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($telas)): ?>
                <?php foreach ($telas as $te): ?>
                <tr>
                    <td><?= $te['id'] ?></td>
                    <td><span class="badge"><?= htmlspecialchars($te['nombre']) ?></span></td>
                    <td><?= htmlspecialchars($te['descripcion']) ?></td>
                    <td class="acciones">
                        <a class="btn btn-warning" href="index.php?c=telas&a=editar&id=<?= $te['id'] ?>">✏ Editar</a>
                        <a class="btn btn-danger" href="index.php?c=telas&a=eliminar&id=<?= $te['id'] ?>"
                           onclick="return confirm('¿Eliminar este tipo de tela?');">🗑 Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay tipos de tela registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
