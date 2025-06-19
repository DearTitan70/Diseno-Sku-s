<?php
require_once __DIR__ . '/../backend/auth.php';

// Permite acceso a todos
require_login_and_role();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Historico de Cargas</title>
    <link rel="icon" type="image/x-icon" href="../img/FDS_Favicon.png">
    <script src="../js/visualizar_cargas_mejorado.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <style>
        /*Estilo para headers dinamicos*/
        .dynamic-header th {
            background: #879683;
            color: #fff;
            font-weight: bold;
            border-bottom: 2px solid #879683;
        }

        /*Estilo para seccion de exportar*/

        .export {
            background-color: white;
            margin: 24px;
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            border-left: 4px solid var(--color-primary);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .export label {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 0.95rem;
            color: var(--color-text-dark);
            font-weight: 500;
            flex: 1;
            min-width: 200px;
            transition: var(--transition);
        }

        .export input {
            padding: 12px 16px;
            border: 1px solid var(--color-highlight);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            outline: none;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .export input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
            background-color: white;
        }
        .selected-row {
        background-color:rgb(10, 83, 38) !important;
        }
        /* ==============================
           VARIABLES CSS PARA COLORES Y ESTILOS
        ============================== */
        :root {
            --color-background: #F9F3E5; /* Fondo claro y elegante */
            --color-text-dark: #000000; /* Texto oscuro principal */
            --color-primary: #879683; /* Verde/Gris principal, para elementos interactivos */
            --color-secondary: #5A6B58; /* Un tono más oscuro del primario, para hover/activos */
            --color-highlight: #C5D4C1; /* Un tono más claro, para bordes o detalles */
            --color-logout: #a0a0a0; /* Gris para el botón de cerrar sesión */
            --color-logout-hover: #8a8a8a; /* Gris más oscuro para hover de cerrar sesión */
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --radius: 8px;
            --transition: all 0.3s ease;
        }

        /* ==============================
           ESTILOS GENERALES DEL BODY
        ============================== */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--color-background);
            color: var(--color-text-dark);
            line-height: 1.6;
            padding: 0;
            margin: 0;
            transition: var(--transition);
        }

        /* ==============================
           HEADER SUPERIOR
        ============================== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--color-primary);
            padding: 0 24px;
            height: 80px;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 10;
            transition: var(--transition);
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
            letter-spacing: -0.5px;
            margin: 0;
            transition: var(--transition);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* ==============================
           BOTONES
        ============================== */
        .btn {
            background-color: var(--color-secondary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            text-decoration: none;
        }

        .btn:hover {
            background-color: var(--color-secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* ==============================
           FILTROS DE BUSQUEDA
        ============================== */
        .filters {
            background-color: white;
            margin: 24px;
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            border-left: 4px solid var(--color-primary);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .filters label {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 0.95rem;
            color: var(--color-text-dark);
            font-weight: 500;
            flex: 1;
            min-width: 200px;
            transition: var(--transition);
        }

        .filters input {
            padding: 12px 16px;
            border: 1px solid var(--color-highlight);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            outline: none;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .filters input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
            background-color: white;
        }

        /* ==============================
           CONTENEDOR DE LA TABLA
        ============================== */
        .table_container {
            overflow: auto;
            max-height: 70vh;
            margin: 0 24px 24px;
            background-color: white;
            padding: 0;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--color-highlight);
            animation: slideUp 0.6s ease-in-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==============================
           ESTILOS DE LA TABLA
        ============================== */
        table {
            border-spacing: 0;
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        thead {
            position: sticky;
            top: 0;
            background-color: var(--color-primary);
            z-index: 5;
        }

        th {
            padding: 16px 20px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            transition: var(--transition);
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(197, 212, 193, 0.3);
            color: var(--color-text-dark);
            transition: var(--transition);
        }

        tbody tr {
            transition: var(--transition);
            background-color: white;
        }

        tbody tr:nth-child(even) {
            background-color: rgba(197, 212, 193, 0.1);
        }

        tbody tr:hover {
            background-color: rgba(197, 212, 193, 0.3);
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: var(--color-text-dark);
            font-style: italic;
            background-color: rgba(255, 255, 255, 0.9);
        }

        /* ==============================
           ESTILOS DEL SCROLLBAR
        ============================== */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-highlight);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }

        /* ==============================
           DISEÑO RESPONSIVO
        ============================== */
        @media (max-width: 900px) {
            .header {
                padding: 0 16px;
                height: 70px;
            }
            
            .header h1 {
                font-size: 1.4rem;
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
                margin: 16px;
                padding: 16px;
            }
            
            .filters a {
                width: 100%;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
                padding: 10px 16px;
            }
            
            .table_container {
                margin: 0 16px 16px;
            }
            
            th, td {
                padding: 12px 10px;
                font-size: 0.9rem;
            }
        }

        #exportarXLSX {
            background: #879683; 
        }

        /* ==============================
           ANIMACIONES DE FILAS DE TABLA
        ============================== */
        @keyframes rowFadeIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        #tabla-visualizar-cargas tr {
            animation: rowFadeIn 0.3s ease-in-out;
            animation-fill-mode: both;
        }

        /* Retrasos para animar filas de la tabla */
        #tabla-visualizar-cargas tr:nth-child(1) { animation-delay: 0.05s; }
        #tabla-visualizar-cargas tr:nth-child(2) { animation-delay: 0.1s; }
        #tabla-visualizar-cargas tr:nth-child(3) { animation-delay: 0.15s; }
        #tabla-visualizar-cargas tr:nth-child(4) { animation-delay: 0.2s; }
        #tabla-visualizar-cargas tr:nth-child(5) { animation-delay: 0.25s; }
        #tabla-visualizar-cargas tr:nth-child(6) { animation-delay: 0.3s; }
        #tabla-visualizar-cargas tr:nth-child(7) { animation-delay: 0.35s; }
        #tabla-visualizar-cargas tr:nth-child(8) { animation-delay: 0.4s; }
        #tabla-visualizar-cargas tr:nth-child(9) { animation-delay: 0.45s; }
        #tabla-visualizar-cargas tr:nth-child(10) { animation-delay: 0.5s; }

        .btn-modificar {
            background-color: #f0ad4e;
            color: white;
        }
        .btn-modificar:hover {
            background-color: #ec971f;
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>Visualizar Historico Cargas</h1>
        <a href="index.php" class="icon-right">
            <button class="btn">
                <i class="fas fa-arrow-left"></i> Regresar
            </button>
        </a>
        <script src="../js/creacion_consecutivos.js"></script>
    </div>
    <div style="background: #e6f7ff; border: 1px solid #91d5ff; border-radius: 8px; padding: 18px 24px; margin: 24px 24px 0 24px; text-align: left; color: #274c5e; font-size: 1.08em;">
        <strong>Antes de empezar:</strong>
        <ul style="margin-top: 10px;">
            <li>
            <b>¿Cómo leer el histórico de cargas?</b>
            <ul>
                <li>Cada registro corresponde a una carga realizada en la plataforma, mostrando información relevante como fecha, usuario, ID y detalles asociados.</li>
                <li>Revisa cuidadosamente los datos de cada fila para identificar el origen y contenido de cada carga.</li>
                <li>Si necesitas más detalles de una carga específica, utiliza los filtros para ajustar la búsqueda.</li>
            </ul>
            </li>
            <li>
            <b>¿Cómo funcionan los filtros?</b>
            <ul>
                <li>Puedes filtrar los registros por rango de fechas, ID o usuario para encontrar cargas específicas de manera rápida y eficiente.</li>
                <li>Utiliza los campos de búsqueda antes de la tabla para refinar los resultados según tus necesidades.</li>
                <li>El botón "Exportar a XLSX" te permite descargar los resultados filtrados en formato Excel para su análisis o respaldo.</li>
            </ul>
            </li>
            <li>
            <b>Recomendaciones:</b>
            <ul>
                <li>Verifica siempre la información antes de tomar decisiones o realizar modificaciones basadas en el histórico.</li>
                <li>Si detectas inconsistencias o errores, repórtalos al administrador del sistema.</li>
                <li>Recuerda que la información aquí mostrada es sensible y debe ser manejada con responsabilidad.</li>
            </ul>
            </li>
        </ul>
        </div>

    <!-- ==============================
         FILTROS DE BUSQUEDA POR FECHA, USUARIO E ID
    ============================== -->
    <div class="filters">
            <button class="btn" id="exportarXLSX" type="button" style="margin-left:20px;>
            <i class="fas fa-file-excel"></i> Exportar a XLSX
        </button>
        <label>
            Desde
            <input type="date" id="filterDateFrom">
        </label>
        <label>
            Hasta
            <input type="date" id="filterDateTo">
        </label>
        <label>
            Nombre
            <input type="text" id="filterName" placeholder="Filtrar por Nombre" style="width:350px;">
        </label>
        
        <label>
            Usuario
            <input type="text" id="filterUsuario" placeholder="Filtrar por usuario" style="width:350px;">
        </label>
    </div>

    <div class="table_container">
        <table id="dataTable">
            <tbody id="tabla-visualizar-cargas">
            </tbody>
    </div>
</body>
</html>