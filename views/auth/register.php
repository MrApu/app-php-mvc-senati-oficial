<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si ya está autenticado

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        .card-body {
            padding: 3rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #e1e1e1;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118,75,162,0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .register-title {
            color: #333;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .form-floating {
            margin-bottom: 1.5rem;
        }
        .login-link {
            color: #764ba2;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .login-link:hover {
            color: #667eea;
        }
        .form-select {
            height: 58px;
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-5">
                            <i class="fas fa-user-plus fa-3x mb-3" style="color: #764ba2;"></i>
                            <h2 class="register-title">Crear Cuenta</h2>
                        </div>
                        <div id="registerAlert"></div>
                        <form id="registerForm" onsubmit="register(event)">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="full_name" placeholder="Nombre Completo" required>
                                <label for="full_name"><i class="fas fa-user me-2"></i>Nombre Completo</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="username" placeholder="Usuario" required>
                                <label for="username"><i class="fas fa-user-circle me-2"></i>Usuario</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="email" class="form-control" id="email" placeholder="Email" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                            </div>
                            <div class="form-floating mb-4">
                                <select class="form-select" id="rol" aria-label="Rol">
                                    <option selected value="cliente">Cliente</option>
                                    <option value="user">Usuario</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                <label for="rol"><i class="fas fa-user-tag me-2"></i>Rol</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                                <label for="password"><i class="fas fa-lock me-2"></i>Contraseña</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="confirm_password" placeholder="Confirmar Contraseña" required>
                                <label for="confirm_password"><i class="fas fa-lock me-2"></i>Confirmar Contraseña</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-4">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </button>
                            <div class="text-center">
                                <p class="mb-0">¿Ya tienes cuenta? 
                                    <a href="<?= BASE_URL ?>/login" class="login-link">
                                        Inicia Sesión aquí
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/auth.js"></script>
</body>
</html>