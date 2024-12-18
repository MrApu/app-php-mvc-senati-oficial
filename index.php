<?php
//Manejo de errores
error_reporting(E_ALL);
ini_set('display_errors',1);

//Cargar el archivo de configuración
require_once 'config/config.php';

//Autoload de clases
spl_autoload_register(function ($class_name) {
    $directories = [
        'controllers/',
        'models/',
        'config/',
        'utils/',
        ''
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

//Crear una instancia del router
$router = new Router();

//Obtener la ruta actual
$current_route = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$current_route = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $current_route);

//HomeController (página principal)
$router->add('GET','/','HomeController','index');
$router->add('GET','/home','HomeController','index');

//CRUD PRODUCTOS//
$router->add('GET','productos/','ProductoController','index');
$router->add('GET','productos/obtener-todo','ProductoController','obtenerProducto');
$router->add('POST','productos/guardar-producto','ProductoController','guardarProducto');
$router->add('POST','productos/actualizar-producto','ProductoController','actualizarProducto');
$router->add('DELETE','productos/eliminar-producto','ProductoController','eliminarProducto');
$router->add('GET','productos/buscar-producto','ProductoController','buscarProducto');

//CRUD CATEGORIAS//
$router->add('GET','categorias/','CategoriaController','index');
$router->add('GET','categorias/obtener-todo','CategoriaController','obtenerCategoria');
$router->add('POST','categorias/guardar-categoria','CategoriaController','guardarCategoria');
$router->add('POST','categorias/actualizar-categoria','CategoriaController','actualizarCategoria');
$router->add('DELETE','categorias/eliminar-categoria','CategoriaController','eliminarCategoria');
$router->add('GET','categorias/exportar-pdf','CategoriaController','exportarPDF');
$router->add('GET','categorias/exportar-excel','CategoriaController','exportarExcel');

//CRUD PROVEEDORES//
$router->add('GET','proveedores/','ProveedorController','index');
$router->add('GET','proveedores/obtener-todo','ProveedorController','obtenerProveedor');
$router->add('POST','proveedores/guardar-proveedor','ProveedorController','guardarProveedor');
$router->add('POST','proveedores/actualizar-proveedor','ProveedorController','actualizarProveedor');
$router->add('DELETE','proveedores/eliminar-proveedor','ProveedorController','eliminarProveedor');
$router->add('GET','proveedores/exportar-pdf','ProveedorController','exportarPDF');
$router->add('GET','proveedores/exportar-excel','ProveedorController','exportarExcel');

//Reporte en PDF Y EXCEL
$router->add('GET','reporte/pdf','ReporteController','reportePdf');
$router->add('GET','reporte/excel','ReporteController','reporteExcel');

//Despachar la ruta
try {
    $router->dispatch($current_route, $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    // Manejar el error
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        include 'views/errors/404.php';
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}