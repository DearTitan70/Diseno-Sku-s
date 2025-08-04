const CAMPOS_OBLIGATORIOS = [ "tipo", "LINEA", "usuario", "fecha_creacion", "YEAR", "TOT_COMP", "TIPO_TEJIDO", "TIPO_DE_FIBRA", "TIENDA", "TEMPORADA", "TALLAS", "SUBCATEGORIAS", "PROVEEDOR", "OCASION_DE_USO", "NOM_COLOR", "NOMBRE", "MODULO", "MES", "GRUPO", "GAMA", "DESCRIPCION", "COLOR_FDS", "CLUSTER", "CLIMA", "CLASIFICACION", "CATEGORIAS", "CAPSULA", "BASE_TEXTIL", "%_COMP_1", "COMPOSICION_1"
];

const TALLAS_SUP = ["XXS", "XS", "S", "M", "L", "XL", "XXL", "UN", "T", "EP", "P", "G", "EG"];
const TALLAS_INF = ["2", "4", "6", "8", "10", "12", "14", "16", "28", "30", "32", "34", "36", "UN"];
const TALLAS_UN = ["UN"];

const CATEGORIAS_SUP = [
  "CHAQUETAS",
  "CAMISETAS",
  "BLUSAS",
  "CAMISAS",
  "PUNTO",
  "FELPA",
  "VESTIDOS",
];
const CATEGORIAS_INF = ["FALDAS", "PANTALONES", "JEANS"];

// --- COLORES DISPONIBLES ---
const COLORES_FDS = [
  { codigo: "100", nombre: "BLANCO", gama: "BLANCO" },
  { codigo: "101", nombre: "OFFWHITE", gama: "BLANCO" },
  { codigo: "102", nombre: "IVORY", gama: "BLANCO" },
  { codigo: "103", nombre: "IVORY", gama: "BLANCO" },
  { codigo: "109", nombre: "BEIGE", gama: "BEIGE" },
  { codigo: "121", nombre: "ARENA", gama: "BEIGE" },
  { codigo: "123", nombre: "KAKI", gama: "BEIGE" },
  { codigo: "203", nombre: "AMARILLO CLARO", gama: "AMARILLO" },
  { codigo: "204", nombre: "AMARILLO", gama: "AMARILLO" },
  { codigo: "205", nombre: "AMARILLO MEDIO", gama: "AMARILLO" },
  { codigo: "207", nombre: "LIMA", gama: "AMARILLO" },
  { codigo: "208", nombre: "DORADO", gama: "AMARILLO" },
  { codigo: "209", nombre: "AMARILLO QUEMADO", gama: "AMARILLO" },
  { codigo: "218", nombre: "AMARILLO QUEMADO", gama: "AMARILLO" },
  { codigo: "220", nombre: "TIERRA", gama: "AMARILLO" },
  { codigo: "224", nombre: "FLUORECENTE", gama: "AMARILLO" },
  { codigo: "258", nombre: "NARANJA", gama: "NARANJA" },
  { codigo: "260", nombre: "NARANJA CLARO", gama: "NARANJA" },
  { codigo: "263", nombre: "CORAL", gama: "NARANJA" },
  { codigo: "264", nombre: "CORAL", gama: "NARANJA" },
  { codigo: "266", nombre: "NARANJA", gama: "NARANJA" },
  { codigo: "270", nombre: "TERRACOTA", gama: "NARANJA" },
  { codigo: "275", nombre: "TERRACOTA", gama: "NARANJA" },
  { codigo: "276", nombre: "NARANJA PICANTE", gama: "NARANJA" },
  { codigo: "277", nombre: "PEACH", gama: "NARANJA" },
  { codigo: "279", nombre: "TERRACOTA", gama: "NARANJA" },
  { codigo: "281", nombre: "PEACH", gama: "NARANJA" },
  { codigo: "283", nombre: "TERRACOTA", gama: "NARANJA" },
  { codigo: "284", nombre: "MANDARINA", gama: "NARANJA" },
  { codigo: "300", nombre: "ROJO", gama: "ROJO" },
  { codigo: "312", nombre: "CEREZA", gama: "ROJO" },
  { codigo: "313", nombre: "ROJO", gama: "ROJO" },
  { codigo: "315", nombre: "ROJO", gama: "ROJO" },
  { codigo: "318", nombre: "ROJO PIMIENTO", gama: "ROJO" },
  { codigo: "319", nombre: "ROJO", gama: "ROJO" },
  { codigo: "322", nombre: "VINO", gama: "ROJO" },
  { codigo: "328", nombre: "VINO", gama: "ROJO" },
  { codigo: "330", nombre: "VINO", gama: "ROJO" },
  { codigo: "337", nombre: "BURGUNDY", gama: "ROJO" },
  { codigo: "350", nombre: "FUCCIA", gama: "ROSADO" },
  { codigo: "353", nombre: "FRESA", gama: "ROSADO" },
  { codigo: "354", nombre: "ROSADO", gama: "ROSADO" },
  { codigo: "356", nombre: "ROSADO", gama: "ROSADO" },
  { codigo: "357", nombre: "ROSADO", gama: "ROSADO" },
  { codigo: "358", nombre: "ROSADO CARAMELO", gama: "ROSADO" },
  { codigo: "361", nombre: "ROSA MARCHITA", gama: "ROSADO" },
  { codigo: "362", nombre: "ROSA MARCHITA", gama: "ROSADO" },
  { codigo: "363", nombre: "ROSA MARCHITA", gama: "ROSADO" },
  { codigo: "365", nombre: "ROSA SANDIA", gama: "ROSADO" },
  { codigo: "366", nombre: "PALO DE ROSA", gama: "ROSADO" },
  { codigo: "367", nombre: "ROSADO", gama: "ROSADO" },
  { codigo: "368", nombre: "BLUSH", gama: "ROSADO" },
  { codigo: "369", nombre: "ROSADO", gama: "ROSADO" },
  { codigo: "370", nombre: "ROSADO", gama: "ROSADO" },
  { codigo: "375", nombre: "FUCSIA", gama: "MAGENTA" },
  { codigo: "377", nombre: "MORA", gama: "MAGENTA" },
  { codigo: "380", nombre: "MAGENTA", gama: "MAGENTA" },
  { codigo: "393", nombre: "ROSA", gama: "ROSADO" },
  { codigo: "399", nombre: "LAVANDA", gama: "MORADO" },
  { codigo: "401", nombre: "VIOLETA", gama: "MORADO" },
  { codigo: "407", nombre: "PURPURA", gama: "MORADO" },
  { codigo: "417", nombre: "LILA", gama: "MORADO" },
  { codigo: "418", nombre: "LILA CLARO", gama: "MORADO" },
  { codigo: "424", nombre: "VIOLETA", gama: "MORADO" },
  { codigo: "431", nombre: "MORADO", gama: "MORADO" },
  { codigo: "452", nombre: "AZUL", gama: "AZUL" },
  { codigo: "453", nombre: "CLARO", gama: "AZUL" },
  { codigo: "454", nombre: "AZUL", gama: "AZUL" },
  { codigo: "459", nombre: "AZUL", gama: "AZUL" },
  { codigo: "460", nombre: "AZUL LAVANDA", gama: "AZUL" },
  { codigo: "462", nombre: "AZUL", gama: "AZUL" },
  { codigo: "463", nombre: "AZUL CIELO", gama: "AZUL" },
  { codigo: "464", nombre: "MEDIO", gama: "AZUL" },
  { codigo: "467", nombre: "AZUL", gama: "AZUL" },
  { codigo: "473", nombre: "ROYAL", gama: "AZUL" },
  { codigo: "475", nombre: "HIELO", gama: "AZUL" },
  { codigo: "476", nombre: "AZUL HORTENSIA", gama: "AZUL" },
  { codigo: "479", nombre: "NAVY", gama: "AZUL" },
  { codigo: "480", nombre: "CLARO", gama: "AZUL" },
  { codigo: "481", nombre: "AZUL", gama: "AZUL" },
  { codigo: "482", nombre: "MEDIO OSC", gama: "AZUL" },
  { codigo: "483", nombre: "MEDIO OSC", gama: "AZUL" },
  { codigo: "484", nombre: "TURQUEZA", gama: "TURQUEZA" },
  { codigo: "490", nombre: "AZUL OSC", gama: "AZUL" },
  { codigo: "491", nombre: "AZUL OSC", gama: "AZUL" },
  { codigo: "503", nombre: "MINT GREEN", gama: "VERDE" },
  { codigo: "504", nombre: "TURQUEZA", gama: "TURQUEZA" },
  { codigo: "505", nombre: "TURQUEZA", gama: "TURQUEZA" },
  { codigo: "510", nombre: "VERDE CALI", gama: "VERDE" },
  { codigo: "513", nombre: "PETROL", gama: "TURQUEZA" },
  { codigo: "515", nombre: "ALPINE GREEN", gama: "TURQUEZA" },
  { codigo: "556", nombre: "VERDE CLARO", gama: "VERDE" },
  { codigo: "565", nombre: "VERDE CLARO", gama: "VERDE" },
  { codigo: "566", nombre: "VERDE LIMA", gama: "VERDE" },
  { codigo: "567", nombre: "VERDE", gama: "VERDE" },
  { codigo: "570", nombre: "VERDE", gama: "VERDE" },
  { codigo: "572", nombre: "VERDE MILITAR", gama: "VERDE" },
  { codigo: "575", nombre: "GREEN TE", gama: "VERDE" },
  { codigo: "576", nombre: "OLIVO", gama: "VERDE" },
  { codigo: "579", nombre: "VERDE OSCURO", gama: "VERDE" },
  { codigo: "581", nombre: "VERDE OLIVA", gama: "VERDE" },
  { codigo: "583", nombre: "JADE", gama: "VERDE" },
  { codigo: "587", nombre: "VERDE LIMON", gama: "VERDE" },
  { codigo: "588", nombre: "VERDE LIMON", gama: "VERDE" },
  { codigo: "592", nombre: "VERDE CHIVE", gama: "VERDE" },
  { codigo: "596", nombre: "OLIVO", gama: "VERDE" },
  { codigo: "597", nombre: "VERDE MILITAR", gama: "VERDE" },
  { codigo: "605", nombre: "CAQUI", gama: "CAFE" },
  { codigo: "606", nombre: "CAQUI", gama: "CAFE" },
  { codigo: "608", nombre: "CAQUI", gama: "CAFE" },
  { codigo: "609", nombre: "MOCCA", gama: "CAFE" },
  { codigo: "611", nombre: "CARAMELO", gama: "CAFE" },
  { codigo: "613", nombre: "CAFÉ", gama: "CAFE" },
  { codigo: "614", nombre: "CAFÉ", gama: "CAFE" },
  { codigo: "623", nombre: "CAQUI", gama: "CAFE" },
  { codigo: "624", nombre: "CHOCOLATE", gama: "CAFE" },
  { codigo: "625", nombre: "CAQUI", gama: "CAFE" },
  { codigo: "626", nombre: "TAUPE", gama: "CAFE" },
  { codigo: "627", nombre: "COFFE", gama: "CAFE" },
  { codigo: "700", nombre: "NEGRO", gama: "NEGRO" },
  { codigo: "701", nombre: "CAVIAR", gama: "NEGRO" },
  { codigo: "803", nombre: "GRIS CLARO", gama: "NEGRO" },
  { codigo: "811", nombre: "GRIS MEDIO", gama: "NEGRO" },
  { codigo: "815", nombre: "GRIS MEDIO", gama: "NEGRO" },
  { codigo: "819", nombre: "GRIS OSCURO", gama: "NEGRO" },
  { codigo: "821", nombre: "GRIS OSCURO", gama: "NEGRO" },
  { codigo: "999", nombre: "MULTICOLOR", gama: "MULTICOLOR" }
];


// --- MAPAS PARA ACCESO RÁPIDO ---
const MAPA_NOMBRE_COLOR = {};
const MAPA_GAMA_COLOR = {};
COLORES_FDS.forEach(c => {
  MAPA_NOMBRE_COLOR[c.codigo] = c.nombre;
  MAPA_GAMA_COLOR[c.codigo] = c.gama;
});

function actualizarBotonesEliminar() {
  const filas = document.querySelectorAll(".fila-carga");
  filas.forEach((fila, idx) => {
    const celdas = fila.querySelectorAll("td");
    const celdaAcciones = celdas[celdas.length - 1];
    if (celdaAcciones) {
      const btnExistente = celdaAcciones.querySelector(".delete-row");
      if (btnExistente) btnExistente.remove();
    }
    if (idx === 0) return;
    if (celdaAcciones && !celdaAcciones.querySelector(".delete-row")) {
      const deleteBtn = document.createElement("button");
      deleteBtn.type = "button";
      deleteBtn.className = "delete-row btn-eliminar";
      deleteBtn.textContent = "Eliminar";
      celdaAcciones.style.textAlign = "center";
      deleteBtn.onclick = function (event) {
        event.preventDefault();
        fila.style.transition = "opacity 0.3s, transform 0.3s";
        fila.style.opacity = "0";
        fila.style.transform = "translateX(20px)";
        setTimeout(() => {
          fila.remove();
          actualizarBotonesEliminar();
        }, 300);
      };
      celdaAcciones.appendChild(deleteBtn);
    }
  });
}

function filtrarOpcionesTallasMultiSelectPorCategoria(fila) {
  const categoriaSelect = fila.querySelector(
    '.campo-formulario[data-campo-nombre="CATEGORIAS"]'
  );
  const multiSelect = document.getElementById("multiTallas");
  if (!categoriaSelect || !multiSelect) return;
  const categoria = categoriaSelect.value;
  let opciones = [];
  if (CATEGORIAS_SUP.includes(categoria)) {
    opciones = TALLAS_SUP;
  } else if (CATEGORIAS_INF.includes(categoria)) {
    opciones = TALLAS_INF;
  } else {
    opciones = TALLAS_UN;
  }
  const seleccionadas = Array.from(multiSelect.selectedOptions).map(
    (opt) => opt.value
  );
  multiSelect.innerHTML = "";
  opciones.forEach((talla) => {
    const opt = document.createElement("option");
    opt.value = talla;
    opt.textContent = talla;
    if (seleccionadas.includes(talla)) opt.selected = true;
    multiSelect.appendChild(opt);
  });
}

function validarCamposObligatoriosFila(fila) {
  let validos = true;
  let primerCampoFaltante = null;
  CAMPOS_OBLIGATORIOS.forEach((nombreCampo) => {
    const campo = fila.querySelector(
      `.campo-formulario[data-campo-nombre="${nombreCampo}"]`
    );
    if (!campo) return;
    let valor = "";
    if (
      campo.tagName === "SELECT" ||
      campo.tagName === "INPUT" ||
      campo.tagName === "TEXTAREA"
    ) {
      valor = campo.value;
    } else {
      valor = campo.textContent.trim();
    }
    if (!valor || valor === "-" || valor === "Seleccione") {
      validos = false;
      if (!primerCampoFaltante) primerCampoFaltante = nombreCampo;
      campo.style.border = "2px solid var(--color-error)";
      campo.style.transition = "border 0.3s ease";
      const evento = campo.tagName === "SELECT" ? "change" : "input";
      campo.addEventListener(evento, function limpiarBorde() {
        if (
          campo.value &&
          campo.value !== "-" &&
          campo.value !== "Seleccione"
        ) {
          campo.style.border = "";
          campo.removeEventListener(evento, limpiarBorde);
        }
      });
    } else {
      campo.style.border = "";
    }
  });
  if (!validos && primerCampoFaltante) {
    mostrarMensaje(
      "Por favor diligencie todos los campos obligatorios antes de replicar filas. Falta: " +
        primerCampoFaltante,
      "error"
    );
  }
  return validos;
}

async function copiarValoresCampos(filaOrigen, filaDestino, campoExcluir) {
    const camposOrigen = filaOrigen.querySelectorAll(
        ".campo-formulario[data-campo-nombre]"
    );
    const promesas = [];
    
    camposOrigen.forEach((campoOrigen) => {
        const nombreCampo = campoOrigen.getAttribute("data-campo-nombre");
        if (nombreCampo === campoExcluir) return;
        const campoDestino = filaDestino.querySelector(
            `.campo-formulario[data-campo-nombre="${nombreCampo}"]`
        );
        if (!campoDestino) return;
        
        const tag = campoOrigen.tagName.toUpperCase();
        if (tag === "INPUT" || tag === "TEXTAREA") {
            campoDestino.value = campoOrigen.value;
        } else if (tag === "SELECT") {
            const tipoCampo = campoDestino.getAttribute("data-campo-type");
            const valorOriginal = campoOrigen.value;
            if (tipoCampo === "static" && typeof getStaticOptions === "function") {
                const opciones = getStaticOptions(nombreCampo);
                campoDestino.innerHTML = "";
                if (Array.isArray(opciones)) {
                    opciones.forEach((opt) => {
                        const optionElement = document.createElement("option");
                        optionElement.value = opt.value;
                        optionElement.textContent = opt.text;
                        campoDestino.appendChild(optionElement);
                    });
                }
                campoDestino.value = valorOriginal || "";
            } else if (
                tipoCampo === "dependent" &&
                typeof fetchDependentOptionsAndUpdateRow === "function"
            ) {
                const fila = campoDestino.closest(".fila-carga");
                if (fila) {
                    const prom = new Promise((resolve) => {
                        fetchDependentOptionsAndUpdateRow(fila).then(() => {
                            campoDestino.value = valorOriginal || "";
                            resolve();
                        });
                    });
                    promesas.push(prom);
                }
            } else {
                campoDestino.value = valorOriginal || "";
            }
        }
    });
    
    if (promesas.length > 0) {
        await Promise.all(promesas);
    }
    
    // Inicializar validaciones Print/Multicolor en la nueva fila
    if (typeof inicializarValidacionPrintMulticolor === "function") {
        inicializarValidacionPrintMulticolor(filaDestino);
    }
}

function mostrarMensaje(texto, tipo = "success") {
  const mensajeExistente = document.querySelector(".mensaje-flotante");
  if (mensajeExistente) mensajeExistente.remove();
  const mensaje = document.createElement("div");
  mensaje.className = `mensaje-flotante mensaje-${tipo}`;
  mensaje.textContent = texto;
  document.body.appendChild(mensaje);
  setTimeout(() => {
    mensaje.style.transform = "translateY(0)";
    mensaje.style.opacity = "1";
  }, 10);
  setTimeout(() => {
    mensaje.style.transform = "translateY(-20px)";
    mensaje.style.opacity = "0";
    setTimeout(() => mensaje.remove(), 300);
  }, 3000);
}

// --- NUEVA FUNCIÓN GENERALIZADA PARA REPLICAR POR CAMPO (TALLAS O COLOR) ---
/**
 * Modificación en la función replicarFilasPorCampo para asegurar que el select de tallas
 * tenga las opciones correctas antes de asignar el valor de talla en la fila clonada.
 */
/**
 * ...dentro de la función replicarFilasPorCampo, justo después de llamar a initializeRowFields...
 */
async function replicarFilasPorCampo({
  filasBase,
  campoReplicar,
  valoresReplicar,
  camposExtra = {},
  callbackPostFila = null,
}) {
  const tbody = document.querySelector("#skuTable tbody");
  if (!tbody) return;

  // Construir set de combinaciones existentes para evitar duplicados
  const combinacionesExistentes = new Set();
  tbody.querySelectorAll(".fila-carga").forEach((fila) => {
    const nombre = fila.querySelector('.campo-formulario[data-campo-nombre="NOMBRE"]')?.value || "";
    const categoria = fila.querySelector('.campo-formulario[data-campo-nombre="CATEGORIAS"]')?.value || "";
    const talla = fila.querySelector('.campo-formulario[data-campo-nombre="TALLAS"]')?.value || "";
    const color = fila.querySelector('.campo-formulario[data-campo-nombre="COLOR_FDS"]')?.value || "";
    combinacionesExistentes.add(`${nombre}|${categoria}|${talla}|${color}`);
  });

  let filasAgregadas = 0;
  let insertAfter = filasBase[filasBase.length - 1];

  for (const filaBase of filasBase) {
    const nombre = filaBase.querySelector('.campo-formulario[data-campo-nombre="NOMBRE"]')?.value || "";
    const categoria = filaBase.querySelector('.campo-formulario[data-campo-nombre="CATEGORIAS"]')?.value || "";
    const tallaBase = filaBase.querySelector('.campo-formulario[data-campo-nombre="TALLAS"]')?.value || "";
    const colorBase = filaBase.querySelector('.campo-formulario[data-campo-nombre="COLOR_FDS"]')?.value || "";

    for (const valor of valoresReplicar) {
      // Determinar valores para la combinación
      let talla = tallaBase;
      let color = colorBase;
      if (campoReplicar === "TALLAS") talla = valor;
      if (campoReplicar === "COLOR_FDS") color = valor;

      const key = `${nombre}|${categoria}|${talla}|${color}`;
      if (combinacionesExistentes.has(key)) continue;

      // Clonar fila y modificar campo correspondiente
      const nuevaFila = filaBase.cloneNode(true);
      nuevaFila.classList.add("fila-nueva");
      await copiarValoresCampos(filaBase, nuevaFila, campoReplicar);

      // Modificar campo replicado
      const campoDestino = nuevaFila.querySelector(`.campo-formulario[data-campo-nombre="${campoReplicar}"]`);
      if (campoDestino) campoDestino.value = valor;

      // Modificar campos extra si aplica
      for (const [campo, valores] of Object.entries(camposExtra)) {
        const campoExtra = nuevaFila.querySelector(`.campo-formulario[data-campo-nombre="${campo}"]`);
        if (campoExtra && valores && valores[valor] !== undefined) {
          if (campoExtra.tagName === "TD") {
            campoExtra.textContent = valores[valor];
          } else {
            campoExtra.value = valores[valor];
          }
        }
      }

      // Inicializar campos dependientes y listeners
      if (typeof initializeRowFields === "function") {
        initializeRowFields(nuevaFila);
      }

      // --- FIX: Asegurar que el select de tallas tenga las opciones correctas y el valor esperado ---
      const selectTallas = nuevaFila.querySelector('.campo-formulario[data-campo-nombre="TALLAS"]');
      if (selectTallas) {
        let opcionesTalla = [];
        if (CATEGORIAS_SUP.includes(categoria)) {
          opcionesTalla = TALLAS_SUP;
        } else if (CATEGORIAS_INF.includes(categoria)) {
          opcionesTalla = TALLAS_INF;
        } else {
          opcionesTalla = TALLAS_UN;
        }
        // Limpiar y rellenar opciones
        const prevValue = talla;
        selectTallas.innerHTML = '<option value="">Seleccione</option>';
        opcionesTalla.forEach(opt => {
          const option = document.createElement('option');
          option.value = opt;
          option.textContent = opt;
          selectTallas.appendChild(option);
        });
        // Asignar el valor de talla (ya sea el original o el replicado)
        selectTallas.value = prevValue;
      }

      if (callbackPostFila) callbackPostFila(nuevaFila, valor);

      insertAfter.parentNode.insertBefore(nuevaFila, insertAfter.nextSibling);
      insertAfter = nuevaFila;
      nuevaFila.removeAttribute("data_fila_original");
      filasAgregadas++;
      combinacionesExistentes.add(key);
      await new Promise((resolve) => setTimeout(resolve, 50));
    }
  }
  actualizarBotonesEliminar();
  return filasAgregadas;
}

// --- MODALES Y EVENTOS ---
document.addEventListener("DOMContentLoaded", function () {
  // --- MODAL STYLES ---
  const style = document.createElement("style");
  style.textContent = `
    :root {
      --color-background: #F9F3E5;
      --color-text-dark: #000000;
      --color-primary: #879683;
      --color-secondary: #5A6B58;
      --color-highlight: #C5D4C1;
      --color-logout: #a0a0a0;
      --color-logout-hover: #8a8a8a;
      --color-error: #e74c3c;
      --color-table-header: #879683;
      --color-table-border: #C5D4C1;
      --color-row-even: #f2f2f2;
      --color-delete-button: #c0392b;
      --color-delete-button-hover: #e74c3c;
      --color-white: #ffffff;
      --color-shadow: rgba(0, 0, 0, 0.1);
      --border-radius: 8px;
      --transition-speed: 0.3s;
    }
    .modal-fds {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0; top: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.45);
      align-items: center;
      justify-content: center;
    }
    .modal-fds.active {
      display: flex;
    }
    .modal-content-fds {
      background: var(--color-white);
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.18);
      max-width: 100vw;
      min-width: 320px;
      max-height: 100vh;
      overflow-y: auto;
      position: relative;
      padding: 0;
      animation: fadeInModal 0.4s;
    }
    @keyframes fadeInModal {
      from { opacity: 0; transform: scale(0.98);}
      to { opacity: 1; transform: scale(1);}
    }
    .modal-close-fds {
      position: absolute;
      top: 18px; right: 24px;
      background: transparent;
      border: none;
      font-size: 2.2em;
      color: #888;
      cursor: pointer;
      z-index: 10;
      transition: color 0.2s;
    }
    .modal-close-fds:hover {
      color: #c0392b;
    }
    .replicar-container {
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 350px;
      width: 500px;
      padding: 25px;
      border-radius: var(--border-radius);
      background-color: var(--color-white);
      box-shadow: 0 4px 15px var(--color-shadow);
      transition: transform var(--transition-speed), box-shadow var(--transition-speed);
      position: static;
      z-index: 10;
    }
    .replicar-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      color: var(--color-secondary);
      text-align: center;
      position: relative;
      padding-bottom: 10px;
    }
    .replicar-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 2px;
      background-color: var(--color-highlight);
    }
    .replicar-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      align-items: flex-start;
      width: 500px;
      height: 80%;
      padding: 15px;
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: var(--border-radius);
      box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
    }
    .tallas-selection, .colores-selection {
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 200px;
    }
    .tallas-selection label, .colores-selection label {
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--color-secondary);
      font-size: 14px;
    }
    #multiTallas, #multiColores {
      min-width: 180px;
      height: 70%;
      padding: 10px;
      border-radius: var(--border-radius);
      border: 1px solid var(--color-highlight);
      background-color: var(--color-white);
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
      font-size: 14px;
      max-height: 220px;
    }
    #multiTallas:focus, #multiColores:focus {
      border-color: var(--color-primary);
      box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
      outline: none;
    }
    #multiTallas option, #multiColores option {
      padding: 8px;
    }
    #multiTallas option:checked, #multiColores option:checked {
      background-color: var(--color-highlight);
      color: var(--color-secondary);
    }
    .btn-replicar {
      background-color: var(--color-primary);
      color: var(--color-white);
      border: none;
      border-radius: var(--border-radius);
      padding: 12px 24px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color var(--transition-speed), transform var(--transition-speed);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      align-self: flex-center;
      margin-top: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      letter-spacing: 0.5px;
    }
    .btn-replicar:hover {
      background-color: var(--color-secondary);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-replicar:active {
      transform: translateY(1px);
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .btn-replicar::before {
      content: '✓';
      margin-right: 8px;
      font-size: 16px;
    }
    .btn-eliminar {
      background-color: var(--color-delete-button);
      color: var(--color-white);
      border: none;
      border-radius: var(--border-radius);
      padding: 6px 12px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color var(--transition-speed), transform var(--transition-speed);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-eliminar:hover {
      background-color: var(--color-delete-button-hover);
      transform: translateY(-1px);
    }
    .btn-eliminar:active {
      transform: translateY(1px);
    }
    .fila-carga.selected {
      outline: 2px solid var(--color-primary);
      background-color: rgba(197, 212, 193, 0.2) !important;
      transition: background-color var(--transition-speed), outline var(--transition-speed);
    }
    .mensaje-flotante {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 25px;
      border-radius: var(--border-radius);
      color: white;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 1000;
      transform: translateY(-20px);
      opacity: 0;
      transition: transform var(--transition-speed), opacity var(--transition-speed);
    }
    .mensaje-success {
      background-color: var(--color-primary);
    }
    .mensaje-error {
      background-color: var(--color-error);
    }
    @keyframes fadeInRow {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .fila-nueva {
      animation: fadeInRow 0.5s ease forwards;
    }
    @media (max-width: 768px) {
      .replicar-container {
        padding: 20px 15px;
        margin: 20px 10px;
      }
      .replicar-controls {
        flex-direction: column;
        align-items: center;
        padding: 10px;
      }
      .tallas-selection, .colores-selection {
        max-width: 100%;
      }
      #multiTallas, #multiColores {
        width: 100%;
        min-width: unset;
      }
      .btn-replicar {
        width: 100%;
        margin-top: 15px;
      }
    }
  `;
  document.head.appendChild(style);

  // --- BOTÓN PARA ABRIR EL MODAL DE TALLAS ---
  let abrirBtn = document.getElementById("abrirReplicarModalBtn");
  // --- CREA EL MODAL DE TALLAS Y SU CONTENIDO ---
  let modal = document.getElementById("replicarModal");
  if (!modal) {
    modal = document.createElement("div");
    modal.id = "replicarModal";
    modal.className = "modal-fds";
    modal.innerHTML = `
      <div class="modal-content-fds">
        <button class="modal-close-fds" id="cerrarReplicarModalBtn" title="Cerrar">&times;</button>
        <div class="replicar-container">
          <div class="replicar-title">Replicar filas por tallas</div>
          <div class="replicar-controls">
            <div class="tallas-selection">
              <label for="multiTallas">Seleccione tallas:</label>
              <select id="multiTallas" multiple size="6">
                <option value="XXS">XXS</option>
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="T">T</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
                <option value="2">2</option>
                <option value="4">4</option>
                <option value="6">6</option>
                <option value="8">8</option>
                <option value="10">10</option>
                <option value="12">12</option>
                <option value="14">14</option>
                <option value="16">16</option>
                <option value="28">28</option>
                <option value="30">30</option>
                <option value="32">32</option>
                <option value="34">34</option>
                <option value="36">36</option>
                <option value="UN">UN</option>
              </select>
            </div>
            <button type="button" class="btn-replicar" id="btnReplicar">Replicar filas</button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }

  // --- BOTÓN PARA ABRIR EL MODAL DE COLORES ---
  let abrirBtnColor = document.getElementById("abrirReplicarColorModalBtn");

  // --- CREA EL MODAL DE COLORES Y SU CONTENIDO ---
  let modalColor = document.getElementById("replicarColorModal");
  if (!modalColor) {
    modalColor = document.createElement("div");
    modalColor.id = "replicarColorModal";
    modalColor.className = "modal-fds";
    modalColor.innerHTML = `
      <div class="modal-content-fds">
        <button class="modal-close-fds" id="cerrarReplicarColorModalBtn" title="Cerrar">&times;</button>
        <div class="replicar-container">
          <div class="replicar-title">Replicar filas por color</div>
          <div class="replicar-controls">
            <div class="colores-selection">
              <label for="multiColores">Seleccione color(es):</label>
              <select id="multiColores" multiple size="10">
                ${COLORES_FDS.map(c => `<option value="${c.codigo}">${c.codigo} - ${c.nombre} (${c.gama})</option>`).join("")}
              </select>
            </div>
            <button type="button" class="btn-replicar" id="btnReplicarColor">Replicar filas</button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modalColor);
  }

  // --- ABRIR/CERRAR MODAL TALLAS ---
  const cerrarBtn = document.getElementById("cerrarReplicarModalBtn");
  abrirBtn.addEventListener("click", function() {
    modal.classList.add("active");
    document.body.classList.add("modal-open");
  });
  cerrarBtn.addEventListener("click", function() {
    modal.classList.remove("active");
    document.body.classList.remove("modal-open");
  });
  modal.addEventListener("click", function(e) {
    if (e.target === modal) {
      modal.classList.remove("active");
      document.body.classList.remove("modal-open");
    }
  });
  document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
      modal.classList.remove("active");
      document.body.classList.remove("modal-open");
      modalColor.classList.remove("active");
    }
  });

  // --- ABRIR/CERRAR MODAL COLOR ---
  const cerrarBtnColor = document.getElementById("cerrarReplicarColorModalBtn");
  abrirBtnColor.addEventListener("click", function() {
    modalColor.classList.add("active");
    document.body.classList.add("modal-open");
  });
  cerrarBtnColor.addEventListener("click", function() {
    modalColor.classList.remove("active");
    document.body.classList.remove("modal-open");
  });
  modalColor.addEventListener("click", function(e) {
    if (e.target === modalColor) {
      modalColor.classList.remove("active");
      document.body.classList.remove("modal-open");
    }
  });

  // --- LÓGICA DE REPLICAR FILAS POR TALLAS ---
  document.getElementById("btnReplicar").addEventListener("click", async function () {
    this.style.transform = "scale(0.95)";
    setTimeout(() => {
      this.style.transform = "";
    }, 150);

    const filaBase = document.querySelector(".fila-carga");
    if (!filaBase) {
      mostrarMensaje("No hay fila base para replicar.", "error");
      return;
    }
    if (!validarCamposObligatoriosFila(filaBase)) {
      return;
    }
    const multiSelect = document.getElementById("multiTallas");
    const tallasSeleccionadas = Array.from(multiSelect.selectedOptions).map(
      (opt) => opt.value
    );
    if (tallasSeleccionadas.length === 0) {
      mostrarMensaje("Selecciona al menos una talla para replicar.", "error");
      return;
    }
    const filasBase = [filaBase];
    const filasAgregadas = await replicarFilasPorCampo({
      filasBase,
      campoReplicar: "TALLAS",
      valoresReplicar: tallasSeleccionadas,
    });
    if (filasAgregadas > 0) {
      mostrarMensaje(
        `Se han agregado ${filasAgregadas} filas correctamente.`,
        "success"
      );
    } else {
      mostrarMensaje(
        "No se agregaron filas nuevas. Posiblemente ya existen todas las combinaciones.",
        "error"
      );
    }
    multiSelect.selectedIndex = -1;
  });

  // --- LÓGICA DE REPLICAR FILAS POR COLOR ---
  document.getElementById("btnReplicarColor").addEventListener("click", async function () {
    this.style.transform = "scale(0.95)";
    setTimeout(() => {
      this.style.transform = "";
    }, 150);

    // Puedes cambiar esto para seleccionar varias filas base si lo deseas
    const filasBase = Array.from(document.querySelectorAll(".fila-carga.selected"));
    if (filasBase.length === 0) {
      // Si no hay filas seleccionadas, toma la primera
      const filaBase = document.querySelector(".fila-carga");
      if (!filaBase) {
        mostrarMensaje("No hay fila base para replicar.", "error");
        return;
      }
      filasBase.push(filaBase);
    }
    for (const fila of filasBase) {
      if (!validarCamposObligatoriosFila(fila)) {
        return;
      }
    }
    const multiSelect = document.getElementById("multiColores");
    const coloresSeleccionados = Array.from(multiSelect.selectedOptions).map(
      (opt) => opt.value
    );
    if (coloresSeleccionados.length === 0) {
      mostrarMensaje("Selecciona al menos un color para replicar.", "error");
      return;
    }
    // Mapeo para campos extra
    const camposExtra = {
      "NOM_COLOR": MAPA_NOMBRE_COLOR,
      "GAMA": MAPA_GAMA_COLOR,
    };
    const filasAgregadas = await replicarFilasPorCampo({
      filasBase,
      campoReplicar: "COLOR_FDS",
      valoresReplicar: coloresSeleccionados,
      camposExtra,
    });
    if (filasAgregadas > 0) {
      mostrarMensaje(
        `Se han agregado ${filasAgregadas} filas correctamente.`,
        "success"
      );
    } else {
      mostrarMensaje(
        "No se agregaron filas nuevas. Posiblemente ya existen todas las combinaciones.",
        "error"
      );
    }
    multiSelect.selectedIndex = -1;
  });

  // Evento para filtrar tallas según categoría seleccionada
  document.addEventListener("change", function (e) {
    if (
      e.target.classList.contains("campo-formulario") &&
      e.target.getAttribute("data-campo-nombre") === "CATEGORIAS"
    ) {
      const fila = e.target.closest(".fila-carga");
      if (fila) {
        filtrarOpcionesTallasMultiSelectPorCategoria(fila);
      }
    }
  });

  // Permitir seleccionar filas para replicar por color (opcional)
  document.addEventListener("click", function (e) {
    if (e.target.closest(".fila-carga")) {
      const fila = e.target.closest(".fila-carga");
      if (e.ctrlKey || e.metaKey) {
        fila.classList.toggle("selected");
      } else {
        document.querySelectorAll(".fila-carga.selected").forEach(f => f.classList.remove("selected"));
        fila.classList.add("selected");
      }
    }
  });

  actualizarBotonesEliminar();
  document.addEventListener("change", function(e) {
    if (
      e.target.classList.contains("campo-formulario") &&
      e.target.getAttribute("data-campo-nombre") === "COLOR_FDS"
    ) {
      const fila = e.target.closest(".fila-carga");
      const codigo = e.target.value;
      if (fila) {
        const nomColorTd = fila.querySelector('.campo-formulario[data-campo-nombre="NOM_COLOR"]');
        const gamaTd = fila.querySelector('.campo-formulario[data-campo-nombre="GAMA"]');
        if (nomColorTd) nomColorTd.textContent = MAPA_NOMBRE_COLOR[codigo] || "-";
        if (gamaTd) gamaTd.textContent = MAPA_GAMA_COLOR[codigo] || "-";
      }
    }
  });
});