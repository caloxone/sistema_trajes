<?php
require_once 'core/Controller.php';
require_once 'core/Database.php';

class AuthController extends Controller {

    public function login() {
        // Si ya está logueado, mandarlo al inicio
        if (isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=trajes&a=index');
            return;
        }

        $usuarioRecordado = isset($_COOKIE['recordar_usuario']) ? $_COOKIE['recordar_usuario'] : '';
        $error = isset($_GET['error']) ? true : false;

        $this->view('auth/login', [
            'usuarioRecordado' => $usuarioRecordado,
            'error'            => $error
        ]);
    }

    public function ingresar() {
        $usuario  = isset($_POST['usuario']) ? $_POST['usuario'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $recordar = isset($_POST['recordar']);

        if ($usuario === '' || $password === '') {
            header('Location: index.php?c=auth&a=login&error=1');
            return;
        }

        $pdo = Database::getInstance();
        $sql = "SELECT * FROM usuarios WHERE usuario = ? AND estado = 1 LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Password MD5 (como el usuario admin que insertamos en la BD)
        if ($user && $user['password'] === md5($password)) {

            // Guardar datos en sesión
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['usuario']    = $user['usuario'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['rol']        = $user['rol'];

            // Cookie "recordar usuario"
            if ($recordar) {
                setcookie('recordar_usuario', $usuario, [
                    'expires'  => time() + 60 * 60 * 24 * 30, // 30 días
                    'path'     => '/',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            } else {
                // Borrar cookie si existe
                setcookie('recordar_usuario', '', time() - 3600, '/');
            }

            header('Location: index.php?c=trajes&a=index');
        } else {
            // Usuario o password inválidos
            header('Location: index.php?c=auth&a=login&error=1');
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?c=auth&a=login');
    }
}
