<style>
    /* Puedes reutilizar los mismos estilos de card, btn, table que ya usamos */
    .card {
        max-width: 1100px;
        margin: 20px auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .card h1 { margin: 0 0 6px; font-size: 24px; }
    .card-subtitle { color:#6b7280; font-size:14px; margin-bottom: 10px; }
    .btn {
        display:inline-block; padding:8px 14px; border-radius:8px;
        border:none; text-decoration:none; font-size:14px; cursor:pointer;
    }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-secondary { background:#e5e7eb; color:#374151; }
    .btn-secondary:hover { background:#d1d5db; }
    .table-wrapper { margin-top:12px; overflow-x:auto; }
    table.lista { width:100%; border-collapse:collapse; font-size:14px; }
    table.lista th, table.lista td {
        padding:8px 6px; border-bottom:1px solid #e5e7eb; white-space:nowrap;
    }
    table.lista thead { background:#f3f4f6; }
    table.lista tr:hover { background:#f9fafb; }
    .badge { padding:2px 8px; border-radius:999px; background:#e5e7eb; font-size:12px; }
</style>

<div class="card">
    <h1>Ventas</h1>
    <div class="card-subtitle">
        Registro de ventas realizadas en el sistema de trajes.
    </div>

    <a href="index.php?c=ventas&a=crear" class="btn btn-primary">➕ Registrar nueva venta</a>

    <div class="table-wrapper">
        <table class="lista">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Total (Bs.)</th>
                    <th>Observaciones</th>
                    <th>Ver</th>
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
                            <td>
                            <a href="index.php?c=ventas&a=exportar_excel" class="btn btn-primary">⬇ Exportar a Excel</a>
   
                            <a class="btn btn-secondary"
                                   href="index.php?c=ventas&a=ver&id=<?= $v['id'] ?>">
                                    🔍 Ver
                                </a>
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
