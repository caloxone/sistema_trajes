<?php
require_once 'core/Database.php';

class Venta {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // LISTAR TODAS LAS VENTAS (para el index)
    public function getAll() {
        $sql = "SELECT v.*, 
                       c.nombre AS cliente,
                       u.nombre AS usuario
                FROM ventas v
                LEFT JOIN clientes c ON v.id_cliente = c.id
                LEFT JOIN usuarios u ON v.id_usuario = u.id
                ORDER BY v.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER UNA VENTA POR ID (cabecera)
    public function getById($id) {
        $sql = "SELECT v.*, 
                       c.nombre AS cliente,
                       c.ci_nit,
                       u.nombre AS usuario
                FROM ventas v
                LEFT JOIN clientes c ON v.id_cliente = c.id
                LEFT JOIN usuarios u ON v.id_usuario = u.id
                WHERE v.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // OBTENER DETALLE DE UNA VENTA
    public function getDetalle($idVenta) {
        $sql = "SELECT d.*, 
                       t.codigo,
                       t.nombre AS traje
                FROM detalles_venta d
                INNER JOIN trajes t ON d.id_traje = t.id
                WHERE d.id_venta = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * CREAR VENTA COMPLETA (cabecera + detalle + descuento de stock)
     * $cabecera: array con id_usuario, id_cliente, total, observaciones
     * $items: array de items [ [id_traje, cantidad, precio_unitario], ... ]
     */
    public function crearVenta($cabecera, $items) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar cabecera
            $sqlVenta = "INSERT INTO ventas (id_usuario, id_cliente, total, observaciones)
                         VALUES (?, ?, ?, ?)";
            $stmtVenta = $this->pdo->prepare($sqlVenta);
            $stmtVenta->execute([
                $cabecera['id_usuario'],
                $cabecera['id_cliente'],
                $cabecera['total'],
                $cabecera['observaciones']
            ]);

            $idVenta = $this->pdo->lastInsertId();

            // 2. Insertar detalle + actualizar stock
            $sqlDetalle = "INSERT INTO detalles_venta 
                          (id_venta, id_traje, cantidad, precio_unitario, subtotal)
                          VALUES (?, ?, ?, ?, ?)";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            $sqlStock = "UPDATE trajes 
                         SET stock = stock - ?
                         WHERE id = ? AND stock >= ?";
            $stmtStock = $this->pdo->prepare($sqlStock);

            foreach ($items as $item) {
                $idTraje = $item['id_traje'];
                $cantidad = $item['cantidad'];
                $precio = $item['precio_unitario'];
                $subtotal = $cantidad * $precio;

                // Insertar detalle
                $stmtDetalle->execute([
                    $idVenta,
                    $idTraje,
                    $cantidad,
                    $precio,
                    $subtotal
                ]);

                // Descontar stock (evitar stock negativo)
                $stmtStock->execute([
                    $cantidad,
                    $idTraje,
                    $cantidad
                ]);

                if ($stmtStock->rowCount() == 0) {
                    // Stock insuficiente, rollback
                    throw new Exception("Stock insuficiente para el traje ID: " . $idTraje);
                }
            }

            $this->pdo->commit();
            return $idVenta;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            // En sistema real, loggear error
            return false;
        }
    }
}
