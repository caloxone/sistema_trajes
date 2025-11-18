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
