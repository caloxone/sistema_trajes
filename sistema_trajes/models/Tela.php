<?php
require_once 'core/Database.php';

class Tela {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM telas ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM telas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO telas (nombre, descripcion)
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE telas
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
        $sql = "DELETE FROM telas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
