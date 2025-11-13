<style>
    .card {
        max-width: 1100px;
        margin: 20px auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .card h1 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 24px;
    }
    .card-subtitle {
        color: #666;
        font-size: 14px;
        margin-bottom: 16px;
    }
    .btn {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        transition: transform 0.1s ease, box-shadow 0.1s ease, background 0.15s ease;
    }
    .btn-primary {
        background: #2563eb;
        color: #fff;
    }
    .btn-primary:hover {
        background: #1d4ed8;
        box-shadow: 0 3px 8px rgba(37,99,235,0.35);
        transform: translateY(-1px);
    }
    .btn-warning {
        background: #facc15;
        color: #854d0e;
    }
    .btn-warning:hover {
        background: #eab308;
    }
    .btn-danger {
        background: #ef4444;
        color: #fff;
    }
    .btn-danger:hover {
        background: #dc2626;
    }
    .table-wrapper {
        overflow-x: auto;
        margin-top: 16px;
    }
    table.clientes {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    table.clientes thead {
        background: #f3f4f6;
    }
    table.clientes th,
    table.clientes td {
        padding: 10px 8px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    table.clientes tr:hover {
        background: #f9fafb;
    }
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 12px;
        background: #e5e7eb;
        color: #374151;
    }
    .acciones a {
        margin-right: 4px;
        margin-bottom: 4px;
    }
</style>

<div class="card">
    <h1>Clientes</h1>
    <div class="card-subtitle">
        Gestión de clientes del sistema de trajes. Aquí puedes registrar, editar y eliminar clientes.
    </div>

    <a href="index.php?c=clientes&a=crear" class="btn btn-primary">➕ Nuevo cliente</a>

    <div class="table-wrapper">
        <table class="clientes">
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
                        <a class="btn btn-warning"
                           href="index.php?c=clientes&a=editar&id=<?= $c['id'] ?>">
                            ✏ Editar
                        </a>
                        <a class="btn btn-danger"
                           href="index.php?c=clientes&a=eliminar&id=<?= $c['id'] ?>"
                           onclick="return confirm('¿Seguro de eliminar este cliente?');">
                            🗑 Eliminar
                        </a>
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
