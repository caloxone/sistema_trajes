<?php
require_once 'core/Database.php';

class Talla {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM tallas ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM tallas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO tallas (talla, descripcion)
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['talla'],
            $data['descripcion']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE tallas
                SET talla = ?, descripcion = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['talla'],
            $data['descripcion'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM tallas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
