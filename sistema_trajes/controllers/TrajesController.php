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
