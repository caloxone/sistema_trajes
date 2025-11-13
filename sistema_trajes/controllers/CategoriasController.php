<?php
require_once 'core/Controller.php';
require_once 'models/Categoria.php';

class CategoriasController extends Controller {

    private $model;

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        $this->model = new Categoria();
    }

    public function index() {
        $categorias = $this->model->getAll();
        $this->view('categorias/index', ['categorias' => $categorias]);
    }

    public function crear() {
        $categoria = ['id' => '', 'nombre' => '', 'descripcion' => ''];
        $this->view('categorias/formulario', [
            'categoria' => $categoria,
            'accion'    => 'guardar',
            'titulo'    => 'Registrar categoría'
        ]);
    }

    public function guardar() {
        $this->model->create($_POST);
        header('Location: index.php?c=categorias&a=index');
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?c=categorias&a=index');
            return;
        }
        $categoria = $this->model->getById($id);
        $this->view('categorias/formulario', [
            'categoria' => $categoria,
            'accion'    => 'actualizar',
            'titulo'    => 'Editar categoría'
        ]);
    }

    public function actualizar() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header('Location: index.php?c=categorias&a=index');
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?c=categorias&a=index');
    }
}
