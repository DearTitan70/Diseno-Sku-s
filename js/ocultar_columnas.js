// Diccionario de columnas a ocultar por categoría
const columnasOcultasPorCategoria = {
  BLUSAS: [
    "DISENO", "PUNO", "CAPOTA", "TIRO", "BOTA", "CINTURA", "SILUETA", "CIERRE", "GALGA", "TIPO_GALGA",
    "FORRO", "%_FORRO_1", "%_FORRO_2", "TOT_FORRO", "COMP_FORRO_1", "COMP_FORRO_2",
    "RELLENO", "%_RELLENO_1", "%_RELLENO_2", "TOT_RELLENO", "COMP_RELLENO_1", "COMP_RELLENO_2"
  ],
  CAMISAS: [
    "DISENO",
    "TIPO_MANGA",
    "PUNO",
    "CAPOTA",
    "ESCOTE",
    "CUELLO",
    "TIRO",
    "BOTA",
    "CINTURA",
    "SILUETA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  CAMISETAS: [
    "DISENO",
    "CAPOTA",
    "TIRO",
    "BOTA",
    "CINTURA",
    "SILUETA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  PUNTO: [
    "DISENO",
    "PUNO",
    "ESCOTE",
    "TIRO",
    "BOTA",
    "CINTURA",
    "SILUETA",
    "CIERRE",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  FELPA: [
    "DISENO",
    "TIPO_MANGA",
    "PUNO",
    "ESCOTE",
    "LARGO",
    "CUELLO",
    "TIRO",
    "BOTA",
    "CINTURA",
    "SILUETA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  CHAQUETAS: [
    "TIPO_MANGA",
    "PUNO",
    "ESCOTE",
    "TIRO",
    "BOTA",
    "CINTURA",
    "SILUETA",
    "GALGA",
    "TIPO_GALGA",
  ],
  VESTIDOS: [
    "DISENO",
    "PUNO",
    "CAPOTA",
    "LARGO",
    "CUELLO",
    "TIRO",
    "BOTA",
    "CINTURA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  FALDAS: [
    "MANGA",
    "TIPO_MANGA",
    "PUNO",
    "CAPOTA",
    "ESCOTE",
    "LARGO",
    "CUELLO",
    "TIRO",
    "BOTA",
    "SILUETA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  JEANS: [
    "MANGA",
    "TIPO_MANGA",
    "PUNO",
    "CAPOTA",
    "ESCOTE",
    "LARGO",
    "CUELLO",
    "CINTURA",
    "SILUETA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
  PANTALONES: [
    "DISENO",
    "MANGA",
    "TIPO_MANGA",
    "PUNO",
    "CAPOTA",
    "ESCOTE",
    "LARGO",
    "CUELLO",
    "CINTURA",
    "SILUETA",
    "CIERRE",
    "GALGA",
    "TIPO_GALGA",
    "FORRO",
    "%_FORRO_1",
    "%_FORRO_2",
    "TOT_FORRO",
    "COMP_FORRO_1",
    "COMP_FORRO_2",
    "RELLENO",
    "%_RELLENO_1",
    "%_RELLENO_2",
    "TOT_RELLENO",
    "COMP_RELLENO_1",
    "COMP_RELLENO_2",
  ],
};

// Mapea data-campo-nombre a índice de columna
function obtenerIndicesColumnasPorCampo() {
  const indices = {};
  document.querySelectorAll("#skuTable thead th").forEach((th, idx) => {
    const campo = th.getAttribute("data-campo-nombre");
    if (campo) indices[campo] = idx;
  });
  return indices;
}

// Muestra todas las columnas
function mostrarTodasLasColumnas() {
  document.querySelectorAll("#skuTable thead th").forEach((th) => th.style.display = "");
  document.querySelectorAll("#skuTable tbody tr").forEach((tr) => {
    tr.querySelectorAll("td").forEach((td) => td.style.display = "");
  });
}

// Oculta columnas por categoría
function actualizarColumnasPorCategoria(categoria) {
  mostrarTodasLasColumnas();
  const camposOcultar = columnasOcultasPorCategoria[categoria];
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
  // Después de ocultar columnas, también actualiza PRINT
  ocultarPrintSiColorFdsNoEs999();
}

/**
 * Muestra u oculta la columna PRINT (header y todas las celdas) según si
 * al menos una fila tiene COLOR_FDS = 999.
 * Si alguna fila tiene 999, se muestra PRINT para todas las filas.
 * Si ninguna fila tiene 999, se oculta PRINT para todas las filas.
 */
function ocultarPrintSiColorFdsNoEs999() {
  const indices = obtenerIndicesColumnasPorCampo();
  const idxColorFds = indices["COLOR_FDS"];
  const idxPrint = indices["PRINT"];
  if (typeof idxColorFds === "undefined" || typeof idxPrint === "undefined") return;

  const thPrint = document.querySelector(`#skuTable thead th:nth-child(${idxPrint + 1})`);
  if (!thPrint) return;

  // ¿Hay al menos una fila con COLOR_FDS = 999?
  let mostrarPrint = false;
  document.querySelectorAll("#skuTable tbody tr").forEach((tr) => {
    const tdColorFds = tr.querySelector(`td:nth-child(${idxColorFds + 1})`);
    if (tdColorFds) {
      let valorColorFds = "";
      const input = tdColorFds.querySelector("input, select");
      if (input) {
        valorColorFds = input.value.trim();
      } else {
        valorColorFds = tdColorFds.textContent.trim();
      }
      if (valorColorFds === "999") {
        mostrarPrint = true;
      }
    }
  });

  // Mostrar/ocultar header PRINT
  thPrint.style.display = mostrarPrint ? "" : "none";

  // Mostrar/ocultar todas las celdas PRINT en todas las filas
  document.querySelectorAll("#skuTable tbody tr").forEach((tr) => {
    const tdPrint = tr.querySelector(`td:nth-child(${idxPrint + 1})`);
    if (tdPrint) {
      tdPrint.style.display = mostrarPrint ? "" : "none";
    }
  });
}

// Hook principal
function inicializarOcultamientoColumnas() {
  // Asegura que los th tengan data-campo-nombre
  document.querySelectorAll("#skuTable thead th").forEach((th, idx) => {
    if (!th.hasAttribute("data-campo-nombre")) {
      const td = document.querySelector(`#skuTable tbody tr td:nth-child(${idx + 1})`);
      if (td && td.classList.contains("campo-formulario") && td.dataset.campoNombre) {
        th.setAttribute("data-campo-nombre", td.dataset.campoNombre);
      }
    }
  });

  // Evento para cambio de categoría
  document.querySelector("#skuTable").addEventListener("change", function (e) {
    if (e.target.classList.contains("campo-formulario") && e.target.dataset.campoNombre === "CATEGORIAS") {
      actualizarColumnasPorCategoria(e.target.value);
    }
    if (e.target.classList.contains("campo-formulario") && e.target.dataset.campoNombre === "COLOR_FDS") {
      ocultarPrintSiColorFdsNoEs999();
    }
  });

  // Al cargar, ajusta columnas según la categoría de la primera fila
  const primeraCategoria = document.querySelector(
    '#skuTable tbody tr .campo-formulario[data-campo-nombre="CATEGORIAS"]'
  );
  if (primeraCategoria) {
    actualizarColumnasPorCategoria(primeraCategoria.value);
  }
}

// Llama al hook cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", inicializarOcultamientoColumnas);