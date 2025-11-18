# Lote Trajes (controlador + formularios)

Este paquete reúne los tres archivos que corrigen el error de llave foránea al registrar trajes. Copia cada bloque completo en tu instalación de XAMPP para asegurar que el controlador valide las referencias y que los formularios usen desplegables con datos reales.

---

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

---

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

---

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

---
