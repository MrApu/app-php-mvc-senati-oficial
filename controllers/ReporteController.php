<?php
// controllers/ReportController.php
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteController {
    private $product;
    private $db;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->connect();
            $this->product = new Producto($this->db);
        } catch (Exception $e) {
            error_log("Error en ReporteController::__construct: " . $e->getMessage());
            throw new Exception("Error al inicializar el controlador de reportes");
        }
    }

    public function reportePdf() {
        try {
            // Obtener productos
            $result = $this->product->obtenerProducto();
            if (!$result) {
                throw new Exception("Error al obtener los productos");
            }
            
            $products = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($products)) {
                throw new Exception("No hay productos para generar el reporte");
            }

            // Configurar DOMPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('defaultFont', 'Arial');

            // Crear instancia de DOMPDF
            $dompdf = new Dompdf($options);

            // Preparar el HTML
            $html = $this->generatePDFTemplate($products);

            // Cargar HTML
            $dompdf->loadHtml($html);

            // Configurar papel y orientación
            $dompdf->setPaper('A4', 'landscape');

            // Renderizar PDF
            $dompdf->render();

            // Nombre del archivo
            $filename = 'productos_' . date('Y-m-d_H-i-s') . '.pdf';

            // Enviar al navegador
            $dompdf->stream($filename, array("Attachment" => true));

        } catch (Exception $e) {
            error_log("Error en ReporteController::reportePdf: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ]);
        }
    }

    public function reporteExcel() {
        try {
            // Obtener productos
            $result = $this->product->obtenerProducto();
            if (!$result) {
                throw new Exception("Error al obtener los productos");
            }
            
            $products = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($products)) {
                throw new Exception("No hay productos para generar el reporte");
            }

            // Crear nuevo documento Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Productos');

            // Establecer encabezados
            $headers = [
                'A1' => 'ID',
                'B1' => 'Nombre',
                'C1' => 'Descripción',
                'D1' => 'Precio',
                'E1' => 'Stock',
                'F1' => 'Categoría',
                'G1' => 'Proveedor',
                'H1' => 'Estado',
                'I1' => 'Fecha Creación'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Estilo para encabezados
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
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            // Agregar datos
            $row = 2;
            foreach ($products as $product) {
                $sheet->setCellValue('A' . $row, $product['id_producto']);
                $sheet->setCellValue('B' . $row, $product['nombre']);
                $sheet->setCellValue('C' . $row, $product['descripcion']);
                $sheet->setCellValue('D' . $row, $product['precio']);
                $sheet->setCellValue('E' . $row, $product['stock']);
                $sheet->setCellValue('F' . $row, $product['categoria']);
                $sheet->setCellValue('G' . $row, $product['proveedor']);
                $sheet->setCellValue('H' . $row, $product['estado']);
                $sheet->setCellValue('I' . $row, $product['fecha_creacion']);
                $row++;
            }

            // Estilo para los datos
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A2:I'.($row-1))->applyFromArray($dataStyle);

            // Autoajustar columnas
            foreach(range('A','I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Crear el archivo Excel
            $writer = new Xlsx($spreadsheet);
            
            // Headers para descarga
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="productos_' . date('Y-m-d_H-i-s') . '.xlsx"');
            header('Cache-Control: max-age=0');

            // Limpiar cualquier salida previa
            if (ob_get_length()) ob_clean();
            
            $writer->save('php://output');
            exit;

        } catch (Exception $e) {
            error_log("Error en ReporteController::reporteExcel: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al generar el Excel: ' . $e->getMessage()
            ]);
        }
    }

    private function generatePDFTemplate($products) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Productos</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    font-size: 12px;
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
                    font-size: 18px;
                    margin-bottom: 10px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    text-align: center;
                    font-size: 10px;
                    padding: 10px 0;
                    border-top: 1px solid #ddd;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Reporte de Productos</h1>
                <p style="font-size: 12px;">Fecha de generación: ' . date('Y-m-d H:i:s') . '</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $this->generateTableRows($products) . '
                </tbody>
            </table>
            <div class="footer">
                <p>Este documento fue generado automáticamente por el sistema de gestión de inventario.</p>
                <p>Página 1</p>
            </div>
        </body>
        </html>';
    }

    private function generateTableRows($products) {
        $rows = '';
        foreach ($products as $product) {
            $rows .= '<tr>
                <td>' . htmlspecialchars($product['id_producto']) . '</td>
                <td>' . htmlspecialchars($product['nombre']) . '</td>
                <td>' . htmlspecialchars($product['descripcion']) . '</td>
                <td>$' . number_format($product['precio'], 2) . '</td>
                <td>' . htmlspecialchars($product['stock']) . '</td>
                <td>' . htmlspecialchars($product['categoria']) . '</td>
                <td>' . htmlspecialchars($product['proveedor']) . '</td>
                <td>' . htmlspecialchars($product['estado']) . '</td>
                <td>' . htmlspecialchars($product['fecha_creacion']) . '</td>
            </tr>';
        }
        return $rows;
    }
}