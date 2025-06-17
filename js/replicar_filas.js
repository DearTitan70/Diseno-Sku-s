const CAMPOS_OBLIGATORIOS = [
  "tipo",
  "usuario",
  "fecha_creacion",
  "YEAR",
  "TOT_COMP",
  "TIPO_TEJIDO",
  "TIPO_DE_FIBRA",
  "TIENDA",
  "TEMPORADA",
  "TALLAS",
  "SUB_DETALLES",
  "SUBCATEGORIAS",
  "PROVEEDOR",
  "PRINT",
  "OCASION_DE_USO",
  "NOM_COLOR",
  "NOMBRE",
  "MODULO",
  "MES",
  "MANGA",
  "LARGO",
  "GRUPO",
  "GAMA",
  "DETALLES",
  "DESCRIPCION",
  "COLOR_FDS",
  "CLUSTER",
  "CLIMA",
  "CLASIFICACION",
  "CATEGORIAS",
  "CAPSULA",
  "BASE_TEXTIL",
  "%_COMP_1",
  "COMPOSICION_1",
  "precio_compra",
  "costo",
  "precio_venta"
];

const TALLAS_SUP = ["XS", "S", "M", "L", "XL", "XXL"];
const TALLAS_INF = ["26", "28", "30", "32", "34", "36"];
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

function actualizarBotonesEliminar() {
  const filas = document.querySelectorAll(".fila-carga");
  filas.forEach((fila, idx) => {
    // Busca la celda de acciones (última celda)
    const celdas = fila.querySelectorAll("td");
    const celdaAcciones = celdas[celdas.length - 1];

    // Elimina cualquier botón existente en la celda de acciones
    if (celdaAcciones) {
      const btnExistente = celdaAcciones.querySelector(".delete-row");
      if (btnExistente) btnExistente.remove();
    }

    if (idx === 0) {
      // Primera fila: no muestra botón
      return;
    }

    // Otras filas: agrega el botón si no existe
    if (celdaAcciones && !celdaAcciones.querySelector(".delete-row")) {
      const deleteBtn = document.createElement("button");
      deleteBtn.type = "button";
      deleteBtn.className = "delete-row btn-eliminar";
      deleteBtn.textContent = "Eliminar";

      // Centra el botón en la celda
      celdaAcciones.style.textAlign = "center";

      deleteBtn.onclick = function (event) {
        event.preventDefault();
        // Añadir animación de desvanecimiento
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

  // Guarda las tallas seleccionadas antes de limpiar
  const seleccionadas = Array.from(multiSelect.selectedOptions).map(
    (opt) => opt.value
  );

  // Limpia y agrega solo las opciones válidas
  multiSelect.innerHTML = "";
  opciones.forEach((talla) => {
    const opt = document.createElement("option");
    opt.value = talla;
    opt.textContent = talla;
    if (seleccionadas.includes(talla)) opt.selected = true;
    multiSelect.appendChild(opt);
  });
}

/**
 * Valida que todos los campos obligatorios de la fila dada estén diligenciados.
 * @param {HTMLElement} fila - La fila a validar.
 * @returns {boolean} true si todos los campos están llenos, false si falta alguno.
 */
function validarCamposObligatoriosFila(fila) {
  let validos = true;
  let primerCampoFaltante = null;

  CAMPOS_OBLIGATORIOS.forEach((nombreCampo) => {
    const campo = fila.querySelector(
      `.campo-formulario[data-campo-nombre="${nombreCampo}"]`
    );
    console.log("Validando campo:", nombreCampo, campo); // Para depuración
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

      // Limpia el borde cuando el usuario corrige el campo
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
        // Llenar opciones estáticas y luego asignar el valor
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
        // Llenar opciones dependientes por AJAX y luego asignar el valor
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
        // Si no es static ni dependent, solo intenta asignar el valor si existe
        campoDestino.value = valorOriginal || "";
      }
    }
  });

  if (promesas.length > 0) {
    await Promise.all(promesas);
  }
}

// Función para mostrar mensajes con animación
function mostrarMensaje(texto, tipo = "success") {
  const mensajeExistente = document.querySelector(".mensaje-flotante");
  if (mensajeExistente) mensajeExistente.remove();

  const mensaje = document.createElement("div");
  mensaje.className = `mensaje-flotante mensaje-${tipo}`;
  mensaje.textContent = texto;
  document.body.appendChild(mensaje);

  // Animar entrada
  setTimeout(() => {
    mensaje.style.transform = "translateY(0)";
    mensaje.style.opacity = "1";
  }, 10);

  // Animar salida después de 3 segundos
  setTimeout(() => {
    mensaje.style.transform = "translateY(-20px)";
    mensaje.style.opacity = "0";
    setTimeout(() => mensaje.remove(), 300);
  }, 3000);
}

document.addEventListener("DOMContentLoaded", function () {
  // Agregar estilos CSS para los componentes
  const style = document.createElement("style");
  style.textContent = `
        /* Variables de colores */
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
        
        /* Estilos generales */
        .replicar-container {
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 650px;
            padding: 25px;
            border-radius: var(--border-radius);
            background-color: var(--color-white);
            box-shadow: 0 4px 15px var(--color-shadow);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            position: relative;
            overflow: hidden;
        }
        
        .replicar-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .replicar-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--color-primary);
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
            width: 100%;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: var(--border-radius);
            box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
        }
        
        .tallas-selection {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 200px;
        }
        
        .tallas-selection label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--color-secondary);
            font-size: 14px;
        }
        
        #multiTallas {
            min-width: 180px;
            padding: 10px;
            border-radius: var(--border-radius);
            border: 1px solid var(--color-highlight);
            background-color: var(--color-white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
            font-size: 14px;
        }
        
        #multiTallas:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
            outline: none;
        }
        
        #multiTallas option {
            padding: 8px;
        }
        
        #multiTallas option:checked {
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
            align-self: flex-end;
            margin-top: 20px;
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
        
        /* Mensaje flotante */
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
        
        /* Animación para nuevas filas */
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
        
        /* Media queries para responsividad */
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
            
            .tallas-selection {
                max-width: 100%;
            }
            
            #multiTallas {
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

  // 1. Agrega el contenedor de replicación al DOM (después de la tabla)
  const tableContainer = document.querySelector(".table-container");
  if (!tableContainer) return;

  // Crea el contenedor de replicación con mejor estructura
  const replicarContainer = document.createElement("div");
  replicarContainer.className = "replicar-container";

  // Título descriptivo
  const replicarTitle = document.createElement("div");
  replicarTitle.className = "replicar-title";
  replicarTitle.textContent = "Replicar filas por tallas";
  replicarContainer.appendChild(replicarTitle);

  // Contenedor para los controles
  const replicarControls = document.createElement("div");
  replicarControls.className = "replicar-controls";

  // Contenedor para el selector y su etiqueta
  const tallasSelectionDiv = document.createElement("div");
  tallasSelectionDiv.className = "tallas-selection";

  // Etiqueta para el selector
  const multiSelectLabel = document.createElement("label");
  multiSelectLabel.htmlFor = "multiTallas";
  multiSelectLabel.textContent = "Seleccione tallas:";
  tallasSelectionDiv.appendChild(multiSelectLabel);

  // Selector múltiple de tallas
  const multiSelect = document.createElement("select");
  multiSelect.id = "multiTallas";
  multiSelect.multiple = true;
  multiSelect.size = 6;

  // Opciones de tallas (ajusta según tus necesidades)
  const tallas = [
    "XXS",
    "XS",
    "S",
    "M",
    "L",
    "T",
    "XL",
    "XXL",
    "2",
    "4",
    "6",
    "8",
    "10",
    "12",
    "14",
    "16",
    "28",
    "30",
    "32",
    "34",
    "36",
    "UN",
  ];
  tallas.forEach((talla) => {
    const opt = document.createElement("option");
    opt.value = talla;
    opt.textContent = talla;
    multiSelect.appendChild(opt);
  });
  tallasSelectionDiv.appendChild(multiSelect);
  replicarControls.appendChild(tallasSelectionDiv);

  // Botón de replicar
  const btnReplicar = document.createElement("button");
  btnReplicar.type = "button";
  btnReplicar.className = "btn-replicar";
  btnReplicar.textContent = "Replicar filas";
  replicarControls.appendChild(btnReplicar);

  // Agrega los controles al contenedor principal
  replicarContainer.appendChild(replicarControls);

  // Añadir con animación
  replicarContainer.style.opacity = "0";
  replicarContainer.style.transform = "translateY(20px)";
  tableContainer.appendChild(replicarContainer);

  // Animar entrada
  setTimeout(() => {
    replicarContainer.style.transition =
      "opacity 0.5s ease, transform 0.5s ease";
    replicarContainer.style.opacity = "1";
    replicarContainer.style.transform = "translateY(0)";
  }, 100);

  // Evento de replicación
  btnReplicar.addEventListener("click", async function () {
    // Efecto de clic en el botón
    this.style.transform = "scale(0.95)";
    setTimeout(() => {
      this.style.transform = "";
    }, 150);

    // Si no hay fila seleccionada, toma la primera
    const filaBase = document.querySelector(".fila-carga");
    if (!filaBase) {
      mostrarMensaje("No hay fila base para replicar.", "error");
      return;
    }

    if (!validarCamposObligatoriosFila(filaBase)) {
      // Si la validación falla, no continúa
      return;
    }

    // Obtiene las tallas seleccionadas
    const tallasSeleccionadas = Array.from(multiSelect.selectedOptions).map(
      (opt) => opt.value
    );
    if (tallasSeleccionadas.length === 0) {
      mostrarMensaje("Selecciona al menos una talla para replicar.", "error");
      return;
    }

    // Encuentra el select de tallas en la fila base
    const selectTallas = filaBase.querySelector(
      '.campo-formulario[data-campo-nombre="TALLAS"]'
    );
    if (!selectTallas) {
      mostrarMensaje("No se encontró el campo de tallas en la fila.", "error");
      return;
    }

    // Obtiene el tbody
    const tbody = document.querySelector("#skuTable tbody");
    if (!tbody) return;

    // Evitar duplicados: recolecta las combinaciones existentes
    const combinacionesExistentes = new Set();
    tbody.querySelectorAll(".fila-carga").forEach((fila) => {
      const nombre =
        fila.querySelector('.campo-formulario[data-campo-nombre="NOMBRE"]')
          ?.value || "";
      const categoria =
        fila.querySelector('.campo-formulario[data-campo-nombre="CATEGORIAS"]')
          ?.value || "";
      const talla =
        fila.querySelector('.campo-formulario[data-campo-nombre="TALLAS"]')
          ?.value || "";
      combinacionesExistentes.add(`${nombre}|${categoria}|${talla}`);
    });

    // Insertar las nuevas filas justo después de la original
    let insertAfter = filaBase;
    let filasAgregadas = 0;

    // Mostrar indicador de carga
    const loadingIndicator = document.createElement("div");
    loadingIndicator.className = "loading-indicator";
    loadingIndicator.innerHTML = "<span>Procesando...</span>";
    loadingIndicator.style.textAlign = "center";
    loadingIndicator.style.padding = "10px";
    loadingIndicator.style.color = "var(--color-secondary)";
    replicarContainer.appendChild(loadingIndicator);

    // Pequeña pausa para mostrar el indicador
    await new Promise((resolve) => setTimeout(resolve, 100));

    for (const talla of tallasSeleccionadas) {
      const nombre =
        filaBase.querySelector('.campo-formulario[data-campo-nombre="NOMBRE"]')
          ?.value || "";
      const categoria =
        filaBase.querySelector(
          '.campo-formulario[data-campo-nombre="CATEGORIAS"]'
        )?.value || "";
      const key = `${nombre}|${categoria}|${talla}`;
      if (combinacionesExistentes.has(key)) continue;

      const nuevaFila = filaBase.cloneNode(true);
      nuevaFila.classList.add("fila-nueva");

      // Copia todos los valores de la fila base, excepto el campo de tallas (espera selects dependientes)
      await copiarValoresCampos(filaBase, nuevaFila, "TALLAS");

      // Cambia el valor del select de tallas
      const selectTallasNueva = nuevaFila.querySelector(
        '.campo-formulario[data-campo-nombre="TALLAS"]'
      );
      if (selectTallasNueva) {
        selectTallasNueva.value = talla;
      }

      // Si tienes lógica de inicialización de fila, llámala aquí
      if (typeof initializeRowFields === "function") {
        initializeRowFields(nuevaFila);
        if (selectTallasNueva) selectTallasNueva.value = talla;
      }

      // Inserta la nueva fila después de la original o la última insertada
      insertAfter.parentNode.insertBefore(nuevaFila, insertAfter.nextSibling);
      insertAfter = nuevaFila;
      nuevaFila.removeAttribute("data_fila_original");
      filasAgregadas++;

      // Marca la combinación como existente
      combinacionesExistentes.add(key);

      // --- APLICA EL FILTRO DE CATEGORÍA AUTOMÁTICAMENTE ---
      const inputNombre = nuevaFila.querySelector(
        'input.campo-formulario[data-campo-nombre="NOMBRE"]'
      );
      if (inputNombre && typeof filtrarCategoriasPorNombre === "function") {
        filtrarCategoriasPorNombre(inputNombre);
      }

      // Pequeña pausa entre filas para efecto visual
      await new Promise((resolve) => setTimeout(resolve, 50));
    }

    // Eliminar indicador de carga
    loadingIndicator.remove();

    actualizarBotonesEliminar();

    // Muestra un mensaje de éxito
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

    // Limpia la selección de tallas para mejor UX
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

  // Llama a actualizarBotonesEliminar al cargar la página
  actualizarBotonesEliminar();
});
