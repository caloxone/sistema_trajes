<?php
require_once 'core/Database.php';

class Cliente {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // LISTAR TODOS LOS CLIENTES
    public function getAll() {
        $sql = "SELECT * FROM clientes ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER CLIENTE POR ID
    public function getById($id) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR CLIENTE
    public function create($data) {
        $sql = "INSERT INTO clientes (nombre, ci_nit, telefono, correo, direccion)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['ci_nit'],
            $data['telefono'],
            $data['correo'],
            $data['direccion']
        ]);
    }

    // ACTUALIZAR CLIENTE
    public function update($id, $data) {
        $sql = "UPDATE clientes SET
                    nombre = ?,
                    ci_nit = ?,
                    telefono = ?,
                    correo = ?,
                    direccion = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['ci_nit'],
            $data['telefono'],
            $data['correo'],
            $data['direccion'],
            $id
        ]);
    }

    // ELIMINAR CLIENTE
    public function delete($id) {
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
