<div class="card">
    <div class="section-title">
        <div>
            <h2>Detalle de venta #<?= $venta['id'] ?></h2>
            <p class="card-subtitle">Resumen de la transacción y su detalle.</p>
        </div>
        <a href="index.php?c=ventas&a=index" class="btn btn-secondary">↩ Volver al listado</a>
    </div>

    <div class="info-grid" style="margin-bottom: 16px;">
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
            <?= htmlspecialchars($venta['observaciones']) ?: '—' ?>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
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

    <div class="panel" style="margin-top: 12px;">
        <strong>Total: <?= number_format($total, 2, '.', ',') ?> Bs.</strong>
    </div>
</div>
