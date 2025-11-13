<?php
require_once 'core/Database.php';

class Categoria {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM categorias ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM categorias WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO categorias (nombre, descripcion)
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE categorias
                SET nombre = ?, descripcion = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM categorias WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
