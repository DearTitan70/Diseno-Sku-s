/**
 * Mapeo de palabras clave a categorías válidas.
 * Cada objeto relaciona una palabra clave que puede aparecer en el nombre del producto
 * con la categoría correspondiente que debe seleccionarse en el formulario.
 */
const nombreCategoriaMap = [
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

/**
 * Filtra las opciones del select de CATEGORIAS según el valor de NOMBRE.
 * @param {HTMLInputElement} nombreInput - El input de NOMBRE en la fila.
 */
function filtrarCategoriasPorNombre(nombreInput) {
  // Busca la fila (row) que contiene el input de nombre
  const row = nombreInput.closest(".fila-carga");
  if (!row) return;

  // Busca el select de categorías dentro de la misma fila
  const categoriaSelect = row.querySelector(
    'select.campo-formulario[data-campo-nombre="CATEGORIAS"]'
  );
  if (!categoriaSelect) return;

  // Obtiene el valor del input de nombre, lo limpia y lo convierte a mayúsculas
  const nombreValor = nombreInput.value.trim().toUpperCase();

  // Busca las categorías que coinciden con alguna palabra clave en el nombre
  let categoriasFiltradas = [];
  for (const map of nombreCategoriaMap) {
    if (nombreValor.includes(map.keyword)) {
      categoriasFiltradas.push(map.categoria);
    }
  }

  // Si no hay coincidencias, restaura todas las opciones originales del select
  if (categoriasFiltradas.length === 0) {
    restaurarOpcionesCategorias(categoriaSelect);
    categoriaSelect.disabled = false;
    // Actualiza las columnas visibles según la categoría actual (puede estar vacía)
    if (typeof actualizarColumnasPorCategoria === "function") {
      actualizarColumnasPorCategoria(categoriaSelect.value);
    }
    return;
  }

  // Obtiene las opciones originales del select (solo la primera vez)
  const opcionesOriginales = categoriaSelect.dataset.opcionesOriginales
    ? JSON.parse(categoriaSelect.dataset.opcionesOriginales)
    : Array.from(categoriaSelect.options).map((opt) => ({
        value: opt.value,
        text: opt.text,
      }));

  // Guarda las opciones originales en un atributo data si aún no están guardadas
  if (!categoriaSelect.dataset.opcionesOriginales) {
    categoriaSelect.dataset.opcionesOriginales =
      JSON.stringify(opcionesOriginales);
  }

  // Limpia el select y agrega solo las opciones filtradas
  categoriaSelect.innerHTML = "";

  // Siempre agrega la opción "Seleccione" si existe entre las originales
  const opcionSeleccione = opcionesOriginales.find((opt) => opt.value === "");
  if (opcionSeleccione) {
    const opt = document.createElement("option");
    opt.value = opcionSeleccione.value;
    opt.text = opcionSeleccione.text;
    categoriaSelect.appendChild(opt);
  }

  // Agrega solo las opciones que coinciden con las categorías filtradas
  opcionesOriginales.forEach((opt) => {
    if (categoriasFiltradas.includes(opt.value)) {
      const option = document.createElement("option");
      option.value = opt.value;
      option.text = opt.text;
      categoriaSelect.appendChild(option);
    }
  });

  // Si solo hay una categoría válida, la selecciona automáticamente y deshabilita el select
  if (categoriasFiltradas.length === 1) {
    categoriaSelect.value = categoriasFiltradas[0];
    categoriaSelect.disabled = true;

    // Dispara el evento 'change' para que otros scripts puedan reaccionar al cambio
    const event = new Event("change", { bubbles: true });
    categoriaSelect.dispatchEvent(event);

    // Si existe la función para filtrar tallas, la llama para actualizar las tallas disponibles
    if (typeof filtrarOpcionesTallasMultiSelectPorCategoria === "function") {
      filtrarOpcionesTallasMultiSelectPorCategoria(row);
    }
  } else {
    // Si hay varias opciones, habilita el select para que el usuario elija
    categoriaSelect.disabled = false;
    // Actualiza las columnas visibles según la opción actual seleccionada
    if (typeof actualizarColumnasPorCategoria === "function") {
      actualizarColumnasPorCategoria(categoriaSelect.value);
    }
  }
}

/**
 * Restaura las opciones originales del select de CATEGORIAS.
 * @param {HTMLSelectElement} categoriaSelect - El select de categorías a restaurar.
 */
function restaurarOpcionesCategorias(categoriaSelect) {
  // Si no hay opciones originales guardadas, no hace nada
  if (!categoriaSelect.dataset.opcionesOriginales) return;

  // Recupera las opciones originales y las vuelve a agregar al select
  const opcionesOriginales = JSON.parse(
    categoriaSelect.dataset.opcionesOriginales
  );
  categoriaSelect.innerHTML = "";
  opcionesOriginales.forEach((opt) => {
    const option = document.createElement("option");
    option.value = opt.value;
    option.text = opt.text;
    categoriaSelect.appendChild(option);
  });

  // Restaura el valor y habilita el select
  categoriaSelect.value = "";
  categoriaSelect.disabled = false;

  // Actualiza las columnas visibles según la categoría actual (vacía)
  if (typeof actualizarColumnasPorCategoria === "function") {
    actualizarColumnasPorCategoria(categoriaSelect.value);
  }
}

// --- Delegación de eventos para todas las filas, incluso las que se agregan dinámicamente ---
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona el cuerpo de la tabla donde están las filas de SKU
  const skuTableBody = document.querySelector("#skuTable tbody");
  if (!skuTableBody) return;

  // Escucha eventos de entrada (input) en el cuerpo de la tabla
  skuTableBody.addEventListener("input", function (e) {
    const target = e.target;
    // Si el input modificado es el de NOMBRE, filtra las categorías correspondientes
    if (target.matches('input.campo-formulario[data-campo-nombre="NOMBRE"]')) {
      filtrarCategoriasPorNombre(target);
    }
  });
});
