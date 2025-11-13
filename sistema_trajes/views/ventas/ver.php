<style>
    .card {
        max-width: 900px;
        margin: 20px auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .card h2 { margin:0 0 8px; font-size:22px; }
    .info-grid {
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
        gap:10px;
        margin:10px 0 16px;
        font-size:14px;
    }
    .info-item span {
        display:block; color:#6b7280; font-size:12px;
    }
    .table-wrapper { overflow-x:auto; }
    table.detalle { width:100%; border-collapse:collapse; font-size:14px; }
    table.detalle th, table.detalle td {
        padding:8px 6px; border-bottom:1px solid #e5e7eb; white-space:nowrap;
    }
    table.detalle thead { background:#f3f4f6; }
    .text-right { text-align:right; }
    .total-final {
        text-align:right; margin-top:10px; font-size:16px; font-weight:bold;
    }
    .btn { display:inline-block; padding:8px 14px; border-radius:8px; border:none; text-decoration:none; font-size:14px; cursor:pointer; }
    .btn-secondary { background:#e5e7eb; color:#374151; }
    .btn-secondary:hover { background:#d1d5db; }
</style>

<div class="card">
    <h2>Detalle de venta #<?= $venta['id'] ?></h2>

    <div class="info-grid">
        <div class="info-item">
            <span>Fecha</span>
            <?= $venta['fecha'] ?>
        </div>
        <div class="info-item">
            <span>Cliente</span>
            <?= htmlspecialchars($venta['cliente']) ?> (<?= htmlspecialchars($venta['ci_nit']) ?>)
        </div>
        <div class="info-item">
            <span>Atendido por</span>
            <?= htmlspecialchars($venta['usuario']) ?>
        </div>
        <div class="info-item">
            <span>Observaciones</span>
            <?= htmlspecialchars($venta['observaciones']) ?>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="detalle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Traje</th>
                    <th>Cantidad</th>
                    <th>Precio unitario (Bs.)</th>
                    <th>Subtotal (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($detalle as $d): ?>
                    <?php $total += $d['subtotal']; ?>
                    <tr>
                        <td><?= htmlspecialchars($d['codigo']) ?></td>
                        <td><?= htmlspecialchars($d['traje']) ?></td>
                        <td class="text-right"><?= $d['cantidad'] ?></td>
                        <td class="text-right"><?= number_format($d['precio_unitario'], 2, '.', ',') ?></td>
                        <td class="text-right"><?= number_format($d['subtotal'], 2, '.', ',') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="total-final">
        Total: <?= number_format($total, 2, '.', ',') ?> Bs.
    </div>

    <div style="margin-top:12px;">
        <a href="index.php?c=ventas&a=index" class="btn btn-secondary">↩ Volver al listado</a>
    </div>
</div>
