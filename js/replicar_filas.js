const CAMPOS_OBLIGATORIOS = [
  // Ejemplo: "NOMBRE", "CATEGORIAS", "TALLAS"
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
      max-width: 98vw;
      min-width: 320px;
      max-height: 95vh;
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
      width: 100%;
      max-width: 650px;
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

  // --- BOTÓN PARA ABRIR EL MODAL ---
  let abrirBtn = document.getElementById("abrirReplicarModalBtn");

  // --- CREA EL MODAL Y SU CONTENIDO ---
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

  // --- ABRIR/CERRAR MODAL ---
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
    }
  });

  // --- LÓGICA DE REPLICAR FILAS ---
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
    const selectTallas = filaBase.querySelector(
      '.campo-formulario[data-campo-nombre="TALLAS"]'
    );
    if (!selectTallas) {
      mostrarMensaje("No se encontró el campo de tallas en la fila.", "error");
      return;
    }
    const tbody = document.querySelector("#skuTable tbody");
    if (!tbody) return;
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
    let insertAfter = filaBase;
    let filasAgregadas = 0;
    const loadingIndicator = document.createElement("div");
    loadingIndicator.className = "loading-indicator";
    loadingIndicator.innerHTML = "<span>Procesando...</span>";
    loadingIndicator.style.textAlign = "center";
    loadingIndicator.style.padding = "10px";
    loadingIndicator.style.color = "var(--color-secondary)";
    modal.querySelector(".replicar-container").appendChild(loadingIndicator);
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
      await copiarValoresCampos(filaBase, nuevaFila, "TALLAS");
      const selectTallasNueva = nuevaFila.querySelector(
        '.campo-formulario[data-campo-nombre="TALLAS"]'
      );
      if (selectTallasNueva) {
        selectTallasNueva.value = talla;
      }
      if (typeof initializeRowFields === "function") {
        initializeRowFields(nuevaFila);
        if (selectTallasNueva) selectTallasNueva.value = talla;
      }
      insertAfter.parentNode.insertBefore(nuevaFila, insertAfter.nextSibling);
      insertAfter = nuevaFila;
      nuevaFila.removeAttribute("data_fila_original");
      filasAgregadas++;
      combinacionesExistentes.add(key);
      const inputNombre = nuevaFila.querySelector(
        'input.campo-formulario[data-campo-nombre="NOMBRE"]'
      );
      if (inputNombre && typeof filtrarCategoriasPorNombre === "function") {
        filtrarCategoriasPorNombre(inputNombre);
      }
      await new Promise((resolve) => setTimeout(resolve, 50));
    }
    loadingIndicator.remove();
    actualizarBotonesEliminar();
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
    // Opcional: cerrar el modal automáticamente después de replicar
    // modal.classList.remove("active");
    // document.body.classList.remove("modal-open");
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

  actualizarBotonesEliminar();
});