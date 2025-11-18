<div class="card">
    <div class="section-title">
        <div>
            <h1>Dashboard general</h1>
            <p class="card-subtitle">Resumen del sistema de venta e inventario de trajes.</p>
        </div>
        <button class="btn btn-secondary" type="button" onclick="window.print()">🖨 Imprimir / Guardar PDF</button>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <small>Trajes registrados</small>
            <h3><?= $totalTrajes ?></h3>
            <p class="card-subtitle">Modelos activos en el inventario.</p>
        </div>
        <div class="stat-card">
            <small>Clientes</small>
            <h3><?= $totalClientes ?></h3>
            <p class="card-subtitle">Personas registradas para ventas.</p>
        </div>
        <div class="stat-card">
            <small>Ventas totales</small>
            <h3><?= $totalVentas ?></h3>
            <p class="card-subtitle">Comprobantes registrados.</p>
        </div>
        <div class="stat-card">
            <small>Ventas de hoy (Bs.)</small>
            <h3><?= number_format($totalHoy, 2, '.', ',') ?></h3>
            <p class="card-subtitle">Suma de ventas del día actual.</p>
        </div>
    </div>
</div>

<div class="panel">
    <h4>Últimas ventas</h4>
    <p class="panel-sub">Resumen de las 5 ventas más recientes.</p>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th class="text-right">Total (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ultimasVentas)): ?>
                    <?php foreach ($ultimasVentas as $v): ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><?= $v['fecha'] ?></td>
                            <td><span class="badge"><?= htmlspecialchars($v['cliente'] ?: 'Sin cliente') ?></span></td>
                            <td class="text-right"><?= number_format($v['total'], 2, '.', ',') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aún no hay ventas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="dashboard-panels">
    <div class="panel">
        <h4>Ventas de los últimos días</h4>
        <p class="panel-sub">Monto total de ventas por día (hasta 7 días atrás).</p>
        <canvas id="chartVentasDias" height="140"></canvas>
    </div>
    <div class="panel">
        <h4>Top trajes más vendidos</h4>
        <p class="panel-sub">Unidades vendidas por modelo.</p>
        <canvas id="chartTrajesTop" height="140"></canvas>
    </div>
</div>

<div class="panel">
    <h4>Traje más vendido</h4>
    <p class="panel-sub">Modelo con mayor cantidad de unidades vendidas.</p>
    <?php if (!empty($masVendido)): ?>
        <p>
            <strong><?= htmlspecialchars($masVendido['nombre']) ?></strong><br>
            Código: <span class="badge"><?= htmlspecialchars($masVendido['codigo']) ?></span><br>
            Cantidad vendida: <strong><?= $masVendido['total_cant'] ?></strong> unidades
        </p>
    <?php else: ?>
        <p class="card-subtitle">Aún no hay datos suficientes para calcular el traje más vendido.</p>
    <?php endif; ?>
</div>

<script>
const labelsDias   = <?= json_encode($labelsDias) ?>;
const datosDias    = <?= json_encode($datosDias) ?>;
const labelsTrajes = <?= json_encode($labelsTrajes) ?>;
const datosTrajes  = <?= json_encode($datosTrajes) ?>;

const ctxDias = document.getElementById('chartVentasDias');
if (ctxDias) {
    new Chart(ctxDias, {
        type: 'line',
        data: {
            labels: labelsDias,
            datasets: [{
                label: 'Ventas (Bs.)',
                data: datosDias,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.25)',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

const ctxTrajes = document.getElementById('chartTrajesTop');
if (ctxTrajes) {
    new Chart(ctxTrajes, {
        type: 'bar',
        data: {
            labels: labelsTrajes,
            datasets: [{
                label: 'Unidades vendidas',
                data: datosTrajes,
                backgroundColor: '#22c55e',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
}
</script>
