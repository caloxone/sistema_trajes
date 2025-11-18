<div class="card">
    <div class="section-title">
        <div>
            <h1>Tallas</h1>
            <p class="card-subtitle">Administra las tallas disponibles para los trajes.</p>
        </div>
        <div class="acciones">
            <a href="index.php?c=tallas&a=crear" class="btn btn-primary">➕ Nueva talla</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Talla</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($tallas)): ?>
                <?php foreach ($tallas as $ta): ?>
                <tr>
                    <td><?= $ta['id'] ?></td>
                    <td><span class="badge"><?= htmlspecialchars($ta['talla']) ?></span></td>
                    <td><?= htmlspecialchars($ta['descripcion']) ?></td>
                    <td class="acciones">
                        <a class="btn btn-warning" href="index.php?c=tallas&a=editar&id=<?= $ta['id'] ?>">✏ Editar</a>
                        <a class="btn btn-danger" href="index.php?c=tallas&a=eliminar&id=<?= $ta['id'] ?>"
                           onclick="return confirm('¿Eliminar esta talla?');">🗑 Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay tallas registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
