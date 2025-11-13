$controlador = isset($_GET['c']) ? $_GET['c'] : 'dashboard';
<style>
@media print {
    .topbar,
    .btn,
    a[href]:after {
        display: none !important;
    }
    body {
        background: #ffffff !important;
    }
    .dash-container {
        margin: 0;
        max-width: 100%;
    }
}
</style>

<style>
    .dash-container {
        max-width: 1100px;
        margin: 10px auto 0;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .dash-title {
        font-size: 24px;
        margin: 0 0 4px;
    }
    .dash-subtitle {
        color:#6b7280;
        font-size: 13px;
        margin-bottom: 16px;
    }
    .dash-grid {
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap:12px;
        margin-bottom:18px;
    }
    .dash-card {
        background:#ffffff;
        border-radius:14px;
        padding:14px 16px;
        box-shadow:0 4px 12px rgba(0,0,0,0.06);
        position:relative;
        overflow:hidden;
    }
    .dash-card small {
        font-size:11px;
        color:#6b7280;
        text-transform:uppercase;
        letter-spacing:0.06em;
    }
    .dash-card h3 {
        margin:4px 0 0;
        font-size:20px;
    }
    .dash-card span.desc {
        display:block;
        font-size:12px;
        color:#9ca3af;
        margin-top:4px;
    }
    .dash-card.accent-blue::before,
    .dash-card.accent-green::before,
    .dash-card.accent-purple::before,
    .dash-card.accent-orange::before {
        content:"";
        position:absolute;
        right:-20px;
        top:-20px;
        width:70px;
        height:70px;
        border-radius:999px;
        opacity:0.16;
    }
    .dash-card.accent-blue::before { background:#3b82f6; }
    .dash-card.accent-green::before { background:#22c55e; }
    .dash-card.accent-purple::before { background:#a855f7; }
    .dash-card.accent-orange::before { background:#f97316; }

    .dash-row {
        display:grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1.2fr);
        gap:16px;
        margin-top:4px;
    }
    @media (max-width: 900px) {
        .dash-row {
            grid-template-columns: 1fr;
        }
    }

    .panel {
        background:#ffffff;
        border-radius:14px;
        padding:14px 16px;
        box-shadow:0 4px 12px rgba(0,0,0,0.06);
    }
    .panel h4 {
        margin:0 0 8px;
        font-size:16px;
    }
    .panel-sub {
        font-size:12px;
        color:#6b7280;
        margin:0 0 10px;
    }
    .tabla-ventas {
        width:100%;
        border-collapse:collapse;
        font-size:13px;
    }
    .tabla-ventas th, .tabla-ventas td {
        padding:6px 4px;
        border-bottom:1px solid #e5e7eb;
        white-space:nowrap;
    }
    .tabla-ventas thead {
        background:#f3f4f6;
    }
    .tabla-ventas tr:hover {
        background:#f9fafb;
    }
    .text-right { text-align:right; }
    .badge {
        display:inline-block;
        padding:2px 8px;
        border-radius:999px;
        font-size:11px;
        background:#e5e7eb;
        color:#374151;
    }
    .mas-vendido-box {
        font-size:13px;
        margin-top:4px;
        line-height:1.4;
    }
</style>

<div class="dash-container">
    <h1 class="dash-title">Dashboard general</h1>
    <p class="dash-subtitle">Resumen del sistema de venta e inventario de trajes.</p>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
    <p class="dash-subtitle">Resumen del sistema de venta e inventario de trajes.</p>
    <button class="btn btn-secondary" onclick="window.print()">🖨 Imprimir / Guardar PDF</button>
</div>

    <div class="dash-grid">
        <div class="dash-card accent-blue">
            <small>Trajes registrados</small>
            <h3><?= $totalTrajes ?></h3>
            <span class="desc">Modelos activos en el inventario.</span>
        </div>

        <div class="dash-card accent-green">
            <small>Clientes</small>
            <h3><?= $totalClientes ?></h3>
            <span class="desc">Personas registradas para ventas.</span>
        </div>

        <div class="dash-card accent-purple">
            <small>Ventas totales</small>
            <h3><?= $totalVentas ?></h3>
            <span class="desc">Comprobantes registrados en el sistema.</span>
        </div>

        <div class="dash-card accent-orange">
            <small>Ventas de hoy (Bs.)</small>
            <h3><?= number_format($totalHoy, 2, '.', ',') ?></h3>
            <span class="desc">Suma de ventas del día actual.</span>
        </div>
    </div>

    <div class="dash-row">
        <div class="panel">
            <h4>Últimas ventas</h4>
            <p class="panel-sub">Resumen de las 5 ventas más recientes.</p>

            <div style="overflow-x:auto;">
                <table class="tabla-ventas">
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
                                    <td>
                                        <span class="badge">
                                            <?= htmlspecialchars($v['cliente'] ?: 'Sin cliente') ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format($v['total'], 2, '.', ',') ?>
                                    </td>
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
<div class="dash-row" style="margin-top:18px;">
    <div class="panel">
        <h4>Ventas de los últimos días</h4>
        <p class="panel-sub">Monto total de ventas por día (hasta 7 días atrás).</p>
        <canvas id="chartVentasDias" height="120"></canvas>
    </div>

    <div class="panel">
        <h4>Top trajes más vendidos</h4>
        <p class="panel-sub">Unidades vendidas por modelo de traje.</p>
        <canvas id="chartTrajesTop" height="120"></canvas>
    </div>
</div>

        <div class="panel">
            <h4>Traje más vendido</h4>
            <p class="panel-sub">Modelo con mayor cantidad de unidades vendidas.</p>

            <?php if (!empty($masVendido)): ?>
                <div class="mas-vendido-box">
                    <div><strong><?= htmlspecialchars($masVendido['nombre']) ?></strong></div>
                    <div>Código: <span class="badge"><?= htmlspecialchars($masVendido['codigo']) ?></span></div>
                    <div>Cantidad vendida: <strong><?= $masVendido['total_cant'] ?></strong> unidades</div>
                </div>
            <?php else: ?>
                <div class="mas-vendido-box">
                    Aún no hay datos suficientes para calcular el traje más vendido.
                </div>
            <?php endif; ?>

            <div style="margin-top:12px; font-size:12px; color:#9ca3af;">
                Tip: este indicador se calcula a partir del detalle de ventas.
            </div>
        </div>
        
    </div>
<script>
    // Datos desde PHP a JS
    const labelsDias   = <?= json_encode($labelsDias) ?>;
    const datosDias    = <?= json_encode($datosDias) ?>;
    const labelsTrajes = <?= json_encode($labelsTrajes) ?>;
    const datosTrajes  = <?= json_encode($datosTrajes) ?>;

    // Gráfico de líneas: ventas por día
    const ctxDias = document.getElementById('chartVentasDias').getContext('2d');
    new Chart(ctxDias, {
        type: 'line',
        data: {
            labels: labelsDias,
            datasets: [{
                label: 'Ventas (Bs.)',
                data: datosDias,
                fill: false,
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gráfico de barras: top trajes
    const ctxTrajes = document.getElementById('chartTrajesTop').getContext('2d');
    new Chart(ctxTrajes, {
        type: 'bar',
        data: {
            labels: labelsTrajes,
            datasets: [{
                label: 'Unidades vendidas',
                data: datosTrajes,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
</script>

</div>
