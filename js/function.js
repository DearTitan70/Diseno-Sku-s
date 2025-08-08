/**
 * Lógica para autocompletar categoría según el nombre, actualizar subcategoría y ocultar columnas.
 * Debe ser llamado desde initializeRowFields y después de deserializar la tabla.
 */

// Mapeo de palabras clave a categorías
const nombreCategoriaMapa = [
  { keyword: "BLUSA", categoria: "BLUSAS" },
  { keyword: "CAMISA", categoria: "CAMISAS" },
  { keyword: "CAMISETA", categoria: "CAMISETAS" },
  { keyword: "PUNTO", categoria: "PUNTO" },
  { keyword: "FELPA", categoria: "FELPA" },
  { keyword: "CHAQUETA", categoria: "CHAQUETAS" },
  { keyword: "VESTIDO", categoria: "VESTIDOS" },
  { keyword: "FALDA", categoria: "FALDAS" },
  { keyword: "PANTALON", categoria: "PANTALONES" },
  { keyword: "JEAN", categoria: "JEANS" },
  { keyword: "SWEATER", categoria: "FELPA" },
];

// Columnas a ocultar por categoría
const columnasOcultasPorCategorias = {
  BLUSAS: [
    "DISENO", "PUNO", "CAPOTA", "TIRO", "BOTA", "CINTURA", "SILUETA", "CIERRE", "GALGA", "TIPO_GALGA",
    "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1", "COMP_FORRO_2",
    "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  CAMISAS: [
    "DISENO", "TIPO_MANGA", "PUNO", "CAPOTA", "ESCOTE", "CUELLO", "TIRO", "BOTA", "CINTURA", "SILUETA",
    "CIERRE", "GALGA", "TIPO_GALGA", "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1",
    "COMP_FORRO_2", "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  CAMISETAS: [
    "DISENO", "CAPOTA", "TIRO", "BOTA", "CINTURA", "SILUETA", "CIERRE", "GALGA", "TIPO_GALGA", "FORRO",
    "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1", "COMP_FORRO_2", "RELLENO", "%_RELLENO_1",
    "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  PUNTO: [
    "DISENO", "PUNO", "ESCOTE", "TIRO", "BOTA", "CINTURA", "SILUETA", "CIERRE", "FORRO", "%_FORRO_1",
    "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1", "COMP_FORRO_2", "RELLENO", "%_RELLENO_1", "%_RELLENO_2",
    "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  FELPA: [
    "DISENO", "TIPO_MANGA", "PUNO", "ESCOTE", "LARGO", "CUELLO", "TIRO", "BOTA", "CINTURA", "SILUETA",
    "CIERRE", "GALGA", "TIPO_GALGA", "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1",
    "COMP_FORRO_2", "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  CHAQUETAS: [
    "TIPO_MANGA", "PUNO", "ESCOTE", "TIRO", "BOTA", "CINTURA", "SILUETA", "GALGA", "TIPO_GALGA"
  ],
  VESTIDOS: [
    "DISENO", "PUNO", "CAPOTA", "LARGO", "CUELLO", "TIRO", "BOTA", "CINTURA", "CIERRE", "GALGA",
    "TIPO_GALGA", "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1", "COMP_FORRO_2",
    "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  FALDAS: [
    "MANGA", "TIPO_MANGA", "PUNO", "CAPOTA", "ESCOTE", "LARGO", "CUELLO", "TIRO", "BOTA", "SILUETA",
    "CIERRE", "GALGA", "TIPO_GALGA", "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO",
    "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  JEANS: [
    "MANGA", "TIPO_MANGA", "PUNO", "CAPOTA", "ESCOTE", "LARGO", "CUELLO", "CINTURA", "SILUETA",
    "CIERRE", "GALGA", "TIPO_GALGA", "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1",
    "COMP_FORRO_2", "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  PANTALONES: [
    "DISENO", "MANGA", "TIPO_MANGA", "PUNO", "CAPOTA", "ESCOTE", "LARGO", "CUELLO", "CINTURA",
    "SILUETA", "CIERRE", "GALGA", "TIPO_GALGA", "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO",
    "COMP_FORRO_1", "COMP_FORRO_2", "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO",
    "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
};

// Utilidad para buscar categoría por nombre
function obtenerCategoriaPorNombre(nombre) {
  if (!nombre) return "";
  const upper = nombre.toUpperCase();
  const found = nombreCategoriaMapa.find(({ keyword }) => upper.includes(keyword));
  return found ? found.categoria : "";
}

function obtenerIndicesColumnasPorCampo() {
  const indices = {};
  document.querySelectorAll("#skuTable thead th").forEach((th, idx) => {
    const campo = th.getAttribute("data-campo-nombre");
    if (campo) indices[campo] = idx;
  });
  return indices;
}

/**
 * Muestra todas las columnas (header y celdas)
 */
function mostrarTodasLasColumnas() {
  document.querySelectorAll("#skuTable thead th").forEach((th) => th.style.display = "");
  document.querySelectorAll("#skuTable tbody tr").forEach((tr) => {
    tr.querySelectorAll("td").forEach((td) => td.style.display = "");
  });
}

/**
 * Oculta columnas por categoría (header y celdas)
 */
function ocultarColumnasPorCategoria(categoria) {
  mostrarTodasLasColumnas();
  const camposOcultar = columnasOcultasPorCategorias[categoria];
  if (!camposOcultar) return;
  const indices = obtenerIndicesColumnasPorCampo();
  camposOcultar.forEach((campo) => {
    const idx = indices[campo];
    if (typeof idx !== "undefined") {
      // Oculta th
      const th = document.querySelector(`#skuTable thead th:nth-child(${idx + 1})`);
      if (th) th.style.display = "none";
      // Oculta td en cada fila
      document.querySelectorAll(`#skuTable tbody tr`).forEach((tr) => {
        const td = tr.querySelector(`td:nth-child(${idx + 1})`);
        if (td) td.style.display = "none";
      });
    }
  });
}

/**
 * Hook para integrar con tu lógica de autocompletado de categoría
 * Llama a esta función cada vez que cambie la categoría en cualquier fila.
 */
function onCategoriaChangeGlobal() {
  // Puedes decidir la categoría dominante (por ejemplo, la de la primera fila)
  const primeraCategoria = document.querySelector(
    '#skuTable tbody tr .campo-formulario[data-campo-nombre="CATEGORIAS"]'
  );
  if (primeraCategoria) {
    ocultarColumnasPorCategoria(primeraCategoria.value);
  }
}

// Ejemplo de integración: escucha cambios en cualquier select de categoría
document.addEventListener("DOMContentLoaded", function () {
  document.querySelector("#skuTable").addEventListener("change", function (e) {
    if (e.target.classList.contains("campo-formulario") && e.target.dataset.campoNombre === "CATEGORIAS") {
      onCategoriaChangeGlobal();
    }
  });
  // Al cargar, ajusta columnas según la categoría de la primera fila
  onCategoriaChangeGlobal();
});

// Actualiza el select de subcategorías según la categoría seleccionada
async function actualizarSubcategorias(rowElement, categoria, subcategoriaBD = null) {
  // Llama a la API para obtener las subcategorías válidas para la categoría
  const subcatSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="SUBCATEGORIAS"]');
  if (!subcatSelect) return;

  let opciones = [];
  try {
    const resp = await fetch('../api/get_opciones.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        campos_destino: ['SUBCATEGORIAS'],
        form_values: { CATEGORIAS: categoria }
      })
    });
    const data = await resp.json();
    opciones = Array.isArray(data['SUBCATEGORIAS']) ? data['SUBCATEGORIAS'] : [];
  } catch (e) {
    opciones = [];
  }

  // Limpiar y rellenar el select
  const prevValue = subcatSelect.value;
  subcatSelect.innerHTML = '<option value="">Seleccione</option>';
  opciones.forEach(opt => {
    const option = document.createElement('option');
    option.value = opt;
    option.textContent = opt;
    subcatSelect.appendChild(option);
  });

  // Seleccionar la subcategoría que viene de la BD si es válida
  if (subcategoriaBD && opciones.includes(subcategoriaBD)) {
    subcatSelect.value = subcategoriaBD;
  } else if (opciones.includes(prevValue)) {
    subcatSelect.value = prevValue;
  } else {
    subcatSelect.value = '';
  }
}

// Listener para el campo NOMBRE
function agregarListenerNombreCategoria(rowElement) {
  const nombreInput = rowElement.querySelector('input.campo-formulario[data-campo-nombre="NOMBRE"]');
  const categoriaSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="CATEGORIAS"]');
  if (!nombreInput || !categoriaSelect) return;

  nombreInput.addEventListener('input', async function () {
    const categoria = obtenerCategoriaPorNombre(nombreInput.value);
    if (categoria && categoriaSelect.value !== categoria) {
      categoriaSelect.value = categoria;
      categoriaSelect.dispatchEvent(new Event('change', { bubbles: true }));
    }
  });
}

// Listener para el campo CATEGORIAS
function agregarListenerCategoria(rowElement) {
  const categoriaSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="CATEGORIAS"]');
  if (!categoriaSelect) return;

  categoriaSelect.addEventListener('change', async function () {
    // Ocultar columnas según la categoría
    onCategoriaChangeGlobal();

    // Actualizar subcategorías
    await actualizarSubcategorias(rowElement, categoriaSelect.value);
  });
}

// Inicializa la lógica de categoría/subcategoría/ocultamiento en una fila
function inicializarCategoriaSubcategoriaFila(rowElement, subcategoriaBD = null) {
  agregarListenerNombreCategoria(rowElement);
  agregarListenerCategoria(rowElement);

  // Si ya hay un valor en NOMBRE, autocompleta la categoría
  const nombreInput = rowElement.querySelector('input.campo-formulario[data-campo-nombre="NOMBRE"]');
  const categoriaSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="CATEGORIAS"]');
  if (nombreInput && categoriaSelect) {
    const categoria = obtenerCategoriaPorNombre(nombreInput.value);
    if (categoria && categoriaSelect.value !== categoria) {
      categoriaSelect.value = categoria;
    }
    onCategoriaChangeGlobal();
  }

  // Si hay una subcategoría de la BD, intenta seleccionarla
  if (categoriaSelect) {
    actualizarSubcategorias(rowElement, categoriaSelect.value, subcategoriaBD);
  }
}

// Hook global para inicializar en cada fila (llámalo desde initializeRowFields)
window.inicializarCategoriaSubcategoriaFila = inicializarCategoriaSubcategoriaFila;

// --- Integración con la carga de borradores ---
if (typeof deserializarTablaCargaManual === 'function') {
  const originalDeserializar = deserializarTablaCargaManual;
  window.deserializarTablaCargaManual = async function (datos) {
    originalDeserializar(datos);
    const filas = document.querySelectorAll('#skuTable tbody .fila-carga');
    // Procesa cada fila secuencialmente para asegurar el orden correcto
    for (let idx = 0; idx < filas.length; idx++) {
      const row = filas[idx];
      const filaData = datos[idx] || {};
      await inicializarFilaConDependencias(row, filaData);
    }
  };
}

/**
 * Inicializa una fila respetando dependencias entre campos (padre -> dependiente).
 * @param {HTMLElement} rowElement
 * @param {Object} filaData
 */
async function inicializarFilaConDependencias(rowElement, filaData) {
  // 1. Asigna primero los campos padres (ej: CATEGORIAS)
  const categoriaSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="CATEGORIAS"]');
  if (categoriaSelect && filaData['CATEGORIAS']) {
    categoriaSelect.value = filaData['CATEGORIAS'];
    // Dispara el evento change para actualizar dependientes
    categoriaSelect.dispatchEvent(new Event('change', { bubbles: true }));
    // Espera a que el fetch de subcategorías termine (puedes usar un pequeño delay o mejor, un callback/promise si tu función lo soporta)
    await new Promise(resolve => setTimeout(resolve, 200));
  }

  // 2. Asigna los campos dependientes (ej: SUBCATEGORIAS)
  const subcatSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="SUBCATEGORIAS"]');
  if (subcatSelect && filaData['SUBCATEGORIAS']) {
    // Espera a que las opciones estén listas
    let intentos = 0;
    while (subcatSelect.options.length <= 1 && intentos < 10) { // 1 = solo "Seleccione"
      await new Promise(resolve => setTimeout(resolve, 100));
      intentos++;
    }
    subcatSelect.value = filaData['SUBCATEGORIAS'];
  }

  // 3. Asigna el resto de campos (los que no son dependientes)
  for (const [nombre, valor] of Object.entries(filaData)) {
    if (nombre === 'CATEGORIAS' || nombre === 'SUBCATEGORIAS') continue;
    const field = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
    if (field) {
      if (field.tagName === 'SELECT') {
        // Si el valor no está en las opciones, lo agrega temporalmente
        let found = false;
        for (let i = 0; i < field.options.length; i++) {
          if (field.options[i].value == valor) {
            found = true;
            break;
          }
        }
        if (!found && valor !== "" && valor !== null && valor !== undefined) {
          const opt = document.createElement('option');
          opt.value = valor;
          opt.textContent = valor;
          field.appendChild(opt);
        }
        field.value = valor;
      } else if (field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
        field.value = valor;
      } else if (field.tagName === 'TD') {
        field.textContent = valor;
      }
    }
  }
}

/**
 * Inicializa la fila y asegura que los valores condicionales se asignen después de llenar los selects dinámicos.
 * @param {HTMLElement} rowElement
 * @param {Object} filaData
 */
async function inicializarFilaConValores(rowElement, filaData) {
  // 1. Inicializa listeners y lógica de categoría/subcategoría
  const subcat = filaData['SUBCATEGORIAS'] ? filaData['SUBCATEGORIAS'] : null;
  inicializarCategoriaSubcategoriaFila(rowElement, subcat);

  // 2. Autocompleta categoría si aplica
  const nombreInput = rowElement.querySelector('input.campo-formulario[data-campo-nombre="NOMBRE"]');
  const categoriaSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="CATEGORIAS"]');
  if (nombreInput && categoriaSelect) {
    const categoria = obtenerCategoriaPorNombre(nombreInput.value);
    if (categoria && categoriaSelect.value !== categoria) {
      categoriaSelect.value = categoria;
      categoriaSelect.dispatchEvent(new Event('change', { bubbles: true }));
    } else {
      onCategoriaChangeGlobal();
      await actualizarSubcategorias(rowElement, categoriaSelect.value, subcat);
    }
  }

  // 3. Espera a que los selects condicionales estén llenos antes de asignar valores
  //    (esto es importante para selects dependientes de la categoría)
  //    Si tienes más selects dependientes, puedes agregar lógica similar aquí.
  await new Promise(resolve => setTimeout(resolve, 50)); // Espera breve para asegurar DOM actualizado

  // 4. Asigna los valores a todos los campos (incluso los condicionales)
  asignarValoresAFila(rowElement, filaData);
}

// --- Integración con la inicialización de filas nuevas ---
if (typeof initializeRowFields === 'function') {
  const originalInitRowFields = initializeRowFields;
  window.initializeRowFields = function (rowElement) {
    originalInitRowFields(rowElement);
    inicializarCategoriaSubcategoriaFila(rowElement);

    // Si hay datos previos en la fila (por ejemplo, en edición), asignar valores
    if (rowElement.dataset && rowElement.dataset.filaData) {
      try {
        const filaData = JSON.parse(rowElement.dataset.filaData);
        inicializarFilaConValores(rowElement, filaData);
      } catch (e) {}
    }
  };
}