<?php
//models/Proveedor.php
class Proveedor {
    private $conn;
    public $id_proveedor;
    public $nombre;
    public $ruc;
    public $direccion;
    public $telefono;
    public $email;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerProveedor() {
        $query = "SELECT * FROM proveedor ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function crearProveedor() {
        $query = "INSERT INTO proveedor  
                (nombre, ruc, direccion, telefono, email, estado) 
                VALUES (:nombre, :ruc, :direccion, :telefono, :email, :estado)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':ruc', $this->ruc);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':estado', $this->estado);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function actualizarProveedor() {
        $query = "UPDATE proveedor
                SET nombre = :nombre, 
                    ruc = :ruc,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    estado = :estado
                WHERE id_proveedor = :id_proveedor";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':ruc', $this->ruc);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':id_proveedor', $this->id_proveedor);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function eliminarProveedor() {
        $query = "DELETE FROM proveedor WHERE id_proveedor = :id_proveedor";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_proveedor', $this->id_proveedor);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
