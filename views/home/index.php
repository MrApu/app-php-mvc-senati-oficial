<!-- views/home/index.php -->

<div class="container py-5">
    <div class="row">
        <div class="col-md-12 text-center mb-4">
            <h1>Sistema de Gestión de Inventario</h1>
            <p class="lead">Administra tu inventario de manera fácil y eficiente</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mx-auto">
            <select class="form-select form-select-lg" id="quickAccess" onchange="window.location.href=this.value">
                <option value="" selected disabled>Seleccione para acceso rápido</option>
                <option value="<?= BASE_URL ?>/productos">Productos</option>
                <option value="<?= BASE_URL ?>/categorias">Categorías</option>
                <option value="<?= BASE_URL ?>/proveedores">Proveedores</option>
            </select>
        </div>
    </div>
    
    <div class="row g-4 justify-content-center">
        <!-- Productos -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-white" style="background: linear-gradient(45deg, #4CAF50, #45a049); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-3x mb-3"></i>
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text">Gestionar inventario de productos</p>
                    <a href="<?= BASE_URL ?>/productos" class="btn btn-light w-100">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-white" style="background: linear-gradient(45deg, #2196F3, #1976D2); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body text-center">
                    <i class="fas fa-tags fa-3x mb-3"></i>
                    <h5 class="card-title">Categorías</h5>
                    <p class="card-text">Administrar categorías de productos</p>
                    <a href="<?= BASE_URL ?>/categorias" class="btn btn-light w-100">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Proveedores -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-white" style="background: linear-gradient(45deg, #FF9800, #F57C00); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body text-center">
                    <i class="fas fa-truck fa-3x mb-3"></i>
                    <h5 class="card-title">Proveedores</h5>
                    <p class="card-text">Gestionar proveedores</p>
                    <a href="<?= BASE_URL ?>/proveedores" class="btn btn-light w-100">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 text-white" style="background: linear-gradient(45deg, #f44336, #d32f2f); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Generar reportes</p>
                    <div class="btn-group w-100">
                        <a href="<?= BASE_URL ?>/reporte/pdf" class="btn btn-light">PDF</a>
                        <a href="<?= BASE_URL ?>/reporte/excel" class="btn btn-light">Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3) !important;
    cursor: pointer;
}

.form-select {
    border: 2px solid #ddd;
    padding: 12px;
    font-size: 1.1rem;
    border-radius: 10px;
    background-color: white;
    transition: all 0.3s ease;
}

.form-select:hover {
    border-color: #4CAF50;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.form-select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
}
</style>