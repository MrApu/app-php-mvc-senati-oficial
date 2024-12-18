<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4>Gestión de Categorías</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                        <i class="fas fa-plus"></i> Nueva Categoría
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCategorias">
                                <!-- Los datos se cargarán dinámicamente aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Categoría -->
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCategoriaLabel">Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCategoria">
                    <input type="hidden" id="id_categoria" name="id_categoria">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
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
                <button type="submit" class="btn btn-primary" form="formCategoria">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', cargarCategorias);

function cargarCategorias() {
    fetch('<?= BASE_URL ?>/categorias/obtener-todo')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tabla = document.getElementById('tablaCategorias');
                tabla.innerHTML = '';
                data.data.forEach(categoria => {
                    tabla.innerHTML += `
                        <tr>
                            <td>${categoria.id_categoria}</td>
                            <td>${categoria.nombre}</td>
                            <td>${categoria.descripcion || '-'}</td>
                            <td>
                                <span class="badge bg-${categoria.estado == 1 ? 'success' : 'secondary'}">
                                    ${categoria.estado == 1 ? 'Activo' : 'Inactivo'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick='editarCategoria(${JSON.stringify(categoria)})'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarCategoria(${categoria.id_categoria})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar categorías:', error);
        });
}

function guardarCategoria(event) {
    event.preventDefault(); // Prevenir el envío normal del formulario
    
    const form = document.getElementById('formCategoria');
    const formData = new FormData(form);
    
    // Validar campos requeridos
    if (!formData.get('nombre')) {
        alert('El nombre es requerido');
        return;
    }

    // Asegurarse de que estado tenga un valor
    if (!formData.get('estado')) {
        formData.set('estado', '1');
    }

    const url = formData.get('id_categoria') ? 
        '<?= BASE_URL ?>/categorias/actualizar-categoria' : 
        '<?= BASE_URL ?>/categorias/guardar-categoria';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            $('#modalCategoria').modal('hide');
            form.reset();
            cargarCategorias();
            alert(data.message);
        } else {
            alert(data.message || 'Error al procesar la solicitud');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud. Por favor, intente nuevamente.');
    });
}

function editarCategoria(categoria) {
    const form = document.getElementById('formCategoria');
    form.id_categoria.value = categoria.id_categoria;
    form.nombre.value = categoria.nombre;
    form.descripcion.value = categoria.descripcion || '';
    form.estado.value = categoria.estado;
    
    document.getElementById('modalCategoriaLabel').textContent = 'Editar Categoría';
    $('#modalCategoria').modal('show');
}

function eliminarCategoria(id) {
    if (!confirm('¿Está seguro de que desea eliminar esta categoría?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id_categoria', id);

    fetch('<?= BASE_URL ?>/categorias/eliminar-categoria', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            cargarCategorias();
            alert(data.message);
        } else {
            alert(data.message || 'Error al eliminar la categoría');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar la categoría. Por favor, intente nuevamente.');
    });
}

// Agregar el event listener al formulario
document.getElementById('formCategoria').addEventListener('submit', guardarCategoria);

// Limpiar formulario al abrir modal para nueva categoría
document.getElementById('modalCategoria').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || !event.relatedTarget.classList.contains('btn-info')) {
        const form = document.getElementById('formCategoria');
        form.reset();
        form.id_categoria.value = '';
        document.getElementById('modalCategoriaLabel').textContent = 'Nueva Categoría';
    }
});
</script>
