/**
 * UI y lógica para mostrar/cargar/eliminar borrador de carga manual
 * Requiere los endpoints:
 *   - ../backend/cargar_borrador_carga_manual.php (GET, retorna {success, datos, fecha_modificacion})
 *   - ../backend/eliminar_borrador_carga_manual.php (POST, retorna {success})
 * Requiere que deserializarTablaCargaManual(datos) esté disponible globalmente.
 */

function crearBloqueBorradorCargaManual() {
    // Crear el bloque HTML
    const bloque = document.createElement('div');
    bloque.id = 'bloque-borrador-carga-manual';
    bloque.style.background = '#f4f8e8';
    bloque.style.border = '1px solid #c5d4c1';
    bloque.style.borderRadius = '8px';
    bloque.style.padding = '16px 20px';
    bloque.style.marginBottom = '24px';
    bloque.style.display = 'flex';
    bloque.style.alignItems = 'center';
    bloque.style.justifyContent = 'space-between';
    bloque.style.gap = '12px';
    bloque.style.fontSize = '1.05em';

    bloque.innerHTML = `
        <span id="borrador-info">
            <b>Borrador disponible.</b>
            <span id="borrador-fecha"></span>
        </span>
        <div>
            <button id="btn-cargar-borrador" style="background:#879683;color:white;border:none;padding:10px 18px;border-radius:5px;cursor:pointer;margin-right:8px;">Cargar borrador</button>
            <button id="btn-eliminar-borrador" style="background:#e74c3c;color:white;border:none;padding:10px 18px;border-radius:5px;cursor:pointer;">Eliminar borrador</button>
        </div>
    `;
    bloque.style.display = 'none'; // Oculto por defecto
    return bloque;
}

function mostrarBloqueBorrador(fechaModificacion) {
    let bloque = document.getElementById('bloque-borrador-carga-manual');
    if (!bloque) {
        bloque = crearBloqueBorradorCargaManual();
        // Insertar después del h2 principal
        const container = document.querySelector('.container');
        const h2 = container.querySelector('h2');
        h2.parentNode.insertBefore(bloque, h2.nextSibling);
    }
    bloque.style.display = 'flex';
    document.getElementById('borrador-fecha').textContent = fechaModificacion
        ? `Última modificación: ${fechaModificacion}`
        : '';
}

function ocultarBloqueBorrador() {
    const bloque = document.getElementById('bloque-borrador-carga-manual');
    if (bloque) bloque.style.display = 'none';
}

function consultarEstadoBorrador() {
    fetch('../backend/cargar_borrador_carga_manual.php')
        .then(r => r.json())
        .then(data => {
            if (data.success && data.datos && data.fecha_modificacion) {
                mostrarBloqueBorrador(data.fecha_modificacion);
                // Asignar eventos
                document.getElementById('btn-cargar-borrador').onclick = function() {
                    if (confirm('¿Deseas cargar el borrador guardado? Se sobrescribirá la tabla actual.')) {
                        deserializarTablaCargaManual(data.datos);
                        ocultarBloqueBorrador();
                    }
                };
                document.getElementById('btn-eliminar-borrador').onclick = function() {
                    if (confirm('¿Eliminar el borrador guardado? Esta acción no se puede deshacer.')) {
                        fetch('../backend/eliminar_borrador_carga_manual.php', {method:'POST'})
                            .then(r=>r.json())
                            .then(resp=>{
                                if(resp.success){
                                    ocultarBloqueBorrador();
                                    alert('Borrador eliminado.');
                                }else{
                                    alert('No se pudo eliminar el borrador.');
                                }
                            });
                    }
                };
            } else {
                ocultarBloqueBorrador();
            }
        });
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', consultarEstadoBorrador);