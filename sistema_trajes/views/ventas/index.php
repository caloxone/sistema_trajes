<div class="card">
    <div class="section-title">
        <div>
            <h1>Ventas</h1>
            <p class="card-subtitle">Registro de ventas realizadas en el sistema de trajes.</p>
        </div>
        <div class="acciones">
            <a href="index.php?c=ventas&a=crear" class="btn btn-primary">➕ Registrar venta</a>
            <a href="index.php?c=ventas&a=exportar_excel" class="btn btn-secondary">⬇ Exportar CSV</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Total (Bs.)</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ventas)): ?>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><?= $v['fecha'] ?></td>
                            <td><span class="badge"><?= htmlspecialchars($v['cliente']) ?></span></td>
                            <td><?= htmlspecialchars($v['usuario']) ?></td>
                            <td><?= number_format($v['total'], 2, '.', ',') ?></td>
                            <td><?= htmlspecialchars($v['observaciones']) ?></td>
                            <td class="acciones">
                                <a class="btn btn-secondary btn-small" href="index.php?c=ventas&a=ver&id=<?= $v['id'] ?>">🔍 Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay ventas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
