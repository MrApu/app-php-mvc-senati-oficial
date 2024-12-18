<?php
require_once 'models/Categoria.php';
require_once 'config/Database.php';

class CategoriaController {
    private $categoria;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->categoria = new Categoria($this->db);
    }

    public function index() {
        include 'views/layouts/header.php';
        include 'views/categorias/index.php';
        include 'views/layouts/footer.php';
    }

    public function obtenerTodo() {
        header('Content-Type: application/json');
        try {
            $result = $this->categoria->obtenerCategoria();
            $categorias = $result->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'status' => 'success',
                'data' => $categorias
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al obtener las categorías: ' . $e->getMessage()
            ]);
        }
    }

    public function guardarCategoria() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['nombre']) || empty($_POST['nombre'])) {
                throw new Exception('El nombre es requerido');
            }

            $this->categoria->nombre = $_POST['nombre'];
            $this->categoria->descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
            $this->categoria->estado = isset($_POST['estado']) ? $_POST['estado'] : '1';

            if ($this->categoria->crearCategoria()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Categoría creada exitosamente'
                ]);
            } else {
                throw new Exception('Error al crear la categoría');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function actualizarCategoria() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['id_categoria']) || empty($_POST['id_categoria'])) {
                throw new Exception('ID de categoría es requerido');
            }
            
            if (!isset($_POST['nombre']) || empty($_POST['nombre'])) {
                throw new Exception('El nombre es requerido');
            }

            $this->categoria->id_categoria = $_POST['id_categoria'];
            $this->categoria->nombre = $_POST['nombre'];
            $this->categoria->descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
            $this->categoria->estado = isset($_POST['estado']) ? $_POST['estado'] : '1';

            if ($this->categoria->actualizarCategoria()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Categoría actualizada exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar la categoría');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function eliminarCategoria() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['id_categoria']) || empty($_POST['id_categoria'])) {
                throw new Exception('ID de categoría es requerido');
            }

            $this->categoria->id_categoria = $_POST['id_categoria'];

            if ($this->categoria->eliminarCategoria()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Categoría eliminada exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar la categoría');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportarPDF() {
        require_once 'vendor/autoload.php';
        
        try {
            $resultado = $this->categoria->obtenerCategoria();
            $categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Reporte de Categorías</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #4CAF50;
                        color: white;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    h1 {
                        text-align: center;
                        color: #333;
                    }
                </style>
            </head>
            <body>
                <h1>Reporte de Categorías</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach($categorias as $categoria) {
                $html .= '<tr>
                    <td>'.$categoria['id_categoria'].'</td>
                    <td>'.$categoria['nombre'].'</td>
                    <td>'.$categoria['descripcion'].'</td>
                    <td>'.$categoria['estado'].'</td>
                    <td>'.$categoria['fecha_creacion'].'</td>
                </tr>';
            }
            
            $html .= '</tbody></table></body></html>';

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            
            $dompdf->stream("categorias_reporte.pdf", array("Attachment" => true));

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportarExcel() {
        require_once 'vendor/autoload.php';
        
        try {
            $resultado = $this->categoria->obtenerCategoria();
            $categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Establecer los encabezados
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Nombre');
            $sheet->setCellValue('C1', 'Descripción');
            $sheet->setCellValue('D1', 'Estado');
            $sheet->setCellValue('E1', 'Fecha Creación');

            // Estilo para los encabezados
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

            // Agregar los datos
            $row = 2;
            foreach ($categorias as $categoria) {
                $sheet->setCellValue('A' . $row, $categoria['id_categoria']);
                $sheet->setCellValue('B' . $row, $categoria['nombre']);
                $sheet->setCellValue('C' . $row, $categoria['descripcion']);
                $sheet->setCellValue('D' . $row, $categoria['estado']);
                $sheet->setCellValue('E' . $row, $categoria['fecha_creacion']);
                $row++;
            }

            // Autoajustar el ancho de las columnas
            foreach(range('A','E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Crear el archivo Excel
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            // Configurar headers para la descarga
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="categorias_reporte.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
