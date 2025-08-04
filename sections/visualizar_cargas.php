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
    <script src="../js/visualizar_cargas_mejorado.js"></script>
    <script src="../js/asignar_precio_modal.js"></script>
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
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            text-decoration: none;
        }
        
        .btn_error {
            background-color: #F54927;
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
        
        .btn_error:hover {
            background-color: #D92400;
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
            background-color: var(--color-primary);
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
            <input type="text" id="filterName" placeholder="Filtrar por Nombre" style="width:350px;" oninput="this.value=this.value.toUpperCase();">
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
<div id="modalCrearVariacion" style="display:none; position:fixed; top:0; left:0; width:100%; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; padding:32px; border-radius:12px; min-width:300px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.2);">
        <h2>Crear Variación</h2>
        <form id="formCrearVariacion">
            <input type="hidden" id="variacionIdOriginal">
            <label style="margin-bottom: 12px; display: block;">
                Nuevo Nombre (opcional):
                <input type="text" id="variacionNombre" placeholder="Dejar en blanco para usar el nombre original" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </label>
            <label>
                Talla(s) (Ctrl+Click para varias):
                <select id="variacionTalla" multiple size="5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <!-- <option value="igual">Dejar igual</option> -->
                    <option value="XXS">XXS</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="UN">UN</option>
                    <option value="T">T</option>
                    <option value="EP">EP</option>
                    <option value="P">P</option>
                    <option value="G">G</option>
                    <option value="EG">EG</option>
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
                </select>
            </label>
            <label style="margin-top: 12px;">
                Color(es) (Ctrl+Click para varios):
                <select id="variacionColor" multiple size="5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <!-- Opciones generadas por JS -->
                </select>
            </label>
            <div style="margin-top:18px; display:flex; gap:12px;">
                <button type="submit" class="btn btn-modificar">Crear Variación</button>
                <button type="button" class="btn" id="cerrarModalCrearVariacion" style="background:#a0a0a0;">Cancelar</button>
            </div>
        </form>
        <div id="resultadoCrearVariacion" style="margin-top:12px; color:#274c5e;"></div>
    </div>
</div>
<script>
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

// Asume que COLORES_FDS es un array global de colores disponibles
function llenarOpcionesColorVariacion() {
    const select = document.getElementById('variacionColor');
    if (!select) return;
    select.innerHTML = ''; // Limpiar opciones anteriores
    if (typeof COLORES_FDS !== 'undefined' && Array.isArray(COLORES_FDS)) {
        COLORES_FDS.forEach(c => {
            select.innerHTML += `<option value="${c.codigo}">${c.codigo} - ${c.nombre} (${c.gama})</option>`;
        });
    }
}

// Evento para abrir el modal
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-crear-variacion')) {
        const id = e.target.getAttribute('data-id');
        document.getElementById('variacionIdOriginal').value = id;
        document.getElementById('variacionNombre').value = ''; // Limpiar campo de nombre
        document.getElementById('variacionTalla').selectedIndex = -1; // Deseleccionar todo
        document.getElementById('variacionColor').selectedIndex = -1; // Deseleccionar todo
        llenarOpcionesColorVariacion();
        document.getElementById('modalCrearVariacion').style.display = 'flex';
        document.getElementById('resultadoCrearVariacion').innerText = '';
    }
});

// Cerrar modal
document.getElementById('cerrarModalCrearVariacion').onclick = function() {
    document.getElementById('modalCrearVariacion').style.display = 'none';
    document.getElementById('resultadoCrearVariacion').innerText = '';
};

// Enviar formulario
document.getElementById('formCrearVariacion').onsubmit = function(e) {
    e.preventDefault();
    const idOriginal = document.getElementById('variacionIdOriginal').value;
    const nuevoNombre = document.getElementById('variacionNombre').value.trim();
    const nuevasTallas = Array.from(document.getElementById('variacionTalla').selectedOptions).map(opt => opt.value);
    const nuevosColores = Array.from(document.getElementById('variacionColor').selectedOptions).map(opt => opt.value);

    if (nuevasTallas.length === 0 && nuevosColores.length === 0) {
        document.getElementById('resultadoCrearVariacion').innerText = 'Error: Debes seleccionar al menos una nueva talla o un nuevo color.';
        return;
    }

    fetch('../backend/crear_variacion.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            id: idOriginal,
            tallas: nuevasTallas.length > 0 ? nuevasTallas : 'igual',
            colores: nuevosColores.length > 0 ? nuevosColores : 'igual',
            nuevo_nombre: nuevoNombre
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('resultadoCrearVariacion').innerText = `¡${data.creados || 1} variaciones creadas con éxito!`;
            setTimeout(() => {
                document.getElementById('modalCrearVariacion').style.display = 'none';
                // location.reload(); // Recargar para ver los cambios
            }, 1200);
        } else {
            document.getElementById('resultadoCrearVariacion').innerText = 'Error: ' + (data.message || 'No se pudo crear la variación.');
        }
    })
    .catch(err => {
        document.getElementById('resultadoCrearVariacion').innerText = 'Error de red o servidor.';
    });
};
</script>
<div id="modalAsignarPrecio" style="display:none; position:fixed; top:0; left:0; width:100%; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; padding:32px; border-radius:12px; min-width:320px; max-width:95vw; box-shadow:0 8px 32px rgba(0,0,0,0.2);">
        <h2>Asignar Precio</h2>
        <form id="formAsignarPrecio">
            <input type="hidden" id="precioIdOriginal">
            <input type="hidden" id="precioNombreOriginal">
            <label>
                Precio de compra:
                <input type="number" id="precioCompra" min="0" step="0.01" required>
            </label>
            <label>
                Costo:
                <input type="number" id="precioCosto" min="0" step="0.01" required>
            </label>
            <label>
                Precio de venta:
                <input type="number" id="precioVenta" min="0" step="0.01" required>
            </label>
            <label>
                Orden de compra asociada:
                <input type="number" id="ordenCompra" required>
            </label>
            <label style="margin-top:10px;">
                <input type="checkbox" id="aplicarATodos"> ¿Aplicar a todos los registros con el mismo nombre?
            </label>
            <div id="mensajePrecioExistente" style="color:#b36b00; margin:10px 0 0 0; font-size:0.98em;"></div>
            <div style="margin-top:18px; display:flex; gap:12px;">
                <button type="submit" class="btn btn-modificar">Guardar</button>
                <button type="button" class="btn" id="cerrarModalAsignarPrecio" style="background:#a0a0a0;">Cancelar</button>
            </div>
        </form>
        <div id="resultadoAsignarPrecio" style="margin-top:12px; color:#274c5e;"></div>
    </div>
</div>
</body>
</html>