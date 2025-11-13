<?php
require_once 'core/Controller.php';
require_once 'models/Traje.php';

class TrajesController extends Controller {
    

    private $model;

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        
        $this->model = new Traje();
    
    }

    // LISTAR TRAJES
    public function index() {
        $trajes = $this->model->getAll();
        $this->view('trajes/index', ['trajes' => $trajes]);
    }

    private function validarDatosTraje($data) {
    $errores = [];

    if (trim($data['codigo']) === "") {
        $errores[] = "El código del traje es obligatorio.";
    }
    if (trim($data['nombre']) === "") {
        $errores[] = "El nombre del traje es obligatorio.";
    }
    if ($data['precio_venta'] <= 0) {
        $errores[] = "El precio debe ser mayor a 0.";
    }
    if ($data['stock'] < 0) {
        $errores[] = "El stock no puede ser negativo.";
    }
    if ($data['numero_piezas'] <= 0) {
        $errores[] = "El número de piezas debe ser mayor a 0.";
    }

    return $errores;
}

    // FORMULARIO CREAR
    public function crear() {
        $this->view('trajes/crear');
    }

    // GUARDAR NUEVO TRAJE
public function guardar() {
    $errores = $this->validarDatosTraje($_POST);
    if (!empty($errores)) {
        $this->view('trajes/crear', ['errores' => $errores]);
        return;
    }

    $this->model->create($_POST);
    header("Location: index.php?c=trajes&a=index");
}


    // FORMULARIO EDITAR
    public function editar() {
        $id = $_GET['id'];
        $traje = $this->model->getById($id);
        $this->view('trajes/editar', ['traje' => $traje]);
    }

    // ACTUALIZAR
    public function actualizar() {
    $errores = $this->validarDatosTraje($_POST);
    if (!empty($errores)) {
        $this->view('trajes/editar', ['errores' => $errores, 'traje' => $_POST]);
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
