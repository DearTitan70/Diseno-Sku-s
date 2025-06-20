/**
 * Serializa el estado de la tabla de carga manual
 * @returns {Array} Array de objetos, cada uno representa una fila
 */
function serializarTablaCargaManual() {
    const filas = document.querySelectorAll('#skuTable tbody .fila-carga');
    const datos = [];
    filas.forEach(row => {
        const fila = {};
        row.querySelectorAll('.campo-formulario[data-campo-nombre]').forEach(field => {
            const nombre = field.dataset.campoNombre;
            if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                fila[nombre] = field.value;
            } else if (field.tagName === 'TD') {
                fila[nombre] = field.textContent.trim();
            }
        });
        datos.push(fila);
    });
    return datos;
}

/**
 * Rellena la tabla con los datos serializados
 * @param {Array} datos
 */
function deserializarTablaCargaManual(datos) {
    if (!Array.isArray(datos) || datos.length === 0) return;
    const tbody = document.querySelector('#skuTable tbody');
    // Limpiar todas las filas menos la primera
    tbody.querySelectorAll('.fila-carga').forEach((fila, idx) => {
        if (idx > 0) fila.remove();
    });
    // Rellenar la primera fila y clonar si hay más
    datos.forEach((filaData, idx) => {
        let row;
        if (idx === 0) {
            row = tbody.querySelector('.fila-carga');
        } else {
            row = tbody.querySelector('.fila-carga').cloneNode(true);
            tbody.appendChild(row);
        }
        // Rellenar campos
        for (const [nombre, valor] of Object.entries(filaData)) {
            const field = row.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
            if (field) {
                if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                    field.value = valor;
                } else if (field.tagName === 'TD') {
                    field.textContent = valor;
                }
            }
        }
        // Re-inicializar listeners y dependientes
        if (typeof initializeRowFields === 'function') {
            initializeRowFields(row);
        }
    });
}

/**
 * Guarda el borrador automáticamente cada 10 segundos o al cambiar algo
 */
function iniciarAutoGuardadoBorrador() {
    let timeout = null;
    function guardar() {
        const datos = serializarTablaCargaManual();
        fetch('../backend/guardar_borrador_carga_manual.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ datos })
        });
    }
    // Guardar al cambiar algo
    document.querySelector('#skuTable tbody').addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(guardar, 1000);
    });
}

/**
 * Carga el borrador bajo demanda (ya no se llama automáticamente)
 */
function cargarBorradorCargaManual() {
    fetch('../backend/cargar_borrador_carga_manual.php')
        .then(r => r.json())
        .then(data => {
            if (data.success && data.datos) {
                deserializarTablaCargaManual(data.datos);
            }
        });
}

// Inicializar solo el autoguardado al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    iniciarAutoGuardadoBorrador();
});

// Exponer la función de carga manual globalmente para que pueda ser llamada desde la UI
window.cargarBorradorCargaManual = cargarBorradorCargaManual;