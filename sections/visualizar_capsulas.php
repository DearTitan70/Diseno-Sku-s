<?php
require_once __DIR__ . '/../backend/auth.php';

// Permite acceso a admin (1)
require_login_and_role(1);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Visualizar Capsulas</title>
    <link rel="icon" type="image/x-icon" href="../img/FDS_Favicon.png">
    <!-- Iconos de FontAwesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      /* =========================
           VARIABLES DE COLOR Y ESTILO
           ========================= */
      :root {
        --color-background: #f9f3e5;
        --color-text-dark: #000000;
        --color-primary: #879683;
        --color-secondary: #5a6b58;
        --color-highlight: #c5d4c1;
        --color-logout: #a0a0a0;
        --color-logout-hover: #8a8a8a;
        --border-radius: 8px;
        --box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      }

      /* =========================
           RESET Y TIPOGRAFÍA
           ========================= */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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
        max-width: 1800px;
        margin: 20px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        animation: fadeIn 0.8s ease-in-out;
        border: 1px solid var(--color-highlight);
      }

      /* =========================
           CABECERA DE LA TABLA
           ========================= */
      .table_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        margin-bottom: 30px;
        border-bottom: 2px solid var(--color-primary);
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
      }

      .table_title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 15%;
        height: 70%;
        width: 6px;
        background-color: var(--color-primary);
        border-radius: var(--border-radius);
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
        width: 300px;
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
        font-weight: 500;
        font-size: 15px;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .btn:hover {
        background-color: var(--color-secondary);
        transform: translateY(-3px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
      }

      .btn:active {
        transform: translateY(-1px);
      }

      .btn i {
        font-size: 16px;
      }

      /* =========================
           FILTRO DE ENTRADAS
           ========================= */
      .filter-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 0 5px;
      }

      .entries-select {
        padding: 10px 15px;
        border: 2px solid var(--color-highlight);
        border-radius: var(--border-radius);
        transition: var(--transition);
        font-size: 15px;
        background-color: #fff;
        color: var(--color-text-dark);
        cursor: pointer;
        min-width: 150px;
      }

      .entries-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
      }

      /* =========================
           TABLA DE DATOS
           ========================= */
      table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 20px;
        overflow: hidden;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      }

      thead {
        background-color: var(--color-primary);
        color: white;
      }

      th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 15px;
        position: relative;
      }

      th:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: rgba(255, 255, 255, 0.2);
      }

      tbody tr {
        border-bottom: 1px solid #eee;
        transition: var(--transition);
        background-color: #fff;
      }

      tbody tr:nth-child(even) {
        background-color: rgba(197, 212, 193, 0.1);
      }

      tbody tr:hover {
        background-color: rgba(135, 150, 131, 0.08);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      }

      td {
        padding: 16px;
        vertical-align: middle;
        font-size: 15px;
        border-bottom: 1px solid #f0f0f0;
      }

      /* =========================
           BOTONES DE ACCIÓN EN TABLA
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
        margin-right: 8px;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .edit-btn {
        background-color: var(--color-primary);
        color: white;
      }

      .edit-btn:hover {
        background-color: var(--color-secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
      }

      .view-btn {
        background-color: var(--color-highlight);
        color: var(--color-text-dark);
      }

      .view-btn:hover {
        background-color: #b3c5ae;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
      }

      .retire-btn {
        background-color: #dc3545;
        color: white;
      }

      .retire-btn:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
      }

      /* =========================
           PAGINACIÓN
           ========================= */
      .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding: 20px 5px;
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
        font-weight: 500;
        color: var(--color-text-dark);
      }

      .page-btn:hover,
      .page-btn.active {
        background-color: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
           ANIMACIONES Y ESTADOS
           ========================= */
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes slideIn {
        from {
          opacity: 0;
          transform: translateX(30px);
        }
        to {
          opacity: 1;
          transform: translateX(0);
        }
      }

      .loading {
        text-align: center;
        padding: 60px;
        color: var(--color-primary);
        animation: pulse 1.5s infinite alternate;
      }

      .loading i {
        font-size: 50px;
        margin-bottom: 15px;
        animation: spin 1.2s linear infinite;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      @keyframes pulse {
        from {
          opacity: 0.7;
        }
        to {
          opacity: 1;
        }
      }

      .empty-state {
        text-align: center;
        padding: 60px;
        color: var(--color-text-dark);
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: var(--border-radius);
        border: 1px dashed var(--color-highlight);
        margin: 20px 0;
        animation: fadeIn 0.5s ease-in-out;
      }

      .empty-state i {
        font-size: 50px;
        color: var(--color-primary);
        margin-bottom: 20px;
        opacity: 0.8;
      }

      .empty-state h3 {
        font-size: 22px;
        margin-bottom: 10px;
        color: var(--color-primary);
      }

      .empty-state p {
        font-size: 16px;
        max-width: 400px;
        margin: 0 auto;
        color: var(--color-text-dark);
        opacity: 0.8;
      }

      /* =========================
           BADGES Y TOASTS
           ========================= */
      .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.3px;
      }

      .badge-admin {
        background-color: #ffe6cc;
        color: #ff8c00;
      }

      .badge-rule {
        background-color: #e6f7ff;
        color: #0099cc;
      }

      .toast {
        position: fixed;
        top: 30px;
        right: 30px;
        background-color: var(--color-primary);
        color: white;
        padding: 16px 25px;
        border-radius: var(--border-radius);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 1000;
        font-weight: 500;
        min-width: 300px;
      }

      .toast.show {
        transform: translateX(0);
      }

      .toast i {
        font-size: 22px;
      }

      /* =========================
           ESTILOS PARA ESTADO DE CÁPSULAS
           ========================= */
      td:nth-child(3) {
        font-weight: 500;
      }

      td:nth-child(3):contains("Activo") {
        color: #198754;
      }

      td:nth-child(3):contains("Inactivo") {
        color: #dc3545;
      }

      /* =========================
           ANIMACIÓN PARA FILAS DE TABLA
           ========================= */
      tbody tr {
        opacity: 0;
        animation: slideIn 0.5s forwards;
      }

      tbody tr:nth-child(1) {
        animation-delay: 0.05s;
      }
      tbody tr:nth-child(2) {
        animation-delay: 0.1s;
      }
      tbody tr:nth-child(3) {
        animation-delay: 0.15s;
      }
      tbody tr:nth-child(4) {
        animation-delay: 0.2s;
      }
      tbody tr:nth-child(5) {
        animation-delay: 0.25s;
      }
      tbody tr:nth-child(6) {
        animation-delay: 0.3s;
      }
      tbody tr:nth-child(7) {
        animation-delay: 0.35s;
      }
      tbody tr:nth-child(8) {
        animation-delay: 0.4s;
      }
      tbody tr:nth-child(9) {
        animation-delay: 0.45s;
      }
      tbody tr:nth-child(10) {
        animation-delay: 0.5s;
      }

      /* =========================
           RESPONSIVE PARA MÓVILES
           ========================= */
      @media (max-width: 768px) {
        .container {
          padding: 15px;
        }

        .table_header {
          flex-direction: column;
          align-items: flex-start;
          gap: 15px;
        }

        .controls {
          width: 100%;
          justify-content: space-between;
        }

        .search-box {
          width: 100%;
        }

        table {
          display: block;
          overflow-x: auto;
        }

        .pagination {
          flex-direction: column;
          gap: 15px;
          align-items: flex-start;
        }

        .page-btns {
          width: 100%;
          justify-content: center;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- =========================
             CABECERA DE LA TABLA Y BOTÓN DE VOLVER
             ========================= -->
      <div class="table_header">
        <span class="table_title">Capsulas</span>
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

      <!-- =========================
             TABLA DE CÁPSULAS Y ESTADOS
             ========================= -->
      <div id="tableContainer">
        <table id="dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre de la Capsula</th>
              <th>Estado</th>
              <th>Fecha de creacion</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="capsuleTableBody">
            <!-- Las filas de cápsulas se insertan dinámicamente aquí -->
          </tbody>
        </table>

        <!-- Indicador de carga mientras se obtienen los datos -->
        <div id="loadingIndicator" class="loading">
          <i class="fas fa-spinner"></i>
          <p>Cargando reglas...</p>
        </div>

        <!-- Estado vacío si no hay cápsulas -->
        <div id="emptyState" class="empty-state" style="display: none">
          <i
            class="fas fa-rule-slash"
            style="font-size: 40px; margin-bottom: 15px"
          ></i>
          <h3>No se encontraron capsulas</h3>
          <p>No hay capsulas que coincidan con tu búsqueda.</p>
        </div>
      </div>

      <!-- =========================
             PAGINACIÓN
             ========================= -->
      <div class="pagination">
        <div class="page-info">
          Mostrando <span id="startEntry">1</span> a
          <span id="endEntry">10</span> de
          <span id="totalEntries">0</span> capsulas
        </div>
        <div class="page-btns" id="paginationContainer">
          <!-- Los botones de paginación se insertarán aquí -->
        </div>
      </div>
    </div>

    <!-- =========================
         NOTIFICACIÓN TOAST
         ========================= -->
    <div id="toast" class="toast">
      <i class="fas fa-check-circle"></i>
      <span id="toastMessage">Operación realizada con éxito</span>
    </div>

    <!-- =========================
         SCRIPT PRINCIPAL
         ========================= -->
    <script>
      /**
       * Script principal para la gestión de la tabla de cápsulas:
       * - Carga de datos desde el backend
       * - Renderizado de tabla y paginación
       * - Búsqueda y filtrado
       * - Eliminación de cápsulas
       * - Notificaciones toast
       */
      document.addEventListener("DOMContentLoaded", function () {
        // =========================
        // VARIABLES GLOBALES Y ELEMENTOS DOM
        // =========================
        let capsulasData = []; // Almacena todas las cápsulas obtenidas
        let filteredCapsulas = []; // Almacena las cápsulas filtradas por búsqueda
        let currentPage = 1; // Página actual de la tabla
        let entriesPerPage = 10; // Entradas por página (por defecto 10)

        // Elementos del DOM
        const tableBody = document.getElementById("capsuleTableBody");
        const loadingIndicator = document.getElementById("loadingIndicator");
        const emptyState = document.getElementById("emptyState");
        // NOTA: El input de búsqueda no existe en el HTML original, agregar si se requiere
        // const searchInput = document.getElementById("searchInput");
        const entriesSelect = document.getElementById("entriesSelect");
        const paginationContainer = document.getElementById(
          "paginationContainer"
        );
        const startEntrySpan = document.getElementById("startEntry");
        const endEntrySpan = document.getElementById("endEntry");
        const totalEntriesSpan = document.getElementById("totalEntries");
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toastMessage");

        // =========================
        // FUNCIÓN: CARGAR DATOS DE CÁPSULAS DESDE EL BACKEND
        // =========================
        function loadCapsuleData() {
          loadingIndicator.style.display = "block";
          document.getElementById("dataTable").style.display = "none";

          // Petición fetch al backend PHP para obtener las cápsulas
          fetch("../api/obtener_capsulas.php")
            .then((response) => {
              if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
              }
              return response.json();
            })
            .then((data) => {
              // Formatea los datos recibidos
              capsulasData = data.map((capsula) => ({
                id: capsula.id,
                nombre: capsula.nombre,
                estado: capsula.estado == 1 ? "Activo" : "Inactivo",
                fecha_creacion: capsula.created_at,
              }));
              filteredCapsulas = [...capsulasData];
              loadingIndicator.style.display = "none";
              updateTable();
            })
            .catch((error) => {
              // Manejo de errores en la carga de datos
              console.error("Error al cargar cápsulas:", error);
              loadingIndicator.style.display = "none";
              emptyState.style.display = "block";
              document.getElementById("dataTable").style.display = "none";
              updatePageInfo(0, 0, 0);
            });
        }

        // Llama a la función para cargar los datos al iniciar
        loadCapsuleData();

        // =========================
        // FUNCIÓN: MOSTRAR NOTIFICACIÓN TOAST
        // =========================
        function showToast(message) {
          toastMessage.textContent = message;
          toast.classList.add("show");
          setTimeout(() => {
            toast.classList.remove("show");
          }, 3000);
        }

        // =========================
        // FUNCIÓN: RENDERIZAR TABLA CON DATOS FILTRADOS Y PAGINADOS
        // =========================
        function updateTable() {
          tableBody.innerHTML = "";

          // Si no hay cápsulas, muestra el estado vacío
          if (filteredCapsulas.length === 0) {
            document.getElementById("dataTable").style.display = "none";
            emptyState.style.display = "block";
            paginationContainer.innerHTML = "";
            updatePageInfo(0, 0, 0);
            return;
          }

          document.getElementById("dataTable").style.display = "table";
          emptyState.style.display = "none";

          // Calcula el rango de cápsulas a mostrar en la página actual
          const start = (currentPage - 1) * entriesPerPage;
          const end = Math.min(start + entriesPerPage, filteredCapsulas.length);
          const paginatedCapsulas = filteredCapsulas.slice(start, end);

          // Inserta las filas en la tabla
          paginatedCapsulas.forEach((capsula) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${capsula.id}</td>
                <td>${capsula.nombre}</td>
                <td>${capsula.estado}</td>
                <td>${capsula.fecha_creacion}</td>
                <td>
                    <button class="action-btn retire-btn" title="Borrar cápsula" onclick="borrarCapsula(${capsula.id}, '${capsula.nombre}')">
                        <i class="fas fa-trash"></i> Borrar
                    </button>
                </td>
            `;
            // Animación de aparición de la fila
            row.style.animation = `fadeIn 0.3s ease-in-out forwards ${
              0.05 * (row.rowIndex || 0)
            }s`;
            tableBody.appendChild(row);
          });

          updatePagination();
          updatePageInfo(start + 1, end, filteredCapsulas.length);
        }

        // =========================
        // FUNCIÓN GLOBAL: BORRAR UNA CÁPSULA
        // =========================
        window.borrarCapsula = function (id, nombre) {
          if (
            !confirm(
              `¿Estás seguro de que deseas borrar la cápsula "${nombre}" (ID: ${id})? Esta acción no se puede deshacer.`
            )
          ) {
            return;
          }

          // Petición fetch al backend para borrar la cápsula
          fetch("../backend/borrar_capsula.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: id }),
          })
            .then((response) => response.json())
            .then((result) => {
              if (result.success) {
                // Elimina la cápsula del array y actualiza la tabla
                capsulasData = capsulasData.filter((c) => c.id !== id);
                filteredCapsulas = filteredCapsulas.filter((c) => c.id !== id);
                showToast("Cápsula borrada exitosamente");
                updateTable();
              } else {
                alert(result.message || "No se pudo borrar la cápsula.");
              }
            })
            .catch((error) => {
              alert("Error al borrar la cápsula.");
              console.error(error);
            });
        };

        // =========================
        // FUNCIÓN: ACTUALIZAR PAGINACIÓN
        // =========================
        function updatePagination() {
          paginationContainer.innerHTML = "";

          const totalPages = Math.ceil(
            filteredCapsulas.length / entriesPerPage
          );

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

          // Botones de página (máximo 5)
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

        // =========================
        // FUNCIÓN: CREAR BOTÓN DE PAGINACIÓN
        // =========================
        function createPageButton(content, enabled) {
          const button = document.createElement("button");
          button.className = "page-btn";
          button.innerHTML = content;
          if (!enabled) {
            button.classList.add("disabled");
          }
          return button;
        }

        // =========================
        // FUNCIÓN: ACTUALIZAR INFORMACIÓN DE PÁGINA
        // =========================
        function updatePageInfo(start, end, total) {
          startEntrySpan.textContent = total > 0 ? start : 0;
          endEntrySpan.textContent = end;
          totalEntriesSpan.textContent = total;
        }

        // Cambia la cantidad de entradas por página
        entriesSelect.addEventListener("change", function () {
          entriesPerPage = parseInt(this.value);
          currentPage = 1;
          updateTable();
        });
      });
    </script>
  </body>
</html>
