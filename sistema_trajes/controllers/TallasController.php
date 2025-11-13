<?php
require_once 'core/Controller.php';
require_once 'models/Talla.php';

class TallasController extends Controller {

    private $model;

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        $this->model = new Talla();
    }

    public function index() {
        $tallas = $this->model->getAll();
        $this->view('tallas/index', ['tallas' => $tallas]);
    }

    public function crear() {
        $talla = ['id' => '', 'talla' => '', 'descripcion' => ''];
        $this->view('tallas/formulario', [
            'talla'  => $talla,
            'accion' => 'guardar',
            'titulo' => 'Registrar talla'
        ]);
    }

    public function guardar() {
        $this->model->create($_POST);
        header('Location: index.php?c=tallas&a=index');
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?c=tallas&a=index');
            return;
        }
        $talla = $this->model->getById($id);
        $this->view('tallas/formulario', [
            'talla'  => $talla,
            'accion' => 'actualizar',
            'titulo' => 'Editar talla'
        ]);
    }

    public function actualizar() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header('Location: index.php?c=tallas&a=index');
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?c=tallas&a=index');
    }
}
