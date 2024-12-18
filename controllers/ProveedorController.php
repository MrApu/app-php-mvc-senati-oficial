<?php
class ProveedorController {
    private $proveedor;
    private $db;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->connect();
            $this->proveedor = new Proveedor($this->db);
        } catch (Exception $e) {
            error_log("Error en ProveedorController::__construct: " . $e->getMessage());
            throw new Exception("Error al inicializar el controlador de proveedores");
        }
    }

    public function index() {
        include 'views/layouts/header.php';
        include 'views/proveedores/index.php';
        include 'views/layouts/footer.php';
    }

    public function obtenerTodo() {
        header('Content-Type: application/json');
        try {
            $stmt = $this->proveedor->obtenerProveedor();
            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'status' => 'success',
                'data' => $proveedores
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function guardarProveedor() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
        
        try {
            if (empty($data->nombre) || !isset($data->estado)) {
                throw new Exception('El nombre y estado son requeridos');
            }

            $this->proveedor->nombre = $data->nombre;
            $this->proveedor->contacto = $data->contacto ?? '';
            $this->proveedor->telefono = $data->telefono ?? '';
            $this->proveedor->estado = $data->estado;

            if ($this->proveedor->crearProveedor()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proveedor creado exitosamente'
                ]);
            } else {
                throw new Exception('Error al crear el proveedor');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function actualizarProveedor() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
        
        try {
            if (empty($data->id_proveedor) || empty($data->nombre) || !isset($data->estado)) {
                throw new Exception('Datos incompletos para actualizar');
            }

            $this->proveedor->id_proveedor = $data->id_proveedor;
            $this->proveedor->nombre = $data->nombre;
            $this->proveedor->contacto = $data->contacto ?? '';
            $this->proveedor->telefono = $data->telefono ?? '';
            $this->proveedor->estado = $data->estado;

            if ($this->proveedor->actualizarProveedor()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proveedor actualizado exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar el proveedor');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function eliminarProveedor() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
        
        try {
            if (empty($data->id_proveedor)) {
                throw new Exception('ID de proveedor no proporcionado');
            }

            $this->proveedor->id_proveedor = $data->id_proveedor;

            if ($this->proveedor->eliminarProveedor()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proveedor eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el proveedor');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
