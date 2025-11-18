<div class="card">
    <div class="section-title">
        <div>
            <h1>Clientes</h1>
            <p class="card-subtitle">Gestión de clientes registrados para las ventas y reservas.</p>
        </div>
        <div class="acciones">
            <a href="index.php?c=clientes&a=crear" class="btn btn-primary">➕ Nuevo cliente</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>CI / NIT</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><span class="badge"><?= htmlspecialchars($c['nombre']) ?></span></td>
                        <td><?= htmlspecialchars($c['ci_nit']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= htmlspecialchars($c['correo']) ?></td>
                        <td><?= htmlspecialchars($c['direccion']) ?></td>
                        <td class="acciones">
                            <a class="btn btn-warning" href="index.php?c=clientes&a=editar&id=<?= $c['id'] ?>">✏ Editar</a>
                            <a class="btn btn-danger" href="index.php?c=clientes&a=eliminar&id=<?= $c['id'] ?>"
                               onclick="return confirm('¿Seguro de eliminar este cliente?');">🗑 Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay clientes registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
