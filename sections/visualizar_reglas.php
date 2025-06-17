<?php
require_once __DIR__ . '/../backend/auth.php';

// Permite acceso a admin (1) y editor (2)
require_login_and_role([1, 2]);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Visualizar Reglas</title>
    <link rel="icon" type="image/x-icon" href="../img/FDS_Favicon.png">
    <!-- Iconos de FontAwesome para botones y estados -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
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
        <span class="table_title">Reglas</span>
        <div class="controls">
          <!-- Botón para volver al menú principal -->
          <button
            class="btn"
            id="addUserBtn"
            onclick="window.location.href='index.php'"
          >
            <i class="fas fa-home"></i> Volver al menu
          </button>
          <!-- Botón para crear nueva regla -->
          <button
            class="btn"
            id="addUserBtn"
            onclick="window.location.href='admin_reglas.php'"
          >
            <i class="fas fa-plus"></i> Nueva regla
          </button>
          <!-- Botón para abrir modal de cápsula -->
          <button type="button" id="open-capsula-modal-btn" class="btn">
            + Crear Cápsula
          </button>
          <!-- Botón para ver cápsulas existentes -->
          <button
            type="button"
            id="open-capsula-modal-btn"
            class="btn"
            onclick="window.location.href='visualizar_capsulas.php'"
          >
            Ver Capsulas
          </button>
        </div>
      </div>

      <!-- =========================
             FILTRO DE ENTRADAS POR PÁGINA
             ========================= -->
      <div class="filter-controls">
        <select class="entries-select" id="entriesSelect">
          <option value="5">5 por página</option>
          <option value="10" selected>10 por página</option>
          <option value="25">25 por página</option>
          <option value="50">50 por página</option>
        </select>
      </div>

      <div style="background: #e6f7ff; border: 1px solid #91d5ff; border-radius: 8px; padding: 18px 24px; margin-bottom: 32px; text-align: left; color: #274c5e; font-size: 1.08em;">
        <strong>Antes de empezar:</strong>
        <ul style="margin-top: 10px;">
          <li>
            <b>¿Cómo leer las reglas?</b>
            <ul>
              <li>Cada regla está compuesta por condiciones y valores permitidos. Lee cuidadosamente cada condición para entender cuándo se aplica la regla.</li>
              <li>Los valores permitidos indican qué sucede cuando se cumplen las condiciones (por ejemplo, asignación de valores, restricciones, etc.).</li>
              <li>Presta atención a los detalles de cada campo y operador utilizado en la regla.</li>
            </ul>
          </li>
          <li>
            <b>¿Cómo funciona esta sección?</b>
            <ul>
              <li>En esta sección puedes visualizar todas las reglas configuradas en el sistema.</li>
              <li>Puedes filtrar, buscar o expandir reglas para ver más detalles según sea necesario.</li>
              <li>Si tienes dudas sobre el significado de una regla, contacta al área de soporte.</li>
            </ul>
          </li>
          <li>
            <b>Recomendaciones:</b>
            <ul>
              <li>Lee cada regla con atención antes de realizar cambios o tomar decisiones basadas en ellas.</li>
              <li>Evita modificar reglas si no estás seguro de su impacto.</li>
              <li>Si detectas una inconsistencia o error, repórtalo al administrador del sistema.</li>
            </ul>
          </li>
        </ul>
      </div>

      <!-- =========================
             TABLA DE REGLAS Y ESTADOS
             ========================= -->
      <div id="tableContainer">
        <table id="dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre de la Regla</th>
              <th>Campo de que afecta</th>
              <th>Condiciones</th>
              <th>Valores Permitidos</th>
              <th>Activo (1 = activo, 0 = inactivo)</th>
              <th>Fecha de creacion</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="userTableBody">
            <!-- Las filas de reglas se insertan dinámicamente aquí -->
          </tbody>
        </table>

        <!-- Indicador de carga mientras se obtienen los datos -->
        <div id="loadingIndicator" class="loading">
          <i class="fas fa-spinner"></i>
          <p>Cargando reglas...</p>
        </div>

        <!-- Estado vacío si no hay reglas -->
        <div id="emptyState" class="empty-state" style="display: none">
          <i
            class="fas fa-rule-slash"
            style="font-size: 40px; margin-bottom: 15px"
          ></i>
          <h3>No se encontraron reglas</h3>
          <p>No hay reglas que coincidan con tu búsqueda.</p>
        </div>
      </div>

      <!-- =========================
             PAGINACIÓN
             ========================= -->
      <div class="pagination">
        <div class="page-info">
          Mostrando <span id="startEntry">1</span> a
          <span id="endEntry">10</span> de
          <span id="totalEntries">0</span> reglas
        </div>
        <div class="page-btns" id="paginationContainer">
          <!-- Botones de paginación generados dinámicamente -->
        </div>
      </div>
    </div>

    <!-- =========================
         TOAST DE NOTIFICACIÓN
         ========================= -->
    <div id="toast" class="toast">
      <i class="fas fa-check-circle"></i>
      <span id="toastMessage">Operación realizada con éxito</span>
    </div>

    <!-- =========================
         MODAL PARA CREAR CÁPSULA
         ========================= -->
    <div
      id="capsula-modal"
      class="hidden"
      style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
      "
    >
      <div
        style="
          background: #fff;
          padding: 30px 20px 20px 20px;
          border-radius: 8px;
          min-width: 320px;
          max-width: 90vw;
          position: relative;
        "
      >
        <!-- Botón para cerrar el modal -->
        <button
          id="close-capsula-modal-btn"
          style="
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-size: 1.5em;
            color: #888;
            cursor: pointer;
          "
        >
          &times;
        </button>
        <h3>Crear Nueva Cápsula</h3>
        <form id="capsula-form">
          <label for="nombre_capsula">Nombre de la cápsula:</label>
          <input
            type="text"
            id="nombre_capsula"
            name="nombre_capsula"
            required
            maxlength="100"
            placeholder="Ej: Primavera 2024"
          />
          <div style="margin-top: 10px">
            <button type="submit" style="background-color: #5cb85c">
              Guardar Cápsula
            </button>
          </div>
          <div
            id="capsula-status-message"
            style="margin-top: 10px; display: none"
          ></div>
        </form>
      </div>
    </div>

    <!-- =========================
         SCRIPTS DE INTERACCIÓN
         ========================= -->
    <script>
      // =========================
      // MODAL DE CÁPSULA
      // =========================
      // Abrir modal de cápsula
      document.getElementById("open-capsula-modal-btn").onclick = function () {
        document.getElementById("capsula-modal").classList.remove("hidden");
        document.getElementById("nombre_capsula").value = "";
        document.getElementById("capsula-status-message").style.display =
          "none";
      };
      // Cerrar modal de cápsula
      document.getElementById("close-capsula-modal-btn").onclick = function () {
        document.getElementById("capsula-modal").classList.add("hidden");
      };

      // =========================
      // ENVÍO DEL FORMULARIO DE CÁPSULA
      // =========================
      document.getElementById("capsula-form").onsubmit = function (e) {
        e.preventDefault();
        const nombre = document.getElementById("nombre_capsula").value.trim();
        const statusDiv = document.getElementById("capsula-status-message");
        statusDiv.style.display = "none";
        statusDiv.textContent = "";
        statusDiv.className = "";

        // Validación simple
        if (!nombre) {
          statusDiv.textContent = "El nombre es obligatorio.";
          statusDiv.className = "error";
          statusDiv.style.display = "block";
          return;
        }

        // Enviar datos al backend para crear la cápsula
        fetch("../backend/crear_capsula.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ nombre: nombre }),
        })
          .then((resp) => resp.json())
          .then((data) => {
            if (data.success) {
              statusDiv.textContent = "¡Cápsula creada!";
              statusDiv.className = "success";
              statusDiv.style.display = "block";
              // Cerrar modal tras 1 segundo
              setTimeout(() => {
                document
                  .getElementById("capsula-modal")
                  .classList.add("hidden");
              }, 1000);
              // Aquí podrías recargar selects de cápsulas si existen
            } else {
              statusDiv.textContent =
                data.message || "Error al crear la cápsula.";
              statusDiv.className = "error";
              statusDiv.style.display = "block";
            }
          })
          .catch((err) => {
            statusDiv.textContent = "Error de red o servidor.";
            statusDiv.className = "error";
            statusDiv.style.display = "block";
          });
      };

      // =========================
      // FUNCIONES DE ACCIÓN DE REGLAS
      // =========================
      // Redirige a la página de edición de la regla
      function editarRegla(id) {
        window.location.href = `modificar_reglas_page.php?id=${id}`;
      }

      // Verifica si un usuario existe antes de editarlo (no usado en reglas)
      function verificarUsuario(id) {
        fetch("../backend/verificar_usuario.php?id=" + id)
          .then((response) => response.json())
          .then((data) => {
            if (data.existe) {
              window.location.href = `modificar_usuario.php?id=${id}`;
            }
          });
      }

      // Traduce el JSON de condiciones a texto legible para humanos
      function condicionesAHumano(condicionesJson) {
        let condiciones;
        if (typeof condicionesJson === "string") {
          try {
            condiciones = JSON.parse(condicionesJson);
          } catch (e) {
            return "Condiciones inválidas";
          }
        } else {
          condiciones = condicionesJson;
        }

        // Condición compuesta (AND/OR)
        if (condiciones.operator && condiciones.conditions) {
          const op =
            condiciones.operator === "AND"
              ? "y"
              : condiciones.operator === "OR"
              ? "o"
              : condiciones.operator;
          const partes = condiciones.conditions.map((cond) =>
            condicionesAHumano(cond)
          );
          return partes.join(` ${op} `);
        }

        // Condición simple
        if (
          condiciones.operator === "condition" &&
          condiciones.field &&
          condiciones.value !== undefined
        ) {
          return `${condiciones.field} es "${condiciones.value}"`;
        }

        // Condiciones ramificadas
        if (
          condiciones.type === "branched_conditions" &&
          Array.isArray(condiciones.branches)
        ) {
          let texto = condiciones.branches
            .map((branch) => {
              let ifDesc = branch.if ? condicionesAHumano(branch.if) : "";
              let thenAllow =
                branch.then_allow && branch.then_allow.length > 0
                  ? `entonces permitir: ${branch.then_allow
                      .map((v) => `"${v}"`)
                      .join(", ")}`
                  : "";
              return `Si ${ifDesc}, ${thenAllow}`;
            })
            .join("; ");
          if (
            condiciones.default_allow &&
            condiciones.default_allow.length > 0
          ) {
            texto += `. Por defecto permitir: ${condiciones.default_allow
              .map((v) => `"${v}"`)
              .join(", ")}`;
          }
          return texto;
        }

        // Fallback para otros casos
        return JSON.stringify(condiciones);
      }

      // =========================
      // CARGA Y RENDERIZADO DE REGLAS
      // =========================
      document.addEventListener("DOMContentLoaded", function () {
        // Variables globales para datos y paginación
        let usersData = [];
        let filteredUsers = [];
        let currentPage = 1;
        let entriesPerPage = 10;

        // Elementos del DOM
        const tableBody = document.getElementById("userTableBody");
        const loadingIndicator = document.getElementById("loadingIndicator");
        const emptyState = document.getElementById("emptyState");
        const searchInput = document.getElementById("searchInput");
        const entriesSelect = document.getElementById("entriesSelect");
        const paginationContainer = document.getElementById(
          "paginationContainer"
        );
        const startEntrySpan = document.getElementById("startEntry");
        const endEntrySpan = document.getElementById("endEntry");
        const totalEntriesSpan = document.getElementById("totalEntries");
        const addUserBtn = document.getElementById("addUserBtn");
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toastMessage");

        // Carga las reglas desde el backend
        function loadUsersData() {
              loadingIndicator.style.display = "block";
              document.getElementById("dataTable").style.display = "none";
            
              fetch("../api/obtener_reglas.php")
                .then((response) => {
                  if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                  }
                  return response.text(); // <-- obtenemos texto crudo
                })
                .then((text) => {
                  console.log("Respuesta cruda del backend:", text); // <-- depuración
                  let data;
                  try {
                    data = JSON.parse(text);
                  } catch (e) {
                    console.error("Error al parsear JSON:", e);
                    emptyState.style.display = "block";
                    loadingIndicator.style.display = "none";
                    document.getElementById("dataTable").style.display = "none";
                    updatePageInfo(0, 0, 0);
                    return;
                  }
                  // Mapeo correcto de campos para reglas
                  usersData = data.map((rule) => ({
                    ...rule,
                    estado: rule.es_activa == 1 ? "Activo" : "Inactivo",
                  }));
                  filteredUsers = [...usersData];
            
                  loadingIndicator.style.display = "none";
                  updateTable();
                })
                .catch((error) => {
                  console.error("Error al cargar reglas:", error);
                  loadingIndicator.style.display = "none";
                  emptyState.style.display = "block";
                  document.getElementById("dataTable").style.display = "none";
                  updatePageInfo(0, 0, 0);
                });
            }

        // Llama a la función de carga al iniciar
        loadUsersData();

        // =========================
        // FUNCIONES DE UI
        // =========================

        // Muestra un toast de notificación
        function showToast(message) {
          toastMessage.textContent = message;
          toast.classList.add("show");
          setTimeout(() => {
            toast.classList.remove("show");
          }, 3000);
        }

        // Renderiza la tabla con los datos filtrados y paginados
        function updateTable() {
          tableBody.innerHTML = "";

          if (filteredUsers.length === 0) {
            document.getElementById("dataTable").style.display = "none";
            emptyState.style.display = "block";
            paginationContainer.innerHTML = "";
            updatePageInfo(0, 0, 0);
            return;
          }

          document.getElementById("dataTable").style.display = "table";
          emptyState.style.display = "none";

          const start = (currentPage - 1) * entriesPerPage;
          const end = Math.min(start + entriesPerPage, filteredUsers.length);
          const paginatedUsers = filteredUsers.slice(start, end);

          paginatedUsers.forEach((rule) => {
            const row = document.createElement("tr");
            // Traduce condiciones a texto legible
            let condicionesLegibles = condicionesAHumano(rule.condiciones);
            row.innerHTML = `
                    <td>${rule.id}</td>
                    <td>${rule.nombre_regla}</td>
                    <td>${rule.campo_destino}</td>
                    <td>${condicionesLegibles}</td>
                    <td>${rule.valores_permitidos}</td>
                    <td>${rule.es_activa}</td>
                    <td>${rule.fecha_creacion}</td>
                    <td>
                        <button class="action-btn edit-btn" onclick="editarRegla(${
                          rule.id
                        })">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        ${
                          rule.estado === "Activo"
                            ? `
                        <button class="action-btn retire-btn" onclick="eliminarregla(${rule.id})">
                            <i class="fas fa-rule-minus"></i> Eliminar
                        </button>
                        `
                            : ""
                        }
                    </td>
                `;
            row.style.animation = `fadeIn 0.3s ease-in-out forwards ${
              0.05 * (row.rowIndex || 0)
            }s`;
            tableBody.appendChild(row);
          });

          updatePagination();
          updatePageInfo(start + 1, end, filteredUsers.length);
        }

        // Actualiza los botones de paginación
        function updatePagination() {
          paginationContainer.innerHTML = "";

          const totalPages = Math.ceil(filteredUsers.length / entriesPerPage);

          // Botón anterior
          const prevBtn = createPageButton(
            '<i class="fas fa-chevron-left"></i>',
            currentPage > 1
          );
          if (currentPage === 1) {
            prevBtn.classList.add("disabled");
          }
          prevBtn.addEventListener("click", () => {
            if (currentPage > 1) {
              currentPage--;
              updateTable();
            }
          });
          paginationContainer.appendChild(prevBtn);

          // Botones de página
          let startPage = Math.max(1, currentPage - 2);
          let endPage = Math.min(totalPages, startPage + 4);

          if (endPage - startPage < 4 && startPage > 1) {
            startPage = Math.max(1, endPage - 4);
          }

          for (let i = startPage; i <= endPage; i++) {
            const pageBtn = createPageButton(i, true);
            if (i === currentPage) {
              pageBtn.classList.add("active");
            }
            pageBtn.addEventListener("click", () => {
              currentPage = i;
              updateTable();
            });
            paginationContainer.appendChild(pageBtn);
          }

          // Botón siguiente
          const nextBtn = createPageButton(
            '<i class="fas fa-chevron-right"></i>',
            currentPage < totalPages
          );
          if (currentPage === totalPages || totalPages === 0) {
            nextBtn.classList.add("disabled");
          }
          nextBtn.addEventListener("click", () => {
            if (currentPage < totalPages) {
              currentPage++;
              updateTable();
            }
          });
          paginationContainer.appendChild(nextBtn);
        }

        // Crea un botón de paginación
        function createPageButton(content, enabled) {
          const button = document.createElement("button");
          button.className = "page-btn";
          button.innerHTML = content;
          if (!enabled) {
            button.classList.add("disabled");
          }
          return button;
        }

        // Actualiza la información de la página actual
        function updatePageInfo(start, end, total) {
          startEntrySpan.textContent = total > 0 ? start : 0;
          endEntrySpan.textContent = end;
          totalEntriesSpan.textContent = total;
        }

        // =========================
        // EVENTOS DE FILTRO Y SELECT
        // =========================

        // Filtro de búsqueda (debería haber un input con id="searchInput" en el HTML)
        searchInput &&
          searchInput.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase();
            filteredUsers = usersData.filter(
              (rule) =>
                rule.nombre.toLowerCase().includes(searchTerm) ||
                rule.apellido.toLowerCase().includes(searchTerm) ||
                rule.username.toLowerCase().includes(searchTerm)
            );
            currentPage = 1;
            updateTable();
          });

        // Cambia la cantidad de entradas por página
        entriesSelect.addEventListener("change", function () {
          entriesPerPage = parseInt(this.value);
          currentPage = 1;
          updateTable();
        });
      });

      // =========================
      // ELIMINAR UNA REGLA
      // =========================
      function eliminarregla(ruleId) {
        // Confirmación antes de eliminar
        const confirmed = confirm(
          `¿Estás seguro de que quieres eliminar la regla con ID ${ruleId}? Esta acción no se puede deshacer.`
        );

        if (confirmed) {
          // Redirige al backend para eliminar la regla
          const deleteUrl = `../backend/delete_rule.php?id=${ruleId}`;
          window.location.href = deleteUrl;
        } else {
          // Cancelación de la eliminación
          console.log(`Eliminación del usuario con ID ${ruleId} cancelada.`);
        }
      }
    </script>
  </body>
</html>
