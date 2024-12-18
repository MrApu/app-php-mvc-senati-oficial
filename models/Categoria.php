<?php
//models/Categoria.php
class Categoria {
    private $conn;
    private $table = 'categoria';
    
    public $id_categoria;
    public $nombre;
    public $descripcion;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerCategoria() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_categoria DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function crearCategoria() {
        $query = "INSERT INTO " . $this->table . " (nombre, descripcion, estado) VALUES (:nombre, :descripcion, :estado)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Limpiar y validar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->estado = htmlspecialchars(strip_tags($this->estado));
            
            // Vincular parámetros
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':estado', $this->estado);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            error_log("Error en crearCategoria: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarCategoria() {
        $query = "UPDATE " . $this->table . " 
                SET nombre = :nombre, 
                    descripcion = :descripcion, 
                    estado = :estado 
                WHERE id_categoria = :id_categoria";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Limpiar y validar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->estado = htmlspecialchars(strip_tags($this->estado));
            $this->id_categoria = htmlspecialchars(strip_tags($this->id_categoria));
            
            // Vincular parámetros
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':id_categoria', $this->id_categoria);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            error_log("Error en actualizarCategoria: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCategoria() {
        $query = "DELETE FROM " . $this->table . " WHERE id_categoria = :id_categoria";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->id_categoria = htmlspecialchars(strip_tags($this->id_categoria));
            $stmt->bindParam(':id_categoria', $this->id_categoria);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            error_log("Error en eliminarCategoria: " . $e->getMessage());
            return false;
        }
    }
}
