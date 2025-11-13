<?php
session_start();

require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'core/Controller.php';

// Si no hay usuario logueado, obligamos a ir al login,
// excepto si ya está llamando a AuthController.
$controlador = isset($_GET['c']) ? $_GET['c'] : null;

if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión y no pidió explícitamente auth, forzamos login
    if ($controlador !== 'auth') {
        $controlador = 'auth';
        $_GET['c'] = 'auth';
        $_GET['a'] = 'login';
    }
}

// Defaults si no hay acción
$controlador = isset($_GET['c']) ? $_GET['c'] : 'trajes';
$accion      = isset($_GET['a']) ? $_GET['a'] : 'index';

$controladorClase = ucfirst($controlador) . 'Controller';
$archivo = 'controllers/' . $controladorClase . '.php';

if (file_exists($archivo)) {
    require_once $archivo;
    $obj = new $controladorClase();

    if (method_exists($obj, $accion)) {
        $obj->$accion();
    } else {
        echo "Acción no encontrada";
    }
} else {
    echo "Controlador no encontrado";
}
