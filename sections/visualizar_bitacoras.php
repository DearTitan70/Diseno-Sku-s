<?php
require_once __DIR__ . '/../backend/auth.php';

// Permite acceso a todos
require_login_and_role();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Visualizar Cambios</title>
    <!-- Iconos de FontAwesome para botones y estados -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      /* ==============================
           FILTROS DE BUSQUEDA
        ============================== */
        .filter {
            padding: 12px 16px;
            border: 1px solid var(--color-highlight);
            border-radius: var(--radius);
            font-size: 1rem;
            border-radius: 20px;
            transition: var(--transition);
            outline: none;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .filter:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
            background-color: white;
        }

      /* =========================
           VARIABLES CSS Y ESTILOS
           ========================= */
      :root {
        /* Paleta de colores y estilos reutilizables */
        --color-background: #f9f3e5;
        --color-text-dark: #000000;
        --color-primary: #879683;
        --color-secondary: #5a6b58;
        --color-highlight: #c5d4c1;
        --color-logout: #a0a0a0;
        --color-logout-hover: #8a8a8a;
        --color-danger: #dc3545;
        --color-success: #198754;
        --border-radius: 8px;
        --box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      }

      /* Reset y tipografía base */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        background-color: var(--color-background);
        color: var(--color-text-dark);
        padding: 20px;
        line-height: 1.6;
        font-size: 16px;
      }

      /* =========================
           CONTENEDOR PRINCIPAL
           ========================= */
      .container {
        max-width: 2000px;
        margin: 0 auto;
        padding: 30px;
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        animation: fadeInUp 0.8s ease-in-out;
        border: 1px solid var(--color-highlight);
      }

      /* =========================
           CABECERA DE LA TABLA Y CONTROLES
           ========================= */
      .table_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        margin-bottom: 30px;
        border-bottom: 2px solid var(--color-primary);
        position: relative;
      }

      .table_header::after {
        /* Línea decorativa inferior */
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, var(--color-highlight), transparent);
      }

      .hidden {
        display: none !important;
      }

      .table_title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-primary);
        position: relative;
        padding-left: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
      }

      .table_title::before {
        /* Barra vertical animada a la izquierda del título */
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 6px;
        background-color: var(--color-primary);
        border-radius: var(--border-radius);
        animation: pulseHighlight 2s infinite;
      }

      .controls {
        display: flex;
        gap: 15px;
        align-items: center;
      }

      /* =========================
           BUSCADOR Y BOTONES
           ========================= */
      .search-box {
        position: relative;
        width: 280px;
      }

      .search-box input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid var(--color-highlight);
        border-radius: var(--border-radius);
        transition: var(--transition);
        font-size: 15px;
        background-color: rgba(255, 255, 255, 0.9);
      }

      .search-box input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
        background-color: #fff;
      }

      .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-primary);
        font-size: 18px;
      }

      .btn {
        padding: 12px 20px;
        border: none;
        border-radius: var(--border-radius);
        background-color: var(--color-primary);
        color: white;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
      }

      .btn::before {
        /* Efecto de brillo al pasar el mouse */
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          90deg,
          transparent,
          rgba(255, 255, 255, 0.2),
          transparent
        );
        transition: 0.5s;
      }

      .btn:hover {
        background-color: var(--color-secondary);
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      }

      .btn:hover::before {
        left: 100%;
      }

      .btn:active {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
      }

      .btn i {
        font-size: 16px;
      }

      /* =========================
           FILTROS Y SELECT DE ENTRADAS
           ========================= */
      .filter-controls {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .entries-select {
        padding: 10px 15px;
        border: 2px solid var(--color-highlight);
        border-radius: var(--border-radius);
        transition: var(--transition);
        background-color: white;
        color: var(--color-text-dark);
        font-size: 15px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23879683' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
      }

      .entries-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
      }

      /* =========================
           TABLA DE REGLAS
           ========================= */
      table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 20px;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        background-color: white;
        table-layout: auto;
      }

      thead {
        background: linear-gradient(
          135deg,
          var(--color-primary),
          var(--color-secondary)
        );
        color: white;
      }

      th,
      td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid rgba(197, 212, 193, 0.3);
        white-space: normal;
        word-break: normal;
        overflow-wrap: break-word;
        max-width: 800px;
      }

      th {
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 14px;
      }

      td {
        font-size: 15px;
      }

      tbody tr {
        transition: var(--transition);
        position: relative;
      }

      tbody tr:last-child td {
        border-bottom: none;
      }

      tbody tr:hover {
        background-color: rgba(197, 212, 193, 0.1);
        transform: translateY(-2px) scale(1.005);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        z-index: 1;
      }

      /* =========================
           BOTONES DE ACCIÓN EN LA TABLA
           ========================= */
      .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        margin-right: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }

      .edit-btn {
        background-color: var(--color-primary);
        color: white;
      }

      .edit-btn:hover {
        background-color: var(--color-secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      }

      .view-btn {
        background-color: var(--color-highlight);
        color: var(--color-secondary);
      }

      .view-btn:hover {
        background-color: #b6c5b2;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      }

      .retire-btn {
        background-color: var(--color-danger);
        color: white;
      }

      .retire-btn:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      }

      /* =========================
           PAGINACIÓN
           ========================= */
      .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding: 20px 0;
        border-top: 1px solid var(--color-highlight);
      }

      .page-info {
        color: var(--color-text-dark);
        font-size: 15px;
      }

      .page-btns {
        display: flex;
        gap: 10px;
      }

      .page-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--color-highlight);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        background-color: white;
        font-weight: 600;
        color: var(--color-text-dark);
      }

      .page-btn:hover,
      .page-btn.active {
        background-color: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .page-btn.active {
        box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.3);
      }

      .page-btn.disabled {
        opacity: 0.5;
        cursor: default;
        pointer-events: none;
      }

      .page-btn.disabled:hover {
        background-color: white;
        color: inherit;
        border-color: var(--color-highlight);
        transform: none;
        box-shadow: none;
      }

      /* =========================
           ESTADOS DE CARGA Y VACÍO
           ========================= */
      .loading {
        text-align: center;
        padding: 60px;
        color: var(--color-primary);
      }

      .loading i {
        font-size: 50px;
        animation: spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        margin-bottom: 15px;
        color: var(--color-primary);
      }

      .empty-state {
        text-align: center;
        padding: 60px;
        color: var(--color-text-dark);
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: var(--border-radius);
        margin: 20px 0;
        border: 1px dashed var(--color-highlight);
      }

      .empty-state i {
        font-size: 50px;
        margin-bottom: 20px;
        color: var(--color-primary);
      }

      .empty-state h3 {
        font-size: 24px;
        margin-bottom: 10px;
        color: var(--color-secondary);
      }

      /* =========================
           BADGES Y TOASTS
           ========================= */
      .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
      }

      .badge-admin {
        background-color: rgba(135, 150, 131, 0.2);
        color: var(--color-secondary);
      }

      .badge-rule {
        background-color: rgba(197, 212, 193, 0.4);
        color: var(--color-secondary);
      }

      .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: var(--color-success);
        color: white;
        padding: 16px 25px;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 1000;
      }

      .toast.show {
        transform: translateX(0);
      }

      .toast i {
        font-size: 22px;
      }

      /* =========================
           MODAL PARA CREAR CÁPSULA
           ========================= */
      #capsula-modal {
        animation: fadeIn 0.3s ease-out;
      }

      #capsula-modal > div {
        animation: slideInUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        border: 1px solid var(--color-highlight);
      }

      #capsula-modal h3 {
        color: var(--color-primary);
        margin-bottom: 20px;
        font-size: 22px;
        border-bottom: 2px solid var(--color-highlight);
        padding-bottom: 10px;
      }

      #capsula-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--color-text-dark);
      }

      #capsula-form input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--color-highlight);
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        font-size: 16px;
        transition: var(--transition);
      }

      #capsula-form input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
      }

      #capsula-form button {
        padding: 12px 20px;
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        width: 100%;
      }

      #capsula-form button:hover {
        background-color: var(--color-secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      #capsula-status-message {
        padding: 12px;
        border-radius: var(--border-radius);
        margin-top: 15px;
        font-weight: 600;
        text-align: center;
      }

      #capsula-status-message.success {
        background-color: rgba(25, 135, 84, 0.1);
        color: var(--color-success);
        border: 1px solid rgba(25, 135, 84, 0.2);
      }

      #capsula-status-message.error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--color-danger);
        border: 1px solid rgba(220, 53, 69, 0.2);
      }

      /* =========================
           ANIMACIONES Y RESPONSIVE
           ========================= */
      @keyframes fadeIn {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      @keyframes slideInUp {
        from {
          opacity: 0;
          transform: translateY(50px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }
      @keyframes pulseHighlight {
        0% {
          opacity: 0.7;
        }
        50% {
          opacity: 1;
        }
        100% {
          opacity: 0.7;
        }
      }

      /* Adaptación para pantallas medianas y pequeñas */
      @media (max-width: 992px) {
        .container {
          padding: 20px;
        }
        .table_header {
          flex-direction: column;
          align-items: flex-start;
          gap: 15px;
        }
        .controls {
          width: 100%;
          flex-wrap: wrap;
        }
        .btn {
          flex: 1;
          justify-content: center;
        }
        th,
        td {
          padding: 12px;
          font-size: 14px;
        }
      }
      @media (max-width: 768px) {
        .pagination {
          flex-direction: column;
          gap: 15px;
        }
        .page-info {
          text-align: center;
        }
        .page-btns {
          justify-content: center;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- =========================
             CABECERA Y BOTONES PRINCIPALES
             ========================= -->
      <div class="table_header">
        <span class="table_title">Bitacora de cambios</span>
        <div class="controls">
          <!-- Botón para volver al menú principal -->
          <button
            class="btn"
            id="addUserBtn"
            onclick="window.location.href='index.php'"
          >
            <i class="fas fa-home"></i> Volver al menu
          </button>
        </div>
      </div>

      <div style="background: #e6f7ff; border: 1px solid #91d5ff; border-radius: 8px; padding: 18px 24px; margin-bottom: 32px; text-align: left; color: #274c5e; font-size: 1.08em;">
        <strong>Antes de empezar:</strong>
        <ul style="margin-top: 10px;">
          <li>
            <b>¿Qué es la bitácora de cambios?</b>
            <ul>
              <li>La bitácora registra todas las modificaciones realizadas en el sistema, permitiendo rastrear quién, cuándo y qué información fue cambiada.</li>
              <li>Cada grupo corresponde a un campo modificado, y puedes expandirlo para ver los detalles de cada cambio.</li>
            </ul>
          </li>
          <li>
            <b>¿Cómo utilizar los filtros?</b>
            <ul>
              <li>Utiliza la barra de búsqueda para encontrar cambios específicos por usuario, campo, valor anterior, valor nuevo o ID de registro.</li>
              <li>El filtro "campo modificado" te permite acotar los resultados a un campo en particular (por ejemplo, "SAP").</li>
              <li>Puedes combinar ambos filtros para una búsqueda más precisa.</li>
            </ul>
          </li>
          <li>
            <b>Recomendaciones:</b>
            <ul>
              <li>Lee cuidadosamente cada registro antes de tomar decisiones o realizar auditorías.</li>
              <li>La información de la bitácora es sensible y debe ser manejada con responsabilidad y confidencialidad.</li>
              <li>Si detectas un cambio inesperado o sospechoso, repórtalo al administrador del sistema.</li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="filter-controls">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input
            type="text"
            id="searchInput"
            placeholder="Buscar en toda la bitácora..."
            class="filter"
          />
        </div>
        <div class="search-box">
          <i class="fas fa-filter"></i>
          <input
            type="text"
            id="campoFilter"
            placeholder="Filtrar por campo modificado (ej: SAP)"
            class="filter"
          />
        </div>
      </div>

      <!-- =========================
             TABLA DE REGLAS Y ESTADOS
             ========================= -->
      <div id="tableContainer">
        <table id="dataTable">
          <tbody id="userTableBody">
            <!-- Las filas de reglas se insertan dinámicamente aquí -->
          </tbody>
        </table>

        <!-- Indicador de carga mientras se obtienen los datos -->
        <div id="loadingIndicator" class="loading">
          <i class="fas fa-spinner"></i>
          <p>Cargando cambios...</p>
        </div>

        <!-- Estado vacío si no hay reglas -->
        <div id="emptyState" class="empty-state" style="display: none">
          <i
            class="fas fa-rule-slash"
            style="font-size: 40px; margin-bottom: 15px"
          ></i>
          <h3>No se encontraron cambios</h3>
          <p>No hay cambios que coincidan con tu búsqueda.</p>
        </div>
      </div>

    <!-- =========================
         SCRIPTS DE INTERACCIÓN
         ========================= -->
    <script>
      
document.addEventListener("DOMContentLoaded", function () {
  let cambiosData = [];
  let cambiosFiltrados = [];

  // Elementos del DOM
  const tableBody = document.getElementById("userTableBody");
  const loadingIndicator = document.getElementById("loadingIndicator");
  const emptyState = document.getElementById("emptyState");
  const searchInput = document.getElementById("searchInput");
  const campoFilter = document.getElementById("campoFilter");

  // Carga los cambios desde el backend
  function loadBitacoraData() {
    loadingIndicator.style.display = "block";
    document.getElementById("dataTable").style.display = "none";
    emptyState.style.display = "none";

    fetch("../api/obtener_bitacora.php")
      .then((response) => response.json())
      .then((data) => {
        cambiosData = data;
        cambiosFiltrados = [...cambiosData];
        renderGroupedBitacora(cambiosFiltrados);
        loadingIndicator.style.display = "none";
        document.getElementById("dataTable").style.display = "";
      })
      .catch((error) => {
        console.error("Error al cargar bitácora:", error);
        loadingIndicator.style.display = "none";
        emptyState.style.display = "block";
        document.getElementById("dataTable").style.display = "none";
      });
  }

  // Filtro de búsqueda
  function applyFilters() {
  const searchTerm = searchInput ? searchInput.value.toLowerCase() : "";
  const campoTerm = campoFilter ? campoFilter.value.toLowerCase() : "";

  cambiosFiltrados = cambiosData.filter((cambio) => {
    // Filtro general
    const matchesGeneral =
      (cambio.campo && cambio.campo.toLowerCase().includes(searchTerm)) ||
      (cambio.usuario && cambio.usuario.toLowerCase().includes(searchTerm)) ||
      (cambio.valor_anterior && cambio.valor_anterior.toLowerCase().includes(searchTerm)) ||
      (cambio.valor_nuevo && cambio.valor_nuevo.toLowerCase().includes(searchTerm)) ||
      (cambio.nombre && cambio.nombre.toLowerCase().includes(searchTerm)) ||
      (cambio.registro_id && cambio.registro_id.toString().includes(searchTerm)); 

    // Filtro por campo modificado
    const matchesCampo =
      !campoTerm || (cambio.campo && cambio.campo.toLowerCase().includes(campoTerm));

    return matchesGeneral && matchesCampo;
  });

  renderGroupedBitacora(cambiosFiltrados);
}

// Listeners para ambos filtros
if (searchInput) searchInput.addEventListener("input", applyFilters);
if (campoFilter) campoFilter.addEventListener("input", applyFilters);

  // Renderiza la bitácora agrupada por campo modificado
  function renderGroupedBitacora(bitacoraData) {
    tableBody.innerHTML = "";

    // Agrupa por campo modificado
    const groups = {};
    bitacoraData.forEach(registro => {
      const campo = registro.campo || "Otro";
      if (!groups[campo]) groups[campo] = [];
      groups[campo].push(registro);
    });

    // Si no hay datos
    if (Object.keys(groups).length === 0) {
      emptyState.style.display = "block";
      document.getElementById("dataTable").style.display = "";
      return;
    } else {
      emptyState.style.display = "none";
      document.getElementById("dataTable").style.display = "";
    }

    // Helper para crear el header dinámico
    function createHeaderRow() {
      const headerRow = document.createElement("tr");
      headerRow.className = "dynamic-header";
      [
        "Usuario que modifico",
        "Fecha de modificacion",
        "ID del registro afectado",
        "Nombre del registro afectado",
        "Campo Modificado",
        "Valor antes del cambio",
        "Valor despues del cambio"
      ].forEach(header => {
        const th = document.createElement("th");
        th.textContent = header;
        headerRow.appendChild(th);
      });
      return headerRow;
    }

    // Renderiza los grupos
    Object.entries(groups)
      .sort((a, b) => a[0].localeCompare(b[0]))
      .forEach(([campo, registros]) => {
        // Fila de encabezado de grupo
        const groupRow = document.createElement("tr");
        groupRow.className = "group-header";
        groupRow.innerHTML = `<td colspan="6" style="background:white;font-weight:bold;cursor:pointer;">
            Cambios en <b>${campo}</b> (${registros.length} registros) <span style="float:right;">[+]</span>
        </td>`;
        tableBody.appendChild(groupRow);

        // Crea las filas de registros (inicialmente ocultas)
        const rows = [];
        registros.forEach(registro => {
          const row = document.createElement("tr");
          row.className = "grouped-row";
          row.style.display = "none";
          row.innerHTML = `
                <td>${registro.usuario}</td>
                <td>${registro.fecha}</td>
                <td>${registro.registro_id}</td>
                <td>${registro.nombre}</td>
                <td>${registro.campo}</td>
                <td>${registro.valor_anterior}</td>
                <td>${registro.valor_nuevo}</td>
            `;
          rows.push(row);
        });

        // Evento para expandir/colapsar
        groupRow.addEventListener("click", function () {
          const isCollapsed = groupRow.getAttribute("data-collapsed") !== "false";
          groupRow.setAttribute("data-collapsed", !isCollapsed);
          groupRow.querySelector("span").textContent = isCollapsed ? "[-]" : "[+]";

          // Busca si ya existe el header dinámico
          let next = groupRow.nextSibling;
          let headerRow = null;
          if (next && next.classList && next.classList.contains("dynamic-header")) {
            headerRow = next;
          }

          if (isCollapsed) {
            // Expandir: insertar header y mostrar filas
            if (!headerRow) {
              headerRow = createHeaderRow();
              tableBody.insertBefore(headerRow, groupRow.nextSibling);
            }
            rows.forEach((row, idx) => {
              row.style.display = "";
              if (row.parentNode !== tableBody) {
                tableBody.insertBefore(row, headerRow.nextSibling ? headerRow.nextSibling : headerRow.nextSibling);
              }
            });
          } else {
            // Colapsar: ocultar filas y quitar header
            rows.forEach(row => row.style.display = "none");
            if (headerRow) {
              tableBody.removeChild(headerRow);
            }
          }
        });

        // Inserta las filas (pero ocultas)
        rows.forEach(row => tableBody.appendChild(row));
      });
  }

  // Inicializa la carga
  loadBitacoraData();
});

    </script>
  </body>
</html>
