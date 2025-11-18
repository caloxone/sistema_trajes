# Export de archivos actualizados

Este documento recopila el contenido actual de los controladores y vistas que se renovaron. Puedes copiar cada bloque y pegarlo directamente en tu entorno.


## controllers/ClientesController.php

```php
<?php
require_once 'core/Controller.php';
require_once 'models/Cliente.php';

class ClientesController extends Controller {
    private function validarCliente($data) {
        $errores = [];

        $nombre = isset($data['nombre']) ? trim($data['nombre']) : '';
        $ciNit = isset($data['ci_nit']) ? trim($data['ci_nit']) : '';
        $telefono = isset($data['telefono']) ? trim($data['telefono']) : '';
        $correo = isset($data['correo']) ? trim($data['correo']) : '';

        if ($nombre === '') {
            $errores[] = "El nombre del cliente es obligatorio.";
        }
        if ($ciNit !== '' && !preg_match("/^[0-9]{5,15}$/", $ciNit)) {
            $errores[] = "El CI/NIT debe tener entre 5 y 15 dígitos.";
        }
        if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo no es válido.";
        }
        if ($telefono !== '' && !preg_match("/^[0-9]{7,15}$/", $telefono)) {
            $errores[] = "El teléfono debe ser numérico y válido.";
        }

        return $errores;
    }
    

    private $model;

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        $this->model = new Cliente();

    }

    // LISTA DE CLIENTES
    public function index() {
        $clientes = $this->model->getAll();
        $this->view('clientes/index', ['clientes' => $clientes]);
    }

    // FORMULARIO NUEVO CLIENTE
    public function crear() {
        $cliente = [
            'id'        => '',
            'nombre'    => '',
            'ci_nit'    => '',
            'telefono'  => '',
            'correo'    => '',
            'direccion' => ''
        ];
        $this->view('clientes/formulario', [
            'cliente' => $cliente,
            'accion'  => 'guardar',
            'titulo'  => 'Registrar cliente'
        ]);
    }

    // GUARDAR NUEVO CLIENTE
    public function guardar() {
        $errores = $this->validarCliente($_POST);
        if (!empty($errores)) {
            $this->view('clientes/formulario', [
                'cliente' => $_POST,
                'accion'  => 'guardar',
                'titulo'  => 'Registrar cliente',
                'errores' => $errores
            ]);
            return;
        }

        $this->model->create($_POST);
        header('Location: index.php?c=clientes&a=index');
    }

    // FORMULARIO EDITAR CLIENTE
    public function editar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: index.php?c=clientes&a=index');
            return;
        }

        $cliente = $this->model->getById($id);
        $this->view('clientes/formulario', [
            'cliente' => $cliente,
            'accion'  => 'actualizar',
            'titulo'  => 'Editar cliente'
        ]);
    }

    // ACTUALIZAR CLIENTE
    public function actualizar() {
        $errores = $this->validarCliente($_POST);
        if (!empty($errores)) {
            $this->view('clientes/formulario', [
                'cliente' => $_POST,
                'accion'  => 'actualizar',
                'titulo'  => 'Editar cliente',
                'errores' => $errores
            ]);
            return;
        }

        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header('Location: index.php?c=clientes&a=index');
    }

    // ELIMINAR CLIENTE
    public function eliminar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?c=clientes&a=index');
    }
}

```

## controllers/TrajesController.php

```php
<?php
require_once 'core/Controller.php';
require_once 'models/Traje.php';
require_once 'models/Categoria.php';
require_once 'models/Tela.php';
require_once 'models/Talla.php';

class TrajesController extends Controller {


    private $model;
    private $categoriaModel;
    private $telaModel;
    private $tallaModel;

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $this->model = new Traje();
        $this->categoriaModel = new Categoria();
        $this->telaModel = new Tela();
        $this->tallaModel = new Talla();

    }

    // LISTAR TRAJES
    public function index() {
        $trajes = $this->model->getAll();
        $this->view('trajes/index', ['trajes' => $trajes]);
    }

    private function validarDatosTraje($data) {
        $errores = [];

        $codigo = isset($data['codigo']) ? trim($data['codigo']) : '';
        $nombre = isset($data['nombre']) ? trim($data['nombre']) : '';
        $precio = isset($data['precio_venta']) ? (float) $data['precio_venta'] : 0;
        $stock = isset($data['stock']) ? (int) $data['stock'] : 0;
        $piezas = isset($data['numero_piezas']) ? (int) $data['numero_piezas'] : 0;
        $idCategoria = isset($data['id_categoria']) ? $data['id_categoria'] : '';
        $idTela = isset($data['id_tela']) ? $data['id_tela'] : '';
        $idTalla = isset($data['id_talla']) ? $data['id_talla'] : '';

        if ($codigo === '') {
            $errores[] = "El código del traje es obligatorio.";
        }
        if ($nombre === '') {
            $errores[] = "El nombre del traje es obligatorio.";
        }

        if ($precio <= 0) {
            $errores[] = "El precio debe ser mayor a 0.";
        }
        if ($stock < 0) {
            $errores[] = "El stock no puede ser negativo.";
        }
        if ($piezas <= 0) {
            $errores[] = "El número de piezas debe ser mayor a 0.";
        }

        if ($idCategoria !== '' && !$this->categoriaModel->getById($idCategoria)) {
            $errores[] = "La categoría seleccionada no existe.";
        }
        if ($idTela !== '' && !$this->telaModel->getById($idTela)) {
            $errores[] = "La tela seleccionada no existe.";
        }
        if ($idTalla !== '' && !$this->tallaModel->getById($idTalla)) {
            $errores[] = "La talla seleccionada no existe.";
        }

        return $errores;
    }

    private function obtenerReferencias() {
        return [
            'categorias' => $this->categoriaModel->getAll(),
            'telas'      => $this->telaModel->getAll(),
            'tallas'     => $this->tallaModel->getAll()
        ];
    }

    // FORMULARIO CREAR
    public function crear() {
        $this->view('trajes/crear', $this->obtenerReferencias());
    }

    // GUARDAR NUEVO TRAJE
    public function guardar() {
        $errores = $this->validarDatosTraje($_POST);
        if (!empty($errores)) {
            $data = array_merge($this->obtenerReferencias(), [
                'errores' => $errores,
                'traje'   => $_POST
            ]);
            $this->view('trajes/crear', $data);
            return;
        }

        $this->model->create($_POST);
        header("Location: index.php?c=trajes&a=index");
    }


    // FORMULARIO EDITAR
    public function editar() {
        $id = $_GET['id'];
        $traje = $this->model->getById($id);
        $data = array_merge($this->obtenerReferencias(), ['traje' => $traje]);
        $this->view('trajes/editar', $data);
    }

    // ACTUALIZAR
    public function actualizar() {
        $errores = $this->validarDatosTraje($_POST);
        if (!empty($errores)) {
            $data = array_merge($this->obtenerReferencias(), [
                'errores' => $errores,
                'traje'   => $_POST
            ]);
            $this->view('trajes/editar', $data);
            return;
        }

        $this->model->update($_POST['id'], $_POST);
        header("Location: index.php?c=trajes&a=index");
    }


    // ELIMINAR
    public function eliminar() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: index.php?c=trajes&a=index");

    }


}

```

## controllers/VentasController.php

```php
<?php
require_once 'core/Controller.php';
require_once 'models/Venta.php';
require_once 'models/Cliente.php';
require_once 'models/Traje.php';

class VentasController extends Controller {

    private $ventaModel;
    private $clienteModel;
    private $trajeModel;

    private function validarVenta($items) {
        $errores = [];

        if (count($items) === 0) {
            $errores[] = "Debe agregar al menos un traje a la venta.";
        }

        foreach ($items as $item) {
            if ($item['cantidad'] <= 0) {
                $errores[] = "La cantidad debe ser mayor a 0.";
            }
            if ($item['precio_unitario'] <= 0) {
                $errores[] = "El precio unitario debe ser mayor a 0.";
            }
        }

        return $errores;
    }

    private function renderFormularioVenta($data = []) {
        $clientes = $this->clienteModel->getAll();
        $trajes = $this->trajeModel->getAll();

        $this->view('ventas/crear', array_merge([
            'clientes' => $clientes,
            'trajes'   => $trajes,
        ], $data));
    }
public function exportar_excel() {
    $ventas = $this->ventaModel->getAll();

    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=ventas_export_" . date('Y-m-d') . ".csv");

    $salida = fopen("php://output", "w");

    // Cabeceras
    fputcsv($salida, ["ID", "Fecha", "Cliente", "Usuario", "Total"]);

    // Filas
    foreach ($ventas as $v) {
        fputcsv($salida, [
            $v['id'],
            $v['fecha'],
            $v['cliente'],
            $v['usuario'],
            $v['total']
        ]);
    }

    fclose($salida);
    exit;
}


    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        $this->ventaModel   = new Venta();
        $this->clienteModel = new Cliente();
        $this->trajeModel   = new Traje();
    }

    // LISTADO DE VENTAS
    public function index() {
        $ventas = $this->ventaModel->getAll();
        $this->view('ventas/index', ['ventas' => $ventas]);
    }

    // FORMULARIO PARA CREAR UNA NUEVA VENTA
    public function crear() {
        $this->renderFormularioVenta();
    }

    // GUARDAR LA VENTA
    public function guardar() {
        // -------------------------
        // Cabecera de la venta
        // -------------------------
        $idUsuario   = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1;
        $idCliente   = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : '';
        $observacion = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

        // -------------------------
        // Detalle: arrays en POST
        // -------------------------
        $idsTraje  = isset($_POST['id_traje']) ? $_POST['id_traje'] : [];
        $cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];
        $precios    = isset($_POST['precio_unitario']) ? $_POST['precio_unitario'] : [];

        $items = [];
        $total = 0;

        for ($i = 0; $i < count($idsTraje); $i++) {
            if ($idsTraje[$i] == '' || $cantidades[$i] <= 0 || $precios[$i] <= 0) {
                continue;
            }
            $subtotal = $cantidades[$i] * $precios[$i];
            $total += $subtotal;

            $items[] = [
                'id_traje'        => $idsTraje[$i],
                'cantidad'        => $cantidades[$i],
                'precio_unitario' => $precios[$i]
            ];
        }

        $errores = [];
        if (empty($idCliente)) {
            $errores[] = 'Selecciona un cliente para registrar la venta.';
        }

        $errores = array_merge($errores, $this->validarVenta($items));
        if (!empty($errores)) {
            $this->renderFormularioVenta([
                'errores' => $errores,
                'old' => [
                    'id_cliente'    => $idCliente,
                    'observaciones' => $observacion,
                    'items'         => $items
                ]
            ]);
            return;
        }

        $cabecera = [
            'id_usuario'    => $idUsuario,
            'id_cliente'    => $idCliente,
            'total'         => $total,
            'observaciones' => $observacion
        ];

        $idVenta = $this->ventaModel->crearVenta($cabecera, $items);

        if ($idVenta === false) {
            $this->renderFormularioVenta([
                'errores' => ['No se pudo guardar la venta. Inténtalo nuevamente.'],
                'old' => [
                    'id_cliente'    => $idCliente,
                    'observaciones' => $observacion,
                    'items'         => $items
                ]
            ]);
        } else {
            header('Location: index.php?c=ventas&a=ver&id=' . $idVenta);
        }
    }

    // VER UNA VENTA ESPECÍFICA
    public function ver() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: index.php?c=ventas&a=index');
            return;
        }

        $venta   = $this->ventaModel->getById($id);
        $detalle = $this->ventaModel->getDetalle($id);

        $this->view('ventas/ver', [
            'venta'   => $venta,
            'detalle' => $detalle
        ]);
    }
}

```

## views/clientes/formulario.php

```php
<?php
$cliente = isset($cliente) ? $cliente : [
    'id'        => '',
    'nombre'    => '',
    'ci_nit'    => '',
    'telefono'  => '',
    'correo'    => '',
    'direccion' => ''
];
$errores = isset($errores) ? $errores : [];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2><?= $titulo ?></h2>
            <p class="card-subtitle">Completa los datos del cliente para guardarlos en el sistema.</p>
        </div>
        <a href="index.php?c=clientes&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Corrige los siguientes campos:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=clientes&a=<?= $accion ?>" method="POST">
        <?php if (!empty($cliente['id'])): ?>
            <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label>Nombre completo *</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($cliente['nombre']) ?>">
            </div>

            <div class="form-group">
                <label>CI / NIT</label>
                <input type="text" name="ci_nit" pattern="[0-9]{5,15}"
                       placeholder="Solo números" value="<?= htmlspecialchars($cliente['ci_nit']) ?>">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" pattern="[0-9]{7,15}"
                       placeholder="Ej. 71234567" value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <textarea name="direccion" rows="3" placeholder="Barrio, calle, referencia"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="index.php?c=clientes&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

```

## views/clientes/index.php

```php
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

```

## views/dashboard/index.php

```php
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

```

## views/layout/header.php

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de trajes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            color: #0f172a;
        }

        .page-wrapper {
            padding: 24px clamp(12px, 4vw, 40px);
        }

        .topbar {
            background: #111827;
            color: #e5e7eb;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .topbar .logo {
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topbar a {
            color: inherit;
            text-decoration: none;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .topbar a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .topbar-user {
            font-size: 13px;
            margin-right: 8px;
            color: #cbd5f5;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            cursor: pointer;
            font-weight: 600;
            transition: opacity 0.2s, transform 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .btn-warning { background: #facc15; color: #713f12; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-success { background: #22c55e; color: #fff; }
        .btn-ghost { background: transparent; color: inherit; }
        .btn-small { padding: 4px 10px; font-size: 12px; border-radius: 6px; }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: clamp(16px, 3vw, 32px);
            box-shadow: 0 25px 50px -12px rgb(15 23 42 / 0.15);
            margin-bottom: 24px;
        }

        .card h3 {
            margin-top: 0;
            color: #0f172a;
        }

        .card-subtitle {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #cbd5f5;
            font-size: 14px;
            background: #f8fafc;
            resize: vertical;
        }

        .form-card {
            background: #fff;
            border-radius: 18px;
            padding: clamp(20px, 4vw, 36px);
            box-shadow: 0 20px 40px -15px rgb(15 23 42 / 0.12);
            max-width: 960px;
            margin: 0 auto 24px;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 16px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e2e8f0;
            color: #0f172a;
            font-size: 12px;
            font-weight: 600;
        }

        .acciones {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .acciones .btn {
            padding: 6px 12px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgb(15 23 42 / 0.15);
        }

        th, td {
            padding: 14px;
            text-align: left;
        }

        th {
            background: #0f172a;
            color: #e5e7eb;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        tr:hover {
            background: #dbeafe;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 600;
            font-size: 12px;
        }

        .text-right { text-align: right; }

        .section-title h2 {
            margin: 0;
            font-size: 1.4rem;
        }

        .stats-grid,
        .dashboard-panels {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .dashboard-panels {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 10px 30px -12px rgb(15 23 42 / 0.15);
            position: relative;
            overflow: hidden;
        }

        .stat-card small {
            display: block;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }

        .stat-card h3 {
            margin: 6px 0 0;
            font-size: 24px;
        }

        .panel {
            background: #fff;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 15px 30px -18px rgb(15 23 42 / 0.2);
            margin-bottom: 24px;
        }

        .panel h4 {
            margin: 0 0 6px;
            font-size: 18px;
        }

        .panel-sub {
            margin: 0 0 12px;
            font-size: 14px;
            color: #64748b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .info-item span {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-title h1 {
            margin: 0;
            font-size: clamp(1.5rem, 2vw, 2.2rem);
        }

        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            th, td {
                white-space: nowrap;
            }
        }

        /* Tema oscuro */
        .dark-mode {
            background: #0f172a;
            color: #f1f5f9;
        }

        .dark-mode .card,
        .dark-mode table {
            background: #1e293b;
            color: inherit;
        }

        .dark-mode th {
            background: #020617;
        }

        .dark-mode tr:nth-child(even) {
            background: #0f172a;
        }

        .dark-mode .card,
        .dark-mode .form-card,
        .dark-mode .stat-card,
        .dark-mode .panel,
        .dark-mode table {
            background: #1e293b;
            box-shadow: none;
            color: inherit;
        }

        .dark-mode th {
            background: #020617;
        }

        .dark-mode tr:nth-child(even) {
            background: #0f172a;
        }

        .dark-mode .tag,
        .dark-mode .badge {
            background: rgba(148, 163, 184, 0.2);
            color: #f8fafc;
        }

        .dark-mode .card-subtitle,
        .dark-mode .panel-sub,
        .dark-mode .info-item span {
            color: #cbd5f5;
        }

        .dark-mode .btn-secondary {
            background: #334155;
            color: #e2e8f0;
        }

        .dark-mode .alert-error {
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
            border-color: rgba(239, 68, 68, 0.35);
        }
    </style>
</head>
<body>
<div class="topbar">
    <div class="logo">👔 Sistema de trajes</div>
    <div class="topbar-nav">
        <button onclick="toggleTema()" class="btn" type="button">🌓 Tema</button>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <span class="topbar-user">
                <?= htmlspecialchars($_SESSION['nombre']) ?> (<?= htmlspecialchars($_SESSION['rol']) ?>)
            </span>
            <a href="index.php?c=dashboard&a=index">Dashboard</a>
            <a href="index.php?c=trajes&a=index">Trajes</a>
            <a href="index.php?c=clientes&a=index">Clientes</a>
            <a href="index.php?c=ventas&a=index">Ventas</a>
            <a href="index.php?c=categorias&a=index">Categorías</a>
            <a href="index.php?c=telas&a=index">Telas</a>
            <a href="index.php?c=tallas&a=index">Tallas</a>
            <a href="index.php?c=auth&a=logout">Salir</a>
        <?php endif; ?>
    </div>
</div>

<div class="page-wrapper">
<script>
function aplicarTema() {
    const tema = localStorage.getItem('temaSistema');
    if (tema === 'oscuro') {
        document.body.classList.add('dark-mode');
    }
}

function toggleTema() {
    const esOscuro = document.body.classList.toggle('dark-mode');
    localStorage.setItem('temaSistema', esOscuro ? 'oscuro' : 'claro');
}

aplicarTema();
</script>

```

## views/tallas/formulario.php

```php
<?php
$talla = isset($talla) ? $talla : [
    'id' => '',
    'talla' => '',
    'descripcion' => ''
];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2><?= $titulo ?></h2>
            <p class="card-subtitle">Registra una nueva talla para asignarla a los trajes.</p>
        </div>
        <a href="index.php?c=tallas&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <form action="index.php?c=tallas&a=<?= $accion ?>" method="POST">
        <?php if (!empty($talla['id'])): ?>
            <input type="hidden" name="id" value="<?= $talla['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Código / nombre de la talla *</label>
            <input type="text" name="talla" required value="<?= htmlspecialchars($talla['talla']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3"><?= htmlspecialchars($talla['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="index.php?c=tallas&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

```

## views/tallas/index.php

```php
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

```

## views/telas/formulario.php

```php
<?php
$tela = isset($tela) ? $tela : [
    'id' => '',
    'nombre' => '',
    'descripcion' => ''
];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2><?= $titulo ?></h2>
            <p class="card-subtitle">Registra un tipo de tela para asociarlo a los trajes.</p>
        </div>
        <a href="index.php?c=telas&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <form action="index.php?c=telas&a=<?= $accion ?>" method="POST">
        <?php if (!empty($tela['id'])): ?>
            <input type="hidden" name="id" value="<?= $tela['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nombre de la tela *</label>
            <input type="text" name="nombre" required value="<?= htmlspecialchars($tela['nombre']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3"><?= htmlspecialchars($tela['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="index.php?c=telas&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

```

## views/telas/index.php

```php
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

```

## views/categorias/index.php

```php
<div class="card">
    <div class="section-title">
        <div>
            <h1>Categorías de trajes</h1>
            <p class="card-subtitle">Agrupa los trajes por tipo de evento o estilo.</p>
        </div>
        <div class="acciones">
            <a href="index.php?c=categorias&a=crear" class="btn btn-primary">➕ Nueva categoría</a>
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
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><span class="badge"><?= htmlspecialchars($cat['nombre']) ?></span></td>
                    <td><?= htmlspecialchars($cat['descripcion']) ?></td>
                    <td class="acciones">
                        <a class="btn btn-warning" href="index.php?c=categorias&a=editar&id=<?= $cat['id'] ?>">✏ Editar</a>
                        <a class="btn btn-danger" href="index.php?c=categorias&a=eliminar&id=<?= $cat['id'] ?>"
                           onclick="return confirm('¿Eliminar esta categoría?');">🗑 Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

```

## views/categorias/formulario.php

```php
<?php
// $categoria, $accion, $titulo
?>

<?php /* puedes reutilizar los estilos de formulario de clientes/formulario.php */ ?>

<div class="form-card">
    <h2><?= $titulo ?></h2>
    <p>Define una categoría para agrupar los diferentes tipos de trajes.</p>

    <form action="index.php?c=categorias&a=<?= $accion ?>" method="POST">
        <?php if (!empty($categoria['id'])): ?>
            <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nombre de la categoría *</label>
            <input type="text" name="nombre" required
                   value="<?= htmlspecialchars($categoria['nombre']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion"><?= htmlspecialchars($categoria['descripcion']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar</button>
            <a href="index.php?c=categorias&a=index" class="btn btn-secondary">↩ Volver</a>
        </div>
    </form>
</div>

```

## views/trajes/crear.php

```php
<?php
if (!isset($traje)) {
    $traje = [
        'codigo'        => '',
        'nombre'        => '',
        'id_categoria'  => '',
        'id_tela'       => '',
        'id_talla'      => '',
        'numero_piezas' => 2,
        'color'         => '',
        'tipo'          => '',
        'precio_venta'  => '',
        'stock'         => ''
    ];
}
$errores = isset($errores) ? $errores : [];
$categorias = isset($categorias) ? $categorias : [];
$telas = isset($telas) ? $telas : [];
$tallas = isset($tallas) ? $tallas : [];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2>Registrar nuevo traje</h2>
            <p class="card-subtitle">Ingresa la información básica del traje y sus referencias.</p>
        </div>
        <a href="index.php?c=trajes&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Corrige los datos antes de guardar:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=trajes&a=guardar" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" required value="<?= htmlspecialchars($traje['codigo']) ?>">
            </div>
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($traje['nombre']) ?>">
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $traje['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($categorias)): ?>
                    <small class="card-subtitle">Registra una categoría primero.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Tela</label>
                <select name="id_tela">
                    <option value="">Sin tela</option>
                    <?php foreach ($telas as $tela): ?>
                        <option value="<?= $tela['id'] ?>" <?= $traje['id_tela'] == $tela['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tela['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($telas)): ?>
                    <small class="card-subtitle">Aún no hay telas registradas.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Talla</label>
                <select name="id_talla">
                    <option value="">Sin talla</option>
                    <?php foreach ($tallas as $talla): ?>
                        <option value="<?= $talla['id'] ?>" <?= $traje['id_talla'] == $talla['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($talla['talla']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($tallas)): ?>
                    <small class="card-subtitle">Registra tallas para asignarlas.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Número de piezas *</label>
                <input type="number" name="numero_piezas" min="1" required value="<?= htmlspecialchars($traje['numero_piezas']) ?>">
            </div>
            <div class="form-group">
                <label>Color</label>
                <input type="text" name="color" value="<?= htmlspecialchars($traje['color']) ?>">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo" placeholder="Ej. Clásico, Slim" value="<?= htmlspecialchars($traje['tipo']) ?>">
            </div>
            <div class="form-group">
                <label>Precio de venta (Bs.) *</label>
                <input type="number" name="precio_venta" min="0" step="0.01" required value="<?= htmlspecialchars($traje['precio_venta']) ?>">
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" min="0" required value="<?= htmlspecialchars($traje['stock']) ?>">
            </div>
        </div>

        <p class="card-subtitle">Selecciona los catálogos existentes; si lo dejas vacío, se guardará sin asignar.</p>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar traje</button>
            <a href="index.php?c=trajes&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

```

## views/trajes/editar.php

```php
<?php
$traje = isset($traje) ? $traje : [];
$errores = isset($errores) ? $errores : [];
$categorias = isset($categorias) ? $categorias : [];
$telas = isset($telas) ? $telas : [];
$tallas = isset($tallas) ? $tallas : [];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2>Editar traje</h2>
            <p class="card-subtitle">Actualiza la información del traje seleccionado.</p>
        </div>
        <a href="index.php?c=trajes&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Corrige los datos antes de guardar:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=trajes&a=actualizar" method="POST">
        <input type="hidden" name="id" value="<?= $traje['id'] ?>">

        <div class="form-grid">
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" required value="<?= htmlspecialchars($traje['codigo']) ?>">
            </div>
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($traje['nombre']) ?>">
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $traje['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tela</label>
                <select name="id_tela">
                    <option value="">Sin tela</option>
                    <?php foreach ($telas as $tela): ?>
                        <option value="<?= $tela['id'] ?>" <?= $traje['id_tela'] == $tela['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tela['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Talla</label>
                <select name="id_talla">
                    <option value="">Sin talla</option>
                    <?php foreach ($tallas as $talla): ?>
                        <option value="<?= $talla['id'] ?>" <?= $traje['id_talla'] == $talla['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($talla['talla']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Número de piezas *</label>
                <input type="number" name="numero_piezas" min="1" required value="<?= htmlspecialchars($traje['numero_piezas']) ?>">
            </div>
            <div class="form-group">
                <label>Color</label>
                <input type="text" name="color" value="<?= htmlspecialchars($traje['color']) ?>">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo" value="<?= htmlspecialchars($traje['tipo']) ?>">
            </div>
            <div class="form-group">
                <label>Precio de venta (Bs.) *</label>
                <input type="number" name="precio_venta" min="0" step="0.01" required value="<?= htmlspecialchars($traje['precio_venta']) ?>">
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" min="0" required value="<?= htmlspecialchars($traje['stock']) ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Actualizar</button>
            <a href="index.php?c=trajes&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
const ultimoTraje = <?= json_encode([
    'id' => $traje['id'],
    'codigo' => $traje['codigo'],
    'nombre' => $traje['nombre'],
    'precio' => $traje['precio_venta']
]) ?>;
localStorage.setItem('ultimoTrajeVisto', JSON.stringify(ultimoTraje));
</script>

```

## views/trajes/index.php

```php
<div class="section-title">
    <div>
        <h1>Inventario de trajes</h1>
        <p class="card-subtitle">Controla todos los modelos disponibles y su stock.</p>
    </div>
    <div class="acciones">
        <a class="btn btn-primary" href="index.php?c=trajes&a=crear">➕ Nuevo traje</a>
    </div>
</div>

<div id="avisoTraje"></div>

<div class="card">
    <div class="section-title" style="margin-bottom: 12px;">
        <h3>Filtros de búsqueda</h3>
        <button class="btn btn-secondary" type="button" onclick="guardarFiltros()">Guardar filtros</button>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label for="filtroCategoria">Categoría</label>
            <input type="text" id="filtroCategoria" placeholder="Ej. Smoking">
        </div>
        <div class="form-group">
            <label for="filtroTalla">Talla</label>
            <input type="text" id="filtroTalla" placeholder="M, L, XL">
        </div>
        <div class="form-group">
            <label for="filtroColor">Color</label>
            <input type="text" id="filtroColor" placeholder="Negro, azul…">
        </div>
        <div class="form-group">
            <label for="filtroPrecio">Precio máximo</label>
            <input type="number" id="filtroPrecio" placeholder="250">
        </div>
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Tela</th>
                <th>Talla</th>
                <th>Piezas</th>
                <th>Color</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($trajes)): ?>
                <?php foreach ($trajes as $t): ?>
                <tr class="fila-traje" data-traje='<?= json_encode(array(
                    "codigo" => $t['codigo'],
                    "nombre" => $t['nombre'],
                    "precio" => $t['precio_venta']
                ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'>
                    <td><span class="tag">#<?= htmlspecialchars($t['codigo']) ?></span></td>
                    <td><?= htmlspecialchars($t['nombre']) ?></td>
                    <td><?= htmlspecialchars($t['categoria']) ?></td>
                    <td><?= htmlspecialchars($t['tela']) ?></td>
                    <td><?= htmlspecialchars($t['talla']) ?></td>
                    <td><?= htmlspecialchars($t['numero_piezas']) ?></td>
                    <td><?= htmlspecialchars($t['color']) ?></td>
                    <td>Bs. <?= number_format($t['precio_venta'], 2) ?></td>
                    <td><?= htmlspecialchars($t['stock']) ?></td>
                    <td class="acciones">
                        <a class="btn btn-secondary btn-small" href="index.php?c=trajes&a=editar&id=<?= $t['id'] ?>">✏ Editar</a>
                        <a class="btn btn-danger btn-small" href="index.php?c=trajes&a=eliminar&id=<?= $t['id'] ?>"
                           onclick="return confirm('¿Eliminar este traje?');">🗑 Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No hay trajes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
const guardarFiltros = () => {
    const filtros = {
        categoria: document.getElementById('filtroCategoria').value,
        talla: document.getElementById('filtroTalla').value,
        color: document.getElementById('filtroColor').value,
        precio: document.getElementById('filtroPrecio').value
    };
    localStorage.setItem('filtrosTrajes', JSON.stringify(filtros));
    alert('Filtros guardados');
};

document.addEventListener('DOMContentLoaded', () => {
    const guardado = localStorage.getItem('filtrosTrajes');
    if (guardado) {
        const f = JSON.parse(guardado);
        document.getElementById('filtroCategoria').value = f.categoria || '';
        document.getElementById('filtroTalla').value = f.talla || '';
        document.getElementById('filtroColor').value = f.color || '';
        document.getElementById('filtroPrecio').value = f.precio || '';
    }

    const avisoTraje = document.getElementById('avisoTraje');
    const data = localStorage.getItem('ultimoTrajeVisto');
    if (data) {
        const t = JSON.parse(data);
        avisoTraje.innerHTML = `
            <div class="card" style="background:#dbeafe; color:#1e3a8a;">
                🔔 Último traje visto: <strong>${t.codigo}</strong> - ${t.nombre} (Bs. ${t.precio})
            </div>
        `;
    }

    document.querySelectorAll('.fila-traje').forEach((fila) => {
        fila.addEventListener('click', () => {
            localStorage.setItem('ultimoTrajeVisto', fila.dataset.traje);
        });
    });
});
</script>

```

## views/ventas/crear.php

```php
<?php
$errores = isset($errores) ? $errores : [];
$old = isset($old) ? $old : [
    'id_cliente'    => '',
    'observaciones' => '',
    'items'         => []
];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2>Registrar nueva venta</h2>
            <p class="card-subtitle">Selecciona el cliente y agrega los trajes vendidos.</p>
        </div>
        <a href="index.php?c=ventas&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Revisa los siguientes puntos:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=ventas&a=guardar" method="POST" id="formVenta">
        <div class="form-grid">
            <div class="form-group">
                <label>Cliente *</label>
                <select name="id_cliente" required>
                    <option value="">-- Seleccione un cliente --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $old['id_cliente'] == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?> (<?= htmlspecialchars($c['ci_nit']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" rows="2" placeholder="Ej: Venta al contado, sin descuentos..."><?= htmlspecialchars($old['observaciones']) ?></textarea>
            </div>
        </div>

        <div class="form-actions" style="justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="guardarCarrito()">💾 Guardar carrito temporal</button>
        </div>

        <h3>Detalle de la venta</h3>
        <p class="card-subtitle">Agrega uno o varios trajes. Puedes ajustar cantidades y precios.</p>

        <div class="table-wrapper">
            <table id="tablaDetalle">
                <thead>
                    <tr>
                        <th>Traje</th>
                        <th>Cantidad</th>
                        <th>Precio unitario (Bs.)</th>
                        <th>Subtotal (Bs.)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="agregarFila()">➕ Agregar traje</button>
        </div>

        <div class="panel" style="margin-top: 12px;">
            <strong>Total: <span id="totalTexto">0.00</span> Bs.</strong>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar venta</button>
            <a href="index.php?c=ventas&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
const KEY_CARRITO = 'carritoVenta';
const trajesData = <?= json_encode($trajes) ?>;
const itemsServer = <?= json_encode($old['items']) ?>;
let tbody;
let totalTexto;

function crearSelectTraje(index) {
    let html = '<select name="id_traje[' + index + ']" required>';
    html += '<option value="">-- Seleccione --</option>';
    trajesData.forEach(t => {
        const label = `${t.codigo} - ${t.nombre} (Stock: ${t.stock})`;
        html += `<option value="${t.id}" data-precio="${t.precio_venta}">${label}</option>`;
    });
    html += '</select>';
    return html;
}

function agregarFila(datos = null) {
    const index = tbody.children.length;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${crearSelectTraje(index)}</td>
        <td><input type="number" name="cantidad[${index}]" min="1" value="${datos ? datos.cantidad : 1}" oninput="recalcularFila(this)"></td>
        <td><input type="number" step="0.01" min="0" name="precio_unitario[${index}]" value="${datos ? datos.precio_unitario : 0}" oninput="recalcularFila(this)"></td>
        <td class="text-right"><span class="subtotal">0.00</span></td>
        <td><button type="button" class="btn btn-danger btn-small" onclick="eliminarFila(this)">🗑</button></td>
    `;

    tbody.appendChild(tr);

    const select = tr.querySelector('select');
    select.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        const precio = option.getAttribute('data-precio') || 0;
        const precioInput = tr.querySelector('input[name^="precio_unitario"]');
        precioInput.value = parseFloat(precio).toFixed(2);
        recalcularFila(precioInput);
    });

    if (datos) {
        select.value = datos.id_traje;
        recalcularFila(tr.querySelector('input[name^="cantidad"]'));
    } else if (select.options[1]) {
        select.selectedIndex = 0;
    }
}

function eliminarFila(btn) {
    const row = btn.closest('tr');
    row.remove();
    recalcularTotal();
}

function recalcularFila(input) {
    const row = input.closest('tr');
    const cant = parseFloat(row.querySelector('input[name^="cantidad"]').value) || 0;
    const precio = parseFloat(row.querySelector('input[name^="precio_unitario"]').value) || 0;
    const subtotal = cant * precio;
    row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
    recalcularTotal();
}

function recalcularTotal() {
    let total = 0;
    tbody.querySelectorAll('.subtotal').forEach(span => {
        total += parseFloat(span.textContent) || 0;
    });
    totalTexto.textContent = total.toFixed(2);
}

function guardarCarrito() {
    const datos = [];
    tbody.querySelectorAll('tr').forEach(row => {
        const select = row.querySelector('select');
        const cantidad = row.querySelector('input[name^="cantidad"]').value;
        const precio = row.querySelector('input[name^="precio_unitario"]').value;

        if (select.value && cantidad > 0 && precio > 0) {
            datos.push({
                id_traje: select.value,
                cantidad: cantidad,
                precio_unitario: precio
            });
        }
    });
    localStorage.setItem(KEY_CARRITO, JSON.stringify(datos));
    alert('Carrito guardado en este equipo.');
}

function cargarDesdeGuardado() {
    const guardado = localStorage.getItem(KEY_CARRITO);
    if (!guardado) {
        agregarFila();
        return;
    }
    JSON.parse(guardado).forEach(item => agregarFila(item));
}

document.addEventListener('DOMContentLoaded', () => {
    tbody = document.querySelector('#tablaDetalle tbody');
    totalTexto = document.getElementById('totalTexto');
    const formVenta = document.getElementById('formVenta');

    if (!tbody || !totalTexto) {
        return;
    }

    if (itemsServer.length) {
        itemsServer.forEach(item => agregarFila(item));
    } else {
        cargarDesdeGuardado();
    }

    tbody.querySelectorAll('input').forEach(input => recalcularFila(input));
    formVenta.addEventListener('submit', () => localStorage.removeItem(KEY_CARRITO));
});
</script>

```

## views/ventas/index.php

```php
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

```

## views/ventas/ver.php

```php
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

```
