<?php
require_once 'core/Controller.php';
require_once 'core/Database.php';

class DashboardController extends Controller {

    private $pdo;
public function reporte() {
    // Puedes reutilizar las mismas consultas de index()
    // y cargar una vista más simple sin gráficos.
    $this->index(); // o crear consultas aparte y otra vista
}

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        $this->pdo = Database::getInstance();
    }

    public function index() {
    // Total de trajes
    $totalTrajes = $this->pdo->query("SELECT COUNT(*) FROM trajes")->fetchColumn();

    // Total de clientes
    $totalClientes = $this->pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();

    // Total de ventas
    $totalVentas = $this->pdo->query("SELECT COUNT(*) FROM ventas")->fetchColumn();

    // Total de ventas de hoy (monto)
    $sqlHoy = "SELECT IFNULL(SUM(total), 0) FROM ventas WHERE DATE(fecha) = CURDATE()";
    $totalHoy = $this->pdo->query($sqlHoy)->fetchColumn();

    // Traje más vendido
    $sqlMasVendido = "
        SELECT t.nombre, t.codigo, SUM(d.cantidad) AS total_cant
        FROM detalles_venta d
        INNER JOIN trajes t ON d.id_traje = t.id
        GROUP BY d.id_traje
        ORDER BY total_cant DESC
        LIMIT 1
    ";
    $masVendido = $this->pdo->query($sqlMasVendido)->fetch(PDO::FETCH_ASSOC);

    // Últimas 5 ventas
    $sqlUltimas = "
        SELECT v.id, v.fecha, v.total, c.nombre AS cliente
        FROM ventas v
        LEFT JOIN clientes c ON v.id_cliente = c.id
        ORDER BY v.fecha DESC
        LIMIT 5
    ";
    $ultimasVentas = $this->pdo->query($sqlUltimas)->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Datos para gráfico: ventas por día (últimos 7 días)
    $sqlGraficoDias = "
        SELECT DATE(fecha) AS dia, SUM(total) AS total_dia
        FROM ventas
        WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(fecha)
        ORDER BY dia
    ";
    $rows = $this->pdo->query($sqlGraficoDias)->fetchAll(PDO::FETCH_ASSOC);

    $labelsDias = [];
    $datosDias = [];
    foreach ($rows as $r) {
        $labelsDias[] = $r['dia'];
        $datosDias[]  = (float)$r['total_dia'];
    }

    // 🔹 Datos para gráfico: top 5 trajes más vendidos
    $sqlGraficoTrajes = "
        SELECT t.nombre, SUM(d.cantidad) AS total_cant
        FROM detalles_venta d
        INNER JOIN trajes t ON d.id_traje = t.id
        GROUP BY d.id_traje
        ORDER BY total_cant DESC
        LIMIT 5
    ";
    $rowsT = $this->pdo->query($sqlGraficoTrajes)->fetchAll(PDO::FETCH_ASSOC);

    $labelsTrajes = [];
    $datosTrajes  = [];
    foreach ($rowsT as $r) {
        $labelsTrajes[] = $r['nombre'];
        $datosTrajes[]  = (int)$r['total_cant'];
    }

    $this->view('dashboard/index', [
        'totalTrajes'    => $totalTrajes,
        'totalClientes'  => $totalClientes,
        'totalVentas'    => $totalVentas,
        'totalHoy'       => $totalHoy,
        'masVendido'     => $masVendido,
        'ultimasVentas'  => $ultimasVentas,
        'labelsDias'     => $labelsDias,
        'datosDias'      => $datosDias,
        'labelsTrajes'   => $labelsTrajes,
        'datosTrajes'    => $datosTrajes
    ]);
}
}
