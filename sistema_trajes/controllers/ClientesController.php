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
