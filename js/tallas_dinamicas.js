/**
 * Listado de tallas para prendas superiores (ej: camisetas, chaquetas, etc.)
 */
const TALLAS_SUPERIOR = [
  { value: "XS", text: "XS" },
  { value: "S", text: "S" },
  { value: "M", text: "M" },
  { value: "L", text: "L" },
  { value: "XL", text: "XL" },
  { value: "XXL", text: "XXL" },
];

/**
 * Listado de tallas para prendas inferiores (ej: pantalones, faldas, etc.)
 */
const TALLAS_INFERIOR = [
  { value: "26", text: "26" },
  { value: "28", text: "28" },
  { value: "30", text: "30" },
  { value: "32", text: "32" },
  { value: "34", text: "34" },
  { value: "36", text: "36" },
];

/**
 * Listado de talla única (para prendas que no tienen tallas convencionales)
 */
const TALLAS_UNICA = [{ value: "UN", text: "UN" }];

/**
 * Categorías de prendas consideradas como superiores.
 * Si la categoría seleccionada está en este listado, se mostrarán las tallas superiores.
 */
const CATEGORIAS_SUPERIOR = [
  "CHAQUETAS",
  "CAMISETAS",
  "BLUSAS",
  "CAMISAS",
  "PUNTO",
  "FELPA",
];

/**
 * Categorías de prendas consideradas como inferiores.
 * Si la categoría seleccionada está en este listado, se mostrarán las tallas inferiores.
 */
const CATEGORIAS_INFERIOR = ["FALDAS", "PANTALONES", "JEANS"];

/**
 * Actualiza el select de tallas según la categoría seleccionada en la fila.
 *
 * @param {HTMLElement} rowElement - Elemento de la fila que contiene los selects de categoría y talla.
 */
function actualizarTallasPorCategoria(rowElement) {
  // Buscar el select de categoría y el select de tallas dentro de la fila
  const categoriaSelect = rowElement.querySelector(
    'select.campo-formulario[data-campo-nombre="CATEGORIAS"]'
  );
  const tallasSelect = rowElement.querySelector(
    'select.campo-formulario[data-campo-nombre="TALLAS"]'
  );
  if (!categoriaSelect || !tallasSelect) return; // Si no existen, salir

  // Obtener la categoría seleccionada
  const categoria = categoriaSelect.value;
  let opciones = [];

  // Determinar qué listado de tallas mostrar según la categoría
  if (CATEGORIAS_SUPERIOR.includes(categoria)) {
    opciones = TALLAS_SUPERIOR;
  } else if (CATEGORIAS_INFERIOR.includes(categoria)) {
    opciones = TALLAS_INFERIOR;
  } else {
    opciones = TALLAS_UNICA;
  }

  // Guardar el valor actual del select de tallas para intentar mantenerlo si sigue siendo válido
  const valorActual = tallasSelect.value;

  // Limpiar las opciones actuales y agregar la opción por defecto
  tallasSelect.innerHTML = '<option value="">Seleccione</option>';

  // Agregar las nuevas opciones de talla según la categoría
  opciones.forEach((opt) => {
    const option = document.createElement("option");
    option.value = opt.value;
    option.textContent = opt.text;
    tallasSelect.appendChild(option);
  });

  // Si el valor anterior sigue siendo válido, restaurarlo; si no, dejar el select vacío
  if (opciones.some((opt) => opt.value === valorActual)) {
    tallasSelect.value = valorActual;
  } else {
    tallasSelect.value = "";
  }
}

/**
 * Agrega el listener al select de categoría para que, al cambiar, se actualicen las tallas.
 * Además, ejecuta la actualización una vez al cargar la fila.
 *
 * @param {HTMLElement} rowElement - Elemento de la fila que contiene los selects.
 */
function agregarListenerCategoriaTallas(rowElement) {
  // Buscar el select de categoría dentro de la fila
  const categoriaSelect = rowElement.querySelector(
    'select.campo-formulario[data-campo-nombre="CATEGORIAS"]'
  );
  if (!categoriaSelect) return; // Si no existe, salir

  // Agregar el evento 'change' para actualizar las tallas cuando cambie la categoría
  categoriaSelect.addEventListener("change", function () {
    actualizarTallasPorCategoria(rowElement);
  });

  // Ejecutar la actualización de tallas al cargar la fila (por si ya hay una categoría seleccionada)
  actualizarTallasPorCategoria(rowElement);
}
