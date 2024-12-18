<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4>Gestión de Proveedores</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProveedor">
                        <i class="fas fa-plus"></i> Nuevo Proveedor
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProveedores">
                                <!-- Los datos se cargarán dinámicamente aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Proveedor -->
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProveedorLabel">Nuevo Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formProveedor">
                    <input type="hidden" id="id_proveedor" name="id_proveedor">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="contacto" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="contacto" name="contacto">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarProveedor()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', cargarProveedores);

function cargarProveedores() {
    fetch('<?= BASE_URL ?>/proveedores/obtener-todo')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tabla = document.getElementById('tablaProveedores');
                tabla.innerHTML = '';
                data.data.forEach(proveedor => {
                    tabla.innerHTML += `
                        <tr>
                            <td>${proveedor.id_proveedor}</td>
                            <td>${proveedor.nombre}</td>
                            <td>${proveedor.contacto || '-'}</td>
                            <td>${proveedor.telefono || '-'}</td>
                            <td>
                                <span class="badge bg-${proveedor.estado == 1 ? 'success' : 'secondary'}">
                                    ${proveedor.estado == 1 ? 'Activo' : 'Inactivo'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick='editarProveedor(${JSON.stringify(proveedor)})'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarProveedor(${proveedor.id_proveedor})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

function guardarProveedor() {
    const formData = new FormData(document.getElementById('formProveedor'));
    const id = document.getElementById('id_proveedor').value;
    const url = id ? 
        '<?= BASE_URL ?>/proveedores/actualizar-proveedor' : 
        '<?= BASE_URL ?>/proveedores/guardar-proveedor';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('formProveedor').reset();
            $('#modalProveedor').modal('hide');
            cargarProveedores();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function editarProveedor(proveedor) {
    document.getElementById('id_proveedor').value = proveedor.id_proveedor;
    document.getElementById('nombre').value = proveedor.nombre;
    document.getElementById('contacto').value = proveedor.contacto;
    document.getElementById('telefono').value = proveedor.telefono;
    document.getElementById('estado').value = proveedor.estado;
    
    document.getElementById('modalProveedorLabel').textContent = 'Editar Proveedor';
    $('#modalProveedor').modal('show');
}

function eliminarProveedor(id) {
    if (confirm('¿Está seguro de que desea eliminar este proveedor?')) {
        fetch(`<?= BASE_URL ?>/proveedores/eliminar-proveedor`, {
            method: 'DELETE',
            body: JSON.stringify({ id_proveedor: id }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                cargarProveedores();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Limpiar formulario al abrir modal para nuevo proveedor
document.getElementById('modalProveedor').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || !event.relatedTarget.classList.contains('btn-info')) {
        document.getElementById('formProveedor').reset();
        document.getElementById('id_proveedor').value = '';
        document.getElementById('modalProveedorLabel').textContent = 'Nuevo Proveedor';
    }
});
</script>
