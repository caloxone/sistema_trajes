<?php
require_once 'core/Controller.php';
require_once 'models/Tela.php';

class TelasController extends Controller {

    private $model;

    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }
        $this->model = new Tela();
    }

    public function index() {
        $telas = $this->model->getAll();
        $this->view('telas/index', ['telas' => $telas]);
    }

    public function crear() {
        $tela = ['id' => '', 'nombre' => '', 'descripcion' => ''];
        $this->view('telas/formulario', [
            'tela'   => $tela,
            'accion' => 'guardar',
            'titulo' => 'Registrar tipo de tela'
        ]);
    }

    public function guardar() {
        $this->model->create($_POST);
        header('Location: index.php?c=telas&a=index');
    }

    public function editar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: index.php?c=telas&a=index');
            return;
        }
        $tela = $this->model->getById($id);
        $this->view('telas/formulario', [
            'tela'   => $tela,
            'accion' => 'actualizar',
            'titulo' => 'Editar tipo de tela'
        ]);
    }

    public function actualizar() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header('Location: index.php?c=telas&a=index');
    }

    public function eliminar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?c=telas&a=index');
    }
}
