<?php
class Controller
{
    protected function view($vista, $data = [])
    {
        extract($data);

        // Vistas normales: incluyen header y footer
        if (strpos($vista, 'auth/') !== 0) {
            require 'views/layout/header.php';
            require 'views/' . $vista . '.php';
            require 'views/layout/footer.php';
        } else {
            // Vista de login: página completa propia
            require 'views/' . $vista . '.php';
        }
    }
}
