// document.addEventListener('DOMContentLoaded',function(){
//     listar();
// })

async function listar() {
    try {
        const respuesta = await fetch('tipo-documento/listar');
        const resultado = await respuesta.json();

        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        const tipoDocumento = resultado.data;

        const tbody = document.getElementById('tablaTipoDocumento');
        tbody.innerHTML = '';
        
        tipoDocumento.forEach(tipoDoc => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${tipoDoc.id_tipodocumento}</td>
                <td>${tipoDoc.nombre}</td>
                <td>${tipoDoc.sigla}</td>
                <td>${tipoDoc.orden}</td>
                <td>${tipoDoc.fecha_creacion}</td>
                <td>${tipoDoc.fecha_actualizacion}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" 
                            onclick="mostrarDataEdit(${JSON.stringify(tipoDoc).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminar(${tipoDoc.id_tipodocumento})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        showAlert('error', 'Error al cargar los productos: ff' + error.message);
    }
}

async function crear(){
    try {
        const formData = new FormData();
        const nombre = document.getElementById('name').value;
        const sigla = document.getElementById('sigla').value;
        const orden = document.getElementById('orden').value;
        const fecha = document.getElementById('fecha').value;
        const fechaHora = document.getElementById('fechaHora').value;

        formData.append('nombre', nombre);
        formData.append('sigla', sigla);
        formData.append('orden', orden);
        formData.append('fecha', fecha);
        formData.append('fechaHora', fechaHora);

        const respuesta = await fetch('tipo-documento/guardar', {
            method: 'POST',
            body: formData
        });

        const resultado = await respuesta.json();

        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('TipoDocumentoModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showAlert('success', resultado.message);

        // Recargar la lista de Tipo Documento
        listar();

        // Resetear el formulario
        resetForm();        
    } catch (error) {
        showAlert('error', error.message);
    }
}
function guardar(){
    const tipoDocumentoId = document.getElementById('tipoDocumentoId').value;
    if(tipoDocumentoId){
        editar();
    }else{
        crear();
    }
}
async function editar(){
    try {
        const formData = new FormData();
        const tipoDocumentoId = document.getElementById('tipoDocumentoId').value;
        const nombre = document.getElementById('name').value;
        const sigla = document.getElementById('sigla').value;
        const orden = document.getElementById('orden').value;

        formData.append('nombre', nombre);
        formData.append('sigla', sigla);
        formData.append('orden', orden);
        formData.append('id', tipoDocumentoId);

        const respuesta = await fetch('tipo-documento/actualizar', {
            method: 'POST',
            body: formData
        });

        const resultado = await respuesta.json();

        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('TipoDocumentoModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showAlert('success', resultado.message);

        // Recargar la lista de Tipo Documento
        listar();

        // Resetear el formulario
        resetForm();        
    } catch (error) {
        showAlert('error', error.message);
    }
}

async function eliminar(id) {
    try {
        if (!confirm('¿Está seguro de que desea eliminar?')) {
            return;
        }

        const respuesta = await fetch('tipo-documento/eliminar', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_tipoDocumento:id })
        });

        const resultado = await respuesta.json();

        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        showAlert('success', resultado.message);
        listar();

    } catch (error) {
        showAlert('error', error.message);
    }
}

function mostrarDataEdit(tipoDoc) {
    console.log(tipoDoc);
    
    document.getElementById('tipoDocumentoId').value = tipoDoc.id_tipodocumento;
    document.getElementById('name').value = tipoDoc.nombre;
    document.getElementById('sigla').value = tipoDoc.sigla;
    document.getElementById('orden').value = tipoDoc.orden;
    
    
    // Actualizar título del modal
    document.getElementById('modalTitle').textContent = 'Editar Tipo Documento';
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('TipoDocumentoModal'));
    modal.show();
}

function resetForm() {
    document.getElementById('tipoDocumentoId').value = '';
    document.getElementById('tipoDocumentoForm').reset();
    document.getElementById('modalTitle').textContent = 'Nuevo Tipo Documento';
}