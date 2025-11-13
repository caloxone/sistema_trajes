<?php
require_once 'core/Database.php';

class Traje
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "SELECT t.*, 
                       c.nombre AS categoria, 
                       te.nombre AS tela, 
                       ta.talla AS talla
                FROM trajes t
                LEFT JOIN categorias c ON t.id_categoria = c.id
                LEFT JOIN telas te ON t.id_tela = te.id
                LEFT JOIN tallas ta ON t.id_talla = ta.id
                ORDER BY t.id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM trajes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO trajes
                (codigo, nombre, id_categoria, id_tela, id_talla, numero_piezas, color, tipo, precio_venta, stock)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['codigo'],
            $data['nombre'],
            $data['id_categoria'] ?: null,
            $data['id_tela'] ?: null,
            $data['id_talla'] ?: null,
            $data['numero_piezas'],
            $data['color'],
            $data['tipo'],
            $data['precio_venta'],
            $data['stock']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE trajes SET
                    codigo = ?, nombre = ?, id_categoria = ?, id_tela = ?, id_talla = ?,
                    numero_piezas = ?, color = ?, tipo = ?, precio_venta = ?, stock = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['codigo'],
            $data['nombre'],
            $data['id_categoria'] ?: null,
            $data['id_tela'] ?: null,
            $data['id_talla'] ?: null,
            $data['numero_piezas'],
            $data['color'],
            $data['tipo'],
            $data['precio_venta'],
            $data['stock'],
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM trajes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
