<?php
require_once 'core/Controller.php';
require_once 'models/Cliente.php';

class ClientesController extends Controller {
private function validarCliente($data) {
    $errores = [];

    if (trim($data['nombre']) === "") {
        $errores[] = "El nombre del cliente es obligatorio.";
    }
    if (!preg_match("/^[0-9]{5,15}$/", $data['ci_nit'])) {
        $errores[] = "El CI/NIT debe ser numérico y tener entre 5 y 15 dígitos.";
    }
    if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }
    if (!preg_match("/^[0-9]{7,15}$/", $data['telefono'])) {
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
        $this->model->create($_POST);
        header('Location: index.php?c=clientes&a=index');
    }

    // FORMULARIO EDITAR CLIENTE
    public function editar() {
        $id = $_GET['id'] ?? null;
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
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header('Location: index.php?c=clientes&a=index');
    }

    // ELIMINAR CLIENTE
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?c=clientes&a=index');
    }
}
