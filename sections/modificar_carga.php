<?php
require_once __DIR__ . '/../backend/auth.php';
require_login_and_role([1, 2]); 

$userName = $_SESSION['nombre'] ?? 'Usuario';
$userApellido = $_SESSION['apellido'] ?? 'Usuario';
$userRoleName = $_SESSION['user_role_name'] ?? 'Invitado';
date_default_timezone_set('America/Bogota');
$fecha_actual = date("Y-m-d H:i:s");
// Obtener el ID del registro a modificar
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


require_once '../conexion.php';
$conn->set_charset('utf8mb4');
include '../backend/get_cargas.php'; 
$carga = [];
if ($id > 0) {
    $carga = getCargaById($id);
    if (!$carga) {
        die("Registro no encontrado.");
    }
} else {
    die("ID no especificado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Carga</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/tallas_dinamicas.js"></script>
    <script src="../js/ocultar_columnas.js"></script>
    <script src="../js/filtrar_categorias.js"></script>
    <style>
        /* 
        =========================
        ESTILOS CSS PERSONALIZADOS
        =========================
        Definición de variables de color, estilos de tabla, botones, responsividad, etc.
        */
            :root {
            --color-background: #F9F3E5; /* Fondo claro y elegante */
            --color-text-dark: #000000; /* Texto oscuro principal */
            --color-primary: #879683; /* Verde/Gris principal, para elementos interactivos */
            --color-secondary: #5A6B58; /* Un tono más oscuro del primario, para hover/activos */
            --color-highlight: #C5D4C1; /* Un tono más claro, para bordes o detalles */
            --color-logout: #a0a0a0; /* Gris para el botón de cerrar sesión */
            --color-logout-hover: #8a8a8a; /* Gris más oscuro para hover de cerrar sesión */
            --color-error: #e74c3c;
            --color-table-header: #879683; /* Cambiado a color primario */
            --color-table-border: #C5D4C1; /* Cambiado a color highlight */
            --color-row-even: #f2f2f2;
            --color-delete-button: #c0392b;
            --color-delete-button-hover: #e74c3c;
            --color-white: #ffffff;
            --color-shadow: rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-white);
            color: var(--color-text-dark);
            margin: 0;
            padding: 0;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: var(--color-background);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: 0 8px 24px var(--color-shadow);
            max-width: 98%;
            margin: 30px auto;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
            border-top: 5px solid var(--color-primary);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container h2 {
            color: var(--color-primary);
            margin-top: 0;
            margin-bottom: 40px;
            font-size: 2.4em;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .container h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--color-highlight);
            border-radius: 2px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 40px;
            border: 1px solid var(--color-table-border);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 5px;
        }

        #skuTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1200px;
        }

        #skuTable th,
        #skuTable td {
            border: 1px solid var(--color-table-border);
            padding: 15px 12px;
            text-align: center;
            vertical-align: middle;
            text-wrap: nowrap;
            transition: background-color var(--transition-speed);
        }

        #skuTable th {
            background-color: var(--color-table-header);
            color: var(--color-white);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 18px 12px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #skuTable tbody tr {
            transition: all var(--transition-speed) ease;
            height: 60px;
        }

        #skuTable tbody tr:nth-child(even) {
            background-color: rgba(197, 212, 193, 0.1); /* Subtle highlight color */
        }

        #skuTable tbody tr:hover {
            background-color: rgba(197, 212, 193, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        #skuTable input[type="text"],
        #skuTable input[type="number"],
        #skuTable select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--color-table-border);
            border-radius: 4px;
            font-size: 0.95em;
            box-sizing: border-box;
            transition: all var(--transition-speed) ease;
            background-color: var(--color-white);
            min-width: 120px;
        }

        #skuTable input[type="text"]:focus,
        #skuTable input[type="number"]:focus,
        #skuTable select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
            outline: none;
        }

        #skuTable select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23879683" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 30px;
            cursor: pointer;
        }

        .delete-row {
            display: inline-block;
            padding: 10px 18px;
            font-size: 0.9em;
            color: var(--color-white);
            background-color: var(--color-delete-button);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            font-weight: 500;
            margin: 5px;
        }

        .delete-row:hover {
            background-color: var(--color-delete-button-hover);
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .delete-row:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .hidden {
            display: none;
        }

        .options {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            gap: 20px;
            padding: 10px;
        }

        button {
            display: inline-block;
            padding: 14px 28px;
            font-size: 1.1em;
            color: var(--color-white);
            background-color: var(--color-primary);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            margin-bottom: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: var(--color-secondary);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        button:active {
            background-color: var(--color-secondary);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Botón de volver */
        a button {
            background-color: var(--color-highlight);
            color: var(--color-text-dark);
            margin: 0 15px 30px;
            padding: 14px 30px;
        }

        a button:hover {
            background-color: var(--color-primary);
            color: var(--color-white);
        }

        /* Botón de guardar */
        #guardarBtn {
            background-color: var(--color-primary);
            position: relative;
            overflow: hidden;
            padding: 16px 32px;
            font-size: 1.2em;
            margin: 20px 0 30px;
        }

        #guardarBtn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        #guardarBtn:hover::after {
            left: 100%;
        }

        /* Mejoras para campos específicos */
        .campo-formulario[data-campo-nombre="usuario"],
        .campo-formulario[data-campo-nombre="fecha_creacion"],
        .campo-formulario[data-campo-nombre="NOM_COLOR"],
        .campo-formulario[data-campo-nombre="GAMA"],
        .campo-formulario[data-campo-nombre="TEMPORADA"],
        .campo-formulario[data-campo-nombre="TOT_COMP"],
        .campo-formulario[data-campo-nombre="TOT_FORRO"],
        .campo-formulario[data-campo-nombre="TOT_RELLENO"] {
            font-weight: 500;
            background-color: rgba(197, 212, 193, 0.2);
            padding: 12px 8px;
        }

        /* Estilo para campos con error */
        .campo-formulario[style*="background-color: rgb(255, 224, 224)"] {
            border: 1px solid var(--color-error) !important;
            box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2);
        }

        /* Animación para nuevas filas */
        @keyframes highlightRow {
            0% {
                background-color: rgba(197, 212, 193, 0.5);
            }
            100% {
                background-color: transparent;
            }
        }

        .fila-carga.new-row {
            animation: highlightRow 2s ease-out;
        }

        /* Mejoras de accesibilidad y responsividad */
        @media (max-width: 1200px) {
            .container {
                padding: 30px;
                margin: 15px auto;
            }
            
            .container h2 {
                font-size: 1.8em;
            }
        }

        /* Estilo para instrucciones de lavado y cuidado */
        [id^="instruccion_"] {
            font-size: 0.9em;
            color: #555;
            padding: 10px 5px;
        }

        /* Mejora visual para los encabezados de columna */
        #skuTable th {
            position: relative;
            white-space: nowrap;
            min-width: 140px;
        }

        #skuTable th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Mejoras para la visualización de la tabla */
        .table-container::-webkit-scrollbar {
            height: 12px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: var(--color-primary);
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: var(--color-secondary);
        }

        /* Indicador de desplazamiento horizontal */
        .table-container::after {
            content: '→ Desplazar para ver más →';
            display: block;
            text-align: center;
            padding: 10px;
            color: var(--color-primary);
            font-size: 0.9em;
            font-style: italic;
            margin-top: 10px;
        }

        /* Mejora para la visualización de filas */
        #skuTable tbody tr td {
            min-width: 140px;
            max-width: 300px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Modificar Carga</h2>
    <a href="index.php">
        <button>Volver al menú</button>
    </a>
    <button type="submit" id="guardarBtn" class="btn btn-success">Guardar cambios</button>
    <div class="table-container">
        <form id="modificarCargaForm">
        <table id="skuTable">
            <thead>
                <tr>
                    <!-- Encabezados de la tabla, cada uno con su data-campo-nombre para identificación -->
                    <th data-campo-nombre="tipo">Tipo de producto:</th>
                    <th data-campo-nombre="LINEA">Linea de producto:</th>
                    <th data-campo-nombre="usuario">Creado por:</th>
                    <th data-campo-nombre="fecha_creacion">FECHA DE MODIFICACION</th>
                    <th data-campo-nombre="SAP">SAP</th>
                    <th data-campo-nombre="YEAR">AÑO</th>
                    <th data-campo-nombre="MES">MES</th>
                    <th data-campo-nombre="OCASION_DE_USO">OCASION DE USO</th>
                    <th data-campo-nombre="NOMBRE">NOMBRE</th>
                    <th data-campo-nombre="MODULO">MODULO</th>
                    <th data-campo-nombre="TEMPORADA">TEMPORADA</th>
                    <th data-campo-nombre="CAPSULA">CAPSULA</th>
                    <th data-campo-nombre="CLIMA">CLIMA</th>
                    <th data-campo-nombre="TIENDA">TIENDA</th>
                    <th data-campo-nombre="CLASIFICACION">CLASIFICACION</th>
                    <th data-campo-nombre="CLUSTER">CLUSTER</th>
                    <th data-campo-nombre="PROVEEDOR">PROVEEDOR</th>
                    <th data-campo-nombre="CATEGORIAS">CATEGORIAS</th>
                    <th data-campo-nombre="SUBCATEGORIAS">SUB-CATEGORIAS</th>
                    <th data-campo-nombre="DISENO">DISEÑO</th>
                    <th data-campo-nombre="DESCRIPCION">DESCRIPCION</th>
                    <th data-campo-nombre="MANGA">MANGA</th>
                    <th data-campo-nombre="TIPO_MANGA">TIPO DE MANGA</th>
                    <th data-campo-nombre="PUNO">PUÑO</th>
                    <th data-campo-nombre="CAPOTA">CAPOTA</th>
                    <th data-campo-nombre="ESCOTE">ESCOTE</th>
                    <th data-campo-nombre="LARGO">LARGO</th>
                    <th data-campo-nombre="CUELLO">CUELLO</th>
                    <th data-campo-nombre="TIRO">TIRO</th>
                    <th data-campo-nombre="BOTA">BOTA</th>
                    <th data-campo-nombre="CINTURA">CINTURA</th>
                    <th data-campo-nombre="SILUETA">SILUETA</th>
                    <th data-campo-nombre="CIERRE">CIERRE</th>
                    <th data-campo-nombre="GALGA">GALGA</th>
                    <th data-campo-nombre="TIPO_GALGA">TIPO DE GALGA</th>
                    <th data-campo-nombre="COLOR_FDS">COLOR FDS</th>
                    <th data-campo-nombre="NOM_COLOR">NOMBRE COLOR</th>
                    <th data-campo-nombre="GAMA">GAMA</th>
                    <th data-campo-nombre="PRINT">PRINT</th>
                    <th data-campo-nombre="TALLAS">TALLAS</th>
                    <th data-campo-nombre="TIPO_TEJIDO">TIPO DE TEJIDO</th>
                    <th data-campo-nombre="TIPO_DE_FIBRA">TIPO DE FIBRA</th>
                    <th data-campo-nombre="BASE_TEXTIL">BASE TEXTIL</th>
                    <th data-campo-nombre="DETALLES">DETALLES</th>
                    <th data-campo-nombre="SUB_DETALLES">SUB-DETALLES</th>
                    <th data-campo-nombre="GRUPO">GRUPO</th>
                    <th data-campo-nombre="INSTRUCCION_DE_LAVADO_1">INSTRUCCIONES DE LAVADO 1</th>
                    <th data-campo-nombre="INSTRUCCION_DE_LAVADO_2">INSTRUCCIONES DE LAVADO 2</th>
                    <th data-campo-nombre="INSTRUCCION_DE_LAVADO_3">INSTRUCCIONES DE LAVADO 3</th>
                    <th data-campo-nombre="INSTRUCCION_DE_LAVADO_4">INSTRUCCIONES DE LAVADO 4</th>
                    <th data-campo-nombre="INSTRUCCION_DE_LAVADO_5">INSTRUCCIONES DE LAVADO 5</th>
                    <th data-campo-nombre="INSTRUCCION_DE_BLANQUEADO_1">INSTRUCCIONES DE BLANQUEADO 1</th>
                    <th data-campo-nombre="INSTRUCCION_DE_BLANQUEADO_2">INSTRUCCIONES DE BLANQUEADO 2</th>
                    <th data-campo-nombre="INSTRUCCION_DE_BLANQUEADO_3">INSTRUCCIONES DE BLANQUEADO 3</th>
                    <th data-campo-nombre="INSTRUCCION_DE_BLANQUEADO_4">INSTRUCCIONES DE BLANQUEDO 4</th>
                    <th data-campo-nombre="INSTRUCCION_DE_BLANQUEADO_5">INSTRUCCIONES DE BLANQUEADO 5</th>
                    <th data-campo-nombre="INSTRUCCION_SECADO_1">INSTRUCCIONES DE SECADO 1</th>
                    <th data-campo-nombre="INSTRUCCION_SECADO_2">INSTRUCCIONES DE SECADO 2</th>
                    <th data-campo-nombre="INSTRUCCION_SECADO_3">INSTRUCCIONES DE SECADO 3</th>
                    <th data-campo-nombre="INSTRUCCION_SECADO_4">INSTRUCCIONES DE SECADO 4</th>
                    <th data-campo-nombre="INSTRUCCION_SECADO_5">INSTRUCCIONES DE SECADO 5</th>
                    <th data-campo-nombre="INSTRUCCION_PLANCHADO_1">INSTRUCCIONES DE PLANCHADO 1</th>
                    <th data-campo-nombre="INSTRUCCION_PLANCHADO_2">INSTRUCCIONES DE PLANCHADO 2</th>
                    <th data-campo-nombre="INSTRUCCION_PLANCHADO_3">INSTRUCCIONES DE PLANCHADO 3</th>
                    <th data-campo-nombre="INSTRUCCION_PLANCHADO_4">INSTRUCCIONES DE PLANCHADO 4</th>
                    <th data-campo-nombre="INSTRUCCION_PLANCHADO_5">INSTRUCCIONES DE PLANCHADO 5</th>
                    <th data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_1">INSTRUCCIÓN CUIDADO TEXTIL PROF 1</th>
                    <th data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_2">INSTRUCCIÓN CUIDADO TEXTIL PROF 2</th>
                    <th data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_3">INSTRUCCIÓN CUIDADO TEXTIL PROF 3</th>
                    <th data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_4">INSTRUCCIÓN CUIDADO TEXTIL PROF 4</th>
                    <th data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_5">INSTRUCCIÓN CUIDADO TEXTIL PROF 5</th>
                    <th data-campo-nombre="COMPOSICION_1">COMPOSICION 1</th>
                    <th data-campo-nombre="%_COMP_1">% COMP 1</th>
                    <th data-campo-nombre="COMPOSICION_2">COMPOSICION 2</th>
                    <th data-campo-nombre="%_COMP_2">% COMP 2</th>
                    <th data-campo-nombre="COMPOSICION_3">COMPOSICION 3</th>
                    <th data-campo-nombre="%_COMP_3">% COMP 3</th>
                    <th data-campo-nombre="COMPOSICION_4">COMPOSICION 4</th>
                    <th data-campo-nombre="%_COMP_4">% COMP 4</th>
                    <th data-campo-nombre="TOT_COMP">TOTAL COMPOSICION</th>
                    <th data-campo-nombre="FORRO">FORRO</th>
                    <th data-campo-nombre="COMP_FORRO_1">COMPOSICION FORRO 1</th>
                    <th data-campo-nombre="%_FORRO_1">% FORRO 1</th>
                    <th data-campo-nombre="COMP_FORRO_2">COMPOSICION FORRO 2</th>
                    <th data-campo-nombre="%_FORRO_2">% FORRO 2</th>
                    <th data-campo-nombre="TOT_FORRO">TOTAL FORRO</th>
                    <th data-campo-nombre="RELLENO">RELLENO</th>
                    <th data-campo-nombre="COMP_RELLENO_1">COMPOSICION RELLENO 1</th>
                    <th data-campo-nombre="%_RELLENO_1">% RELLENO 1</th>
                    <th data-campo-nombre="COMP_RELLENO_2">COMPOSICION RELLENO 2</th>
                    <th data-campo-nombre="%_RELLENO_2">% RELLENO 2</th>
                    <th data-campo-nombre="TOT_RELLENO">TOTAL RELLENO</th>
                    <th data-campo-nombre="TOT_RELLENO">PRECIO DE COMPRA</th>
                    <th data-campo-nombre="TOT_RELLENO">COSTO</th>
                    <th data-campo-nombre="TOT_RELLENO">PRECIO DE VENTA</th>
                    <th data-campo-nombre="ACCIONES">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fila-carga" data_fila_original="1">
                    <td>
                        <select class="campo-formulario" data-campo-nombre="tipo" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['tipo'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="LINEA" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['LINEA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="usuario" data-campo-type="static"><?php echo htmlspecialchars($carga['usuario'] ?? $userName . ' ' . $userApellido); ?></td>
                    <td class="campo-formulario" data-campo-nombre="fecha_creacion" data-campo-type="static"><?php echo htmlspecialchars($carga['fecha_creacion'] ?? $fecha_actual); ?></td>
                    <td>
                        <input type="text" class="campo-formulario" data-campo-nombre="SAP" value="<?php echo htmlspecialchars($carga['SAP'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="YEAR" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['YEAR'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="MES" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['MES'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="OCASION_DE_USO" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['OCASION_DE_USO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="campo-formulario" data-campo-nombre="NOMBRE" data-campo-type="static" value="<?php echo htmlspecialchars($carga['NOMBRE'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="MODULO" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['MODULO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td id="temp" class="campo-formulario" data-campo-nombre="TEMPORADA" data-campo-type="static"><?php echo htmlspecialchars($carga['TEMPORADA']); ?></td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CAPSULA" data-campo-type="variable" data-valor-actual="<?php echo htmlspecialchars($carga['CAPSULA']); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CLIMA" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['CLIMA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIENDA" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['TIENDA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CLASIFICACION" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['CLASIFICACION'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CLUSTER" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['CLUSTER'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="PROVEEDOR" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['PROVEEDOR'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CATEGORIAS" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['CATEGORIAS'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="SUBCATEGORIAS" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['SUBCATEGORIAS'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="DISENO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['DISENO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="campo-formulario" data-campo-nombre="DESCRIPCION" data-campo-type="static" value="<?php echo htmlspecialchars($carga['DESCRIPCION'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="MANGA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['MANGA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_MANGA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['TIPO_MANGA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="PUNO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['PUNO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CAPOTA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['CAPOTA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="ESCOTE" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['ESCOTE'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="LARGO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['LARGO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CUELLO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['CUELLO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIRO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['TIRO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="BOTA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['BOTA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CINTURA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['CINTURA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="SILUETA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['SILUETA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CIERRE" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['CIERRE'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="GALGA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['GALGA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_GALGA" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['TIPO_GALGA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td >
                        <select id="color_fds" onchange="asignarnomcolor(this), asignargamacolor(this)" class="campo-formulario" data-campo-nombre="COLOR_FDS" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['COLOR_FDS'] ?? ''); ?>">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td id="nom_color" class="campo-formulario" data-campo-nombre="NOM_COLOR" data-campo-type="static"><?php echo htmlspecialchars($carga['NOM_COLOR'] ?? $userName . ' ' . $userApellido); ?></td> 
                    <td id="gama" class="campo-formulario" data-campo-nombre="GAMA" data-campo-type="static"><?php echo htmlspecialchars($carga['GAMA'] ?? $userName . ' ' . $userApellido); ?></td> 
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="PRINT" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['PRINT'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TALLAS" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['TALLAS'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_TEJIDO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['TIPO_TEJIDO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_DE_FIBRA" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['TIPO_DE_FIBRA'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="BASE_TEXTIL" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['BASE_TEXTIL'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="DETALLES" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['DETALLES'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="SUB_DETALLES" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['SUB_DETALLES'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="GRUPO" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['GRUPO'] ?? ''); ?>" id="grupo">
                            <option value=""></option>
                        </select>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_1" data-campo-type="static" id="instruccion_lavado_1"><?php echo htmlspecialchars($carga['INSTRUCCION_DE_LAVADO_1']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_2" data-campo-type="static" id="instruccion_lavado_2"><?php echo htmlspecialchars($carga['INSTRUCCION_DE_LAVADO_2']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_3" data-campo-type="static" id="instruccion_lavado_3"><?php echo htmlspecialchars($carga['INSTRUCCION_DE_LAVADO_3']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_4" data-campo-type="static" id="instruccion_lavado_4"><?php echo htmlspecialchars($carga['INSTRUCCION_DE_LAVADO_4']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_5" data-campo-type="static" id="instruccion_lavado_5"><?php echo htmlspecialchars($carga['INSTRUCCION_DE_LAVADO_5']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_1" data-campo-type="static" id="instruccion_blanqueado_1"><?php echo htmlspecialchars($carga['INSTRUCCION_BLANQUEADO_1']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_2" data-campo-type="static" id="instruccion_blanqueado_2"><?php echo htmlspecialchars($carga['INSTRUCCION_BLANQUEADO_2']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_3" data-campo-type="static" id="instruccion_blanqueado_3"><?php echo htmlspecialchars($carga['INSTRUCCION_BLANQUEADO_3']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_4" data-campo-type="static" id="instruccion_blanqueado_4"><?php echo htmlspecialchars($carga['INSTRUCCION_BLANQUEADO_4']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_5" data-campo-type="static" id="instruccion_blanqueado_5"><?php echo htmlspecialchars($carga['INSTRUCCION_BLANQUEADO_5']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_1" data-campo-type="static" id="instruccion_secado_1"><?php echo htmlspecialchars($carga['INSTRUCCION_SECADO_1']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_2" data-campo-type="static" id="instruccion_secado_2"><?php echo htmlspecialchars($carga['INSTRUCCION_SECADO_2']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_3" data-campo-type="static" id="instruccion_secado_3"><?php echo htmlspecialchars($carga['INSTRUCCION_SECADO_3']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_4" data-campo-type="static" id="instruccion_secado_4"><?php echo htmlspecialchars($carga['INSTRUCCION_SECADO_4']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_5" data-campo-type="static" id="instruccion_secado_5"><?php echo htmlspecialchars($carga['INSTRUCCION_SECADO_5']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_1" data-campo-type="static" id="instruccion_planchado_1"><?php echo htmlspecialchars($carga['INSTRUCCION_PLANCHADO_1']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_2" data-campo-type="static" id="instruccion_planchado_2"><?php echo htmlspecialchars($carga['INSTRUCCION_PLANCHADO_2']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_3" data-campo-type="static" id="instruccion_planchado_3"><?php echo htmlspecialchars($carga['INSTRUCCION_PLANCHADO_3']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_4" data-campo-type="static" id="instruccion_planchado_4"><?php echo htmlspecialchars($carga['INSTRUCCION_PLANCHADO_4']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_5" data-campo-type="static" id="instruccion_planchado_5"><?php echo htmlspecialchars($carga['INSTRUCCION_PLANCHADO_5']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_1" data-campo-type="static" id="instruccion_cuidado_textil_1"><?php echo htmlspecialchars($carga['INSTRUCC_CUIDADO_TEXTIL_PROF_1']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_2" data-campo-type="static" id="instruccion_cuidado_textil_2"><?php echo htmlspecialchars($carga['INSTRUCC_CUIDADO_TEXTIL_PROF_2']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_3" data-campo-type="static" id="instruccion_cuidado_textil_3"><?php echo htmlspecialchars($carga['INSTRUCC_CUIDADO_TEXTIL_PROF_3']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_4" data-campo-type="static" id="instruccion_cuidado_textil_4"><?php echo htmlspecialchars($carga['INSTRUCC_CUIDADO_TEXTIL_PROF_4']); ?></td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_5" data-campo-type="static" id="instruccion_cuidado_textil_5"><?php echo htmlspecialchars($carga['INSTRUCC_CUIDADO_TEXTIL_PROF_5']); ?></td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_1" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['COMPOSICION_1'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_1" value="<?php echo htmlspecialchars($carga['%_COMP_1'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_2" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['COMPOSICION_2'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_2" value="<?php echo htmlspecialchars($carga['%_COMP_2'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_3" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['COMPOSICION_3'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_3" value="<?php echo htmlspecialchars($carga['%_COMP_3'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_4" data-campo-type="static" data-valor-actual="<?php echo htmlspecialchars($carga['COMPOSICION_4'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_4" value="<?php echo htmlspecialchars($carga['%_COMP_4'] ?? ''); ?>">
                    </td>
                    <td class="campo-formulario" data-campo-nombre="TOT_COMP" data-campo-type="static"><?php echo htmlspecialchars($carga['TOT_COMP']); ?></td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="FORRO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['FORRO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_FORRO_1" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['COMP_FORRO_1'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_FORRO_1" value="<?php echo htmlspecialchars($carga['%_FORRO_1'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_FORRO_2" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['COMP_FORRO_2'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_FORRO_2" value="<?php echo htmlspecialchars($carga['%_FORRO_2'] ?? ''); ?>">
                    </td>
                    <td class="campo-formulario" data-campo-nombre="TOT_FORRO" data-campo-type="static">-
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="RELLENO" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['RELLENO'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_RELLENO_1" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['COMP_RELLENO_1'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_RELLENO_1" value="<?php echo htmlspecialchars($carga['%_RELLENO_1'] ?? ''); ?>">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_RELLENO_2" data-campo-type="dependent" data-valor-actual="<?php echo htmlspecialchars($carga['COMP_RELLENO_2'] ?? ''); ?>">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_RELLENO_2" value="<?php echo htmlspecialchars($carga['%_RELLENO_2'] ?? ''); ?>">
                    </td>
                    <td class="campo-formulario" data-campo-nombre="TOT_RELLENO" data-campo-type="static"><?php echo htmlspecialchars($carga['TOT_RELLENO']); ?></td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="precio_compra" value="<?php echo htmlspecialchars($carga['precio_compra'] ?? ''); ?>">
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="costo" value="<?php echo htmlspecialchars($carga['costo'] ?? ''); ?>">
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="precio_venta" value="<?php echo htmlspecialchars($carga['precio_venta'] ?? ''); ?>">
                    </td>
                    <td><button class="delete-row">Eliminar</button></td>
                </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>
<script>
/*
==========================================
SECCIÓN DE VARIABLES Y FUNCIONES JS
==========================================
Incluye validaciones, carga dinámica de opciones, lógica de dependencias, manejo de eventos, etc.
*/

// Lista de campos obligatorios para validación antes de guardar
const CAMPOS_OBLIGATORIOS_save = [
    "tipo", "usuario", "fecha_creacion", "YEAR", "TOT_COMP", "TIPO_TEJIDO", "TIPO_DE_FIBRA", "TIENDA", "TEMPORADA", "TALLAS", "SUB_DETALLES", "SUBCATEGORIAS", "PROVEEDOR", "PRINT", "OCASION_DE_USO", "NOM_COLOR", "NOMBRE", "MODULO", "MES", "MANGA", "LARGO", "GRUPO", "GAMA", "DETALLES", "DESCRIPCION", "COLOR_FDS", "CLUSTER", "CLIMA", "CLASIFICACION", "CATEGORIAS", "CAPSULA", "BASE_TEXTIL", "%_COMP_1", "COMPOSICION_1", "precio_compra", "costo", "precio_venta"
];

/**
 * Valida que el campo principal de un grupo (%_COMP_1, %_FORRO_1, %_RELLENO_1) sea el mayor y mayor a 50
 * @param {HTMLElement} rowElement - La fila a validar
 * @param {string} prefix - El prefijo del campo ("%_COMP_", "%_FORRO_", "%_RELLENO_")
 * @param {number} count - Cuántos campos hay en el grupo (4 para comp, 2 para forro/relleno)
 * @param {string} label - Etiqueta para el mensaje de error
 * @returns {Object} - { esValida: boolean, mensaje: string }
 */
function validarMayorPrincipal(rowElement, prefix, count, label) {
    const valores = [];
    for (let i = 1; i <= count; i++) {
        const val = parseFloat(rowElement.querySelector(`.campo-formulario[data-campo-nombre="${prefix}${i}"]`)?.value) || 0;
        valores.push(val);
    }
    // Limpiar estilos previos
    rowElement.querySelectorAll(`.campo-formulario[data-campo-nombre^="${prefix}"]`).forEach(field => {
        field.style.backgroundColor = '';
    });

    let esValida = true;
    let mensaje = "";

    if (valores[0] <= 50) {
        esValida = false;
        mensaje = `${label} 1 debe ser mayor a 50`;
    } else if (!valores.slice(1).every(v => valores[0] > v)) {
        esValida = false;
        mensaje = `${label} 1 debe ser el mayor de los ${count} porcentajes`;
    }

    if (!esValida) {
        rowElement.querySelectorAll(`.campo-formulario[data-campo-nombre^="${prefix}"]`).forEach(field => {
            field.style.backgroundColor = '#ffe0e0';
        });
    }

    return { esValida, mensaje };
}

// Funciones específicas para cada grupo
function validarComp1Mayor(rowElement) {
    // Composición siempre se valida
    return validarMayorPrincipal(rowElement, "%_COMP_", 4, "% COMP");
}

function validarForro1Mayor(rowElement) {
    // Solo valida si el select FORRO dice "SI TIENE"
    const forroSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="FORRO"]');
    if (!forroSelect || forroSelect.value.toUpperCase() !== "SI TIENE") {
        // Si no tiene forro, no validar mayor a 50
        return { esValida: true, mensaje: "" };
    }
    return validarMayorPrincipal(rowElement, "%_FORRO_", 2, "% FORRO");
}

function validarRelleno1Mayor(rowElement) {
    // Solo valida si el select RELLENO dice "SI TIENE"
    const rellenoSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="RELLENO"]');
    if (!rellenoSelect || rellenoSelect.value.toUpperCase() !== "SI TIENE") {
        // Si no tiene relleno, no validar mayor a 50
        return { esValida: true, mensaje: "" };
    }
    return validarMayorPrincipal(rowElement, "%_RELLENO_", 2, "% RELLENO");
}


// Función para validar que los campos obligatorios de una fila estén completos
function validarFilaObligatorios(row) {
    let esValida = true;
    let camposFaltantes = [];
    // Limpiar estilos previos
    row.querySelectorAll('.campo-formulario').forEach(field => {
        field.style.backgroundColor = '';
    });

    CAMPOS_OBLIGATORIOS_save.forEach(nombreCampo => {
        const field = row.querySelector(`.campo-formulario[data-campo-nombre="${nombreCampo}"]`);
        if (field) {
            let valor = "";
            if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                valor = field.value.trim();
            } else if (field.tagName === 'TD') {
                valor = field.textContent.trim();
            }
            if (!valor) {
                esValida = false;
                camposFaltantes.push(nombreCampo);
                // Resalta el campo vacío
                field.style.backgroundColor = '#ffe0e0';
            }
        }
    });
    return { esValida, camposFaltantes };
}

// Función para cargar dinámicamente las opciones de "CAPSULA" desde la API
function cargarCapsulasEnSelects() {
    fetch('../api/get_capsulas.php')
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('select.campo-formulario[data-campo-nombre="CAPSULA"]').forEach(select => {
                // Limpia las opciones actuales
                select.innerHTML = '<option value="">Seleccione</option>';
                if (Array.isArray(data)) {
                    data.forEach(capsula => {
                        const opt = document.createElement('option');
                        opt.value = capsula.nombre;
                        opt.textContent = capsula.nombre;
                        select.appendChild(opt);
                    });
                }
                // Selecciona el valor actual si existe
                const valorActual = select.getAttribute('data-valor-actual');
                if (valorActual) {
                    select.value = valorActual;
                }
            });
        })
        .catch(err => {
            console.error('Error cargando cápsulas:', err);
        });
}
document.addEventListener('DOMContentLoaded', cargarCapsulasEnSelects);

// Llama a la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarCapsulasEnSelects);

// Variable global con el nombre del usuario actual
const CURRENT_USER = "<?php echo htmlspecialchars($userName . ' ' . $userApellido); ?>";

// Funciones para actualizar totales de composición, forro y relleno
function actualizarTotalComposicion(rowElement) {
    // Suma los valores de los campos de composición y actualiza el total
    const campos = ['%_COMP_1', '%_COMP_2', '%_COMP_3', '%_COMP_4'];
    let total = 0;
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input && input.value !== '') {
            total += parseFloat(input.value) || 0;
        }
    });
    const totalCell = rowElement.querySelector('.campo-formulario[data-campo-nombre="TOT_COMP"]');
    if (totalCell) {
        totalCell.textContent = total;
    }
}

// ... (Las funciones inicializarListenersComposicion, actualizarTotalForro, inicializarListenersForro, actualizarTotalRelleno, inicializarListenersRelleno siguen el mismo patrón)
function inicializarListenersComposicion(rowElement) {
    const campos = ['%_COMP_1', '%_COMP_2', '%_COMP_3', '%_COMP_4'];
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input) {
            input.addEventListener('input', function() {
                actualizarTotalComposicion(rowElement);
                validarComp1Mayor(rowElement);
            });
        }
    });
}

function actualizarTotalForro(rowElement) {
    const campos = ['%_FORRO_1', '%_FORRO_2'];
    let total = 0;
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input && input.value !== '') {
            total += parseFloat(input.value) || 0;
        }
    });
    const totalCell = rowElement.querySelector('.campo-formulario[data-campo-nombre="TOT_FORRO"]');
    if (totalCell) {
        totalCell.textContent = total;
    }
}

function inicializarListenersForro(rowElement) {
    const campos = ['%_FORRO_1', '%_FORRO_2'];
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input) {
            input.addEventListener('input', function() {
                actualizarTotalForro(rowElement);
                validarForro1Mayor(rowElement);
            });
        }
    });
}

function actualizarTotalRelleno(rowElement) {
    const campos = ['%_RELLENO_1', '%_RELLENO_2'];
    let total = 0;
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input && input.value !== '') {
            total += parseFloat(input.value) || 0;
        }
    });
    const totalCell = rowElement.querySelector('.campo-formulario[data-campo-nombre="TOT_RELLENO"]');
    if (totalCell) {
        totalCell.textContent = total;
    }
}


function inicializarListenersRelleno(rowElement) {
    const campos = ['%_RELLENO_1', '%_RELLENO_2'];
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input) {
            input.addEventListener('input', function() {
                actualizarTotalRelleno(rowElement);
                validarRelleno1Mayor(rowElement);
            });
        }
    });
}

// Función para obtener la fecha y hora actual en formato string
function getCurrentDateTimeString() {
    const now = new Date();
    const pad = n => n < 10 ? '0' + n : n;
    return now.getFullYear() + '-' +
        pad(now.getMonth() + 1) + '-' +
        pad(now.getDate()) + ' ' +
        pad(now.getHours()) + ':' +
        pad(now.getMinutes()) + ':' +
        pad(now.getSeconds());
}

// Funciones para asignar valores automáticos a campos dependientes (ej: temporada según módulo, nombre de color, gama, grupo, etc.)
function asignarvalortemporada(moduloSelect) {
    const row = moduloSelect.closest('.fila-carga');
    if (!row) return;
    const temporadaCell = row.querySelector('.campo-formulario[data-campo-nombre="TEMPORADA"]');
    if (!temporadaCell) return;

    const valores = {
        "M1" : "PRIMAVERA/VERANO",
        "M2" : "PRIMAVERA/VERANO",
        "M3" : "OTOÑO/INVIERNO",
        "M4" : "OTOÑO/INVIERNO"
    };
    temporadaCell.textContent = valores[moduloSelect.value] || "-";
}

function asignarnomcolor(colorSelect) {
    const row = colorSelect.closest('.fila-carga');
    if (!row) return;
    const nomColorCell = row.querySelector('.campo-formulario[data-campo-nombre="NOM_COLOR"]');
    if (!nomColorCell) return;
    const valores = {
        "100": "BLANCO",
        "101": "OFFWHITE",
        "102": "IVORY",
        "103": "IVORY",
        "106": "BLANCO",
        "109": "BEIGE",
        "110": "BEIGE",
        "121": "ARENA",
        "123": "KAKI",
        "203": "AMARILLO CLARO",
        "207": "LIMA",
        "209": "AMARILLO QUEMADO",
        "219": "BRIGHT GOLD",
        "220": "TIERRA",
        "224": "FLUORECENTE",
        "233": "CYBER LIME",
        "237": "AMARILLO",
        "258": "NARANJA",
        "260": "NARANJA CLARO",
        "263": "CORAL",
        "264": "CORAL",
        "266": "NARANJA",
        "277": "CORAL",
        "279": "TERRACOTA",
        "281": "CORAL",
        "283": "TERRACOTA",
        "284": "NARANJA",
        "300": "ROJO",
        "301": "ROJO",
        "313": "ROJO",
        "315": "ROJO",
        "319": "ROJO",
        "322": "VINO",
        "328": "VINO",
        "337": "BURGUNDY",
        "350": "FUCCIA",
        "354": "ROSADO",
        "356": "ROSADO",
        "357": "ROSADO",
        "361": "ROSA MARCHITA",
        "362": "ROSA MARCHITA",
        "363": "ROSA MARCHITA",
        "366": "PALO DE ROSA",
        "368": "BLUSH",
        "367": "ROSADO",
        "370": "ROSADO",
        "372": "ROSADO",
        "375": "FUCSIA",
        "369": "ROSADO",
        "380": "MAGENTA",
        "393": "ROSADO",
        "394": "ROSADO",
        "395": "MAUVE",
        "401": "VIOLETA",
        "407": "PURPURA",
        "417": "LILA",
        "418": "LILA CLARO",
        "431": "MORADO",
        "454": "AZUL",
        "463": "AZUL CIELO",
        "473": "ROYAL",
        "480": "CLARO",
        "481": "MEDIO OSC",
        "460": "CLARO",
        "461": "CLARO",
        "464": "MEDIO",
        "467": "AZUL",
        "475": "HIELO",
        "479": "NAVY",
        "482": "AZUL",
        "484": "TURQUEZA",
        "494": "TURQUEZA",
        "505": "TURQUEZA",
        "504": "TURQUEZA",
        "510": "TURQUEZA",
        "513": "PETROL",
        "515": "ALPINE GREEN",
        "556": "VERDE",
        "565": "VERDE CLARO",
        "567": "VERDE",
        "570": "VERDE",
        "575": "GREEN TE",
        "579": "VERDE OSCURO",
        "583": "JADE",
        "588": "VERDE LIMON",
        "591": "VERDE OSCURO", 
        "592": "VERDE CHIVE",
        "596": "OLIVO",
        "597": "VERDE MILITAR",
        "606": "CAQUI",
        "608": "CAQUI",
        "611": "CAFÉ",
        "613": "CAFÉ",
        "622": "CAQUI",
        "623": "CAQUI",
        "624": "CHOCOLATE",
        "625": "CAQUI",
        "626": "TAUPE",
        "627": "COFFE",
        "700": "NEGRO",
        "701": "CAVIAR",
        "803": "GRIS CLARO",
        "811": "GRIS MEDIO", 
        "815": "GRIS MEDIO",
        "817": "GRIS OSCURO",
        "819": "GRIS OSCURO",
        "999": "MULTICOLOR"
    };
    nomColorCell.textContent = valores[colorSelect.value] || "-";
}

function asignargamacolor(gamaSelect) {
    const row = gamaSelect.closest('.fila-carga');
    if (!row) return;
    const gamacell = row.querySelector('.campo-formulario[data-campo-nombre="GAMA"]');
    if (!gamacell) return;

    const valores_gama = {
        "100": "BLANCO",
        "101": "BLANCO",
        "102": "BLANCO",
        "103": "BLANCO",
        "106": "BLANCO",
        "109": "BEIGE",
        "110": "BEIGE",
        "121": "BEIGE",
        "123": "BEIGE",
        "203": "AMARILLO",
        "207": "AMARILLO",
        "209": "AMARILLO",
        "219": "AMARILLO",
        "220": "AMARILLO",
        "224": "AMARILLO",
        "233": "AMARILLO",
        "237": "AMARILLO",
        "258": "NARANJA",
        "260": "NARANJA",
        "263": "NARANJA",
        "264": "NARANJA",
        "266": "NARANJA",
        "277": "NARANJA",
        "279": "NARANJA",
        "281": "NARANJA",
        "283": "NARANJA",
        "284": "NARANJA",
        "300": "ROJO",
        "301": "ROJO",
        "313": "ROJO",
        "315": "ROJO",
        "319": "ROJO",
        "322": "ROJO",
        "328": "ROJO",
        "337": "ROJO",
        "350": "ROSADO",
        "354": "ROSADO",
        "356": "ROSADO",
        "357": "ROSADO",
        "361": "ROSADO",
        "362": "ROSADO",
        "363": "ROSADO",
        "366": "ROSADO",
        "368": "ROSADO",
        "367": "ROSADO",
        "370": "ROSADO",
        "372": "ROSADO",
        "375": "MAGENTA",
        "369": "ROSADO",
        "380": "MAGENTA",
        "393": "MAGENTA",
        "394": "MAGENTA",
        "395": "MAGENTA",
        "401": "MORADO",
        "407": "MORADO",
        "417": "MORADO",
        "418": "MORADO",
        "431": "MORADO",
        "454": "AZUL",
        "463": "AZUL",
        "473": "AZUL",
        "480": "AZUL",
        "481": "AZUL",
        "460": "AZUL",
        "461": "AZUL",
        "464": "AZUL",
        "467": "AZUL",
        "475": "AZUL",
        "479": "AZUL",
        "482": "AZUL",
        "484": "TURQUEZA",
        "494": "TURQUEZA",
        "505": "TURQUEZA",
        "504": "TURQUEZA",
        "510": "TURQUEZA",
        "513": "TURQUEZA",
        "515": "TURQUEZA",
        "556": "VERDE",
        "565": "VERDE",
        "567": "VERDE",
        "570": "VERDE",
        "575": "VERDE",
        "579": "VERDE",
        "583": "VERDE",
        "588": "VERDE",
        "591": "VERDE", 
        "592": "VERDE",
        "596": "VERDE",
        "597": "VERDE",
        "606": "CAFE",
        "608": "CAFE",
        "611": "CAFÉ",
        "613": "CAFÉ",
        "622": "CAFE",
        "623": "CAFE",
        "624": "CAFE",
        "625": "CAFE",
        "626": "CAFE",
        "627": "CAFE",
        "700": "NEGRO",
        "701": "NEGRO",
        "803": "NEGRO",
        "811": "NEGRO", 
        "815": "NEGRO",
        "817": "NEGRO",
        "819": "NEGRO",
        "999": "MULTICOLOR"
    };
    gamacell.textContent = valores_gama[gamaSelect.value] || "-";
}

function asignarvalorgrupo(grupoSelect){
    const row = grupoSelect.closest('.fila-carga');
    if (!row) return;
    const grupo = grupoSelect.value;
    const getCell = name => row.querySelector(`.campo-formulario[data-campo-nombre="${name}"]`);

    const valores_lavado_1 = {
        "1": "TEMP MAX 30°CPROC MODERADO",
        "2": "LAVAR A MANO",
        "3": "LAVAR A MANO",
        "4": "LAVAR A MANO",
        "5": "TEMP MAX 30°CPROC MODERADO",
        "6": "TEMP MAX 30°CPROC MODERADO",
        "7": "TEMP MAX 30°CPROC MODERADO",
        "8": "TEMP MAX 30°CPROC MODERADO",
        "9": "TEMP MAX 30°CPROC MODERADO",
        "10": "TEMP MAX 30°CPROC MODERADO",
        "11": "LAVAR A MANO",
        "12": "NO LAVAR",
        "13": "LAVAR A MANO",
        "14": "TEMP MAX 30°CPROC MODERADO",
        "15": "LAVAR A MANO",
        "16": "TEMP MAX 30°CPROC MODERADO",
        "17": "LAVAR A MANO",
        "18": "LAVAR A MANO",
        "19": "TEMP MAX 30°CPROC MODERADO",
        "20": "LAVAR A MANO",
        "21": "LAVAR A MANO",
        "22": "LAVAR A MANO",
        "23": "LAVAR A MANO",
        "24": "LAVAR A MANO",
        "25": "LAVAR A MANO",
        "26": "LAVAR A MANO",
        "27": "LAVAR A MANO",
        "28": "LAVAR A MANO",
        "29": "LAVAR A MANO",
        "30": "LAVAR A MANO",
        "31": "LAVAR A MANO",
        "32": "NO LAVAR",
        "33": "LAVAR A MANO",
        "34": "LAVAR A MANO",
        "35": "LAVAR A MANO", 
        "36": "LAVAR A MANO",
        "37": "NO LAVAR",
        "38": "LAVAR A MANO",
        "39": "LAVAR A MANO",
        "40": "LAVAR A MANO",
        "41": "LAVAR A MANO",
        "42": "LAVAR A MANO"
    }

    const valores_lavado_2 = {
    }

    const valores_lavado_3 = {
    }

    const valores_lavado_4 = {
    }

    const valores_lavado_5 = {
    }

    const valores_blanqueado_1 = {
        "1": "NO USAR BLANQUEADOR",
        "2": "NO USAR BLANQUEADOR",
        "3": "NO USAR BLANQUEADOR",
        "4": "NO USAR BLANQUEADOR",
        "5": "SE PERMITE SOLAMENTE BLANQUEAD",
        "6": "NO USAR BLANQUEADOR",
        "7": "NO USAR BLANQUEADOR",
        "8": "NO USAR BLANQUEADOR",
        "9": "NO USAR BLANQUEADOR",
        "10": "NO USAR BLANQUEADOR",
        "11": "NO USAR BLANQUEADOR",
        "12": "NO USAR BLANQUEADOR",
        "13": "NO USAR BLANQUEADOR",
        "14": "NO USAR BLANQUEADOR",
        "15": "NO USAR BLANQUEADOR",
        "16": "NO USAR BLANQUEADOR",
        "17": "NO USAR BLANQUEADOR",
        "18": "NO USAR BLANQUEADOR",
        "19": "NO USAR BLANQUEADOR",
        "20": "NO USAR BLANQUEADOR",
        "21": "NO USAR BLANQUEADOR",
        "22": "NO USAR BLANQUEADOR",
        "23": "NO USAR BLANQUEADOR",
        "24": "NO USAR BLANQUEADOR",
        "25": "NO USAR BLANQUEADOR",
        "26": "NO USAR BLANQUEADOR",
        "27": "NO USAR BLANQUEADOR",
        "28": "NO USAR BLANQUEADOR",
        "29": "NO USAR BLANQUEADOR",
        "30": "NO USAR BLANQUEADOR",
        "31": "NO USAR BLANQUEADOR",
        "32": "NO USAR BLANQUEADOR",
        "33": "NO USAR BLANQUEADOR",
        "34": "NO USAR BLANQUEADOR",
        "35": "NO USAR BLANQUEADOR",
        "36": "NO USAR BLANQUEADOR",
        "37": "NO USAR BLANQUEADOR",
        "38": "NO USAR BLANQUEADOR",
        "39": "NO USAR BLANQUEADOR",
        "40": "NO USAR BLANQUEADOR",
        "41": "NO USAR BLANQUEADOR",
        "42": "NO USAR BLANQUEADOR"
    }

    const valores_blanqueado_2 = {
        "5": "OR A BASE DE OXIGENO SIN CLORO"
    }

    const valores_blanqueado_3 = {
    }

    const valores_blanqueado_4 = {
    }

    const valores_blanqueado_5 = {    
    }

    const valores_secado_1 = {
        "1": "SECADO EXTENDIDO A LA SOMBRA",
        "2": "SECADO EXTENDIDO A LA SOMBRA",
        "3": "SECADO EXTENDIDO A LA SOMBRA",
        "4": "SECADO EXTENDIDO A LA SOMBRA",
        "5": "SECADO EN TENDEDERO",
        "6": "SECADO EXTENDIDO A LA SOMBRA",
        "7": "SECADO EN TENDEDERO",
        "8": "SECADO EN TENDEDERO",
        "9": "SECADO EN TENDEDERO",
        "10": "SECADO EXTENDIDO A LA SOMBRA",
        "11": "SECADO EXTENDIDO A LA SOMBRA",
        "12": "NO SECAR EN MAQUINA",
        "13": "SECADO EXTENDIDO A LA SOMBRA",
        "14": "SECADO EXTENDIDO",
        "15": "SECADO EXTENDIDO A LA SOMBRA",
        "16": "SECADO EXTENDIDO A LA SOMBRA",
        "17": "SECADO EXTENDIDO A LA SOMBRA",
        "18": "SECADO EXTENDIDO A LA SOMBRA",
        "19": "SECADO EN TENDEDERO",
        "20": "SECADO EXTENDIDO A LA SOMBRA",
        "21": "SECADO EXTENDIDO A LA SOMBRA",
        "22": "SECADO EXTENDIDO A LA SOMBRA",
        "23": "SECADO EXTENDIDO A LA SOMBRA",
        "24": "SECADO EXTENDIDO A LA SOMBRA",
        "25": "SECADO EXTENDIDO A LA SOMBRA",
        "26": "SECADO EXTENDIDO A LA SOMBRA",
        "27": "SECADO EXTENDIDO A LA SOMBRA",
        "28": "SECADO EXTENDIDO A LA SOMBRA",
        "29": "SECADO EXTENDIDO A LA SOMBRA",
        "30": "SECADO EN TENDEDERO",
        "31": "SECADO EXTENDIDO A LA SOMBRA",
        "32": "NO SECAR EN MAQUINA",
        "33": "SECADO EXTENDIDO A LA SOMBRA",
        "34": "SECADO EN TENDEDERO",
        "35": "SECADO EN TENDEDERO",
        "36": "SECADO EN TENDEDERO",
        "37": "NO SECAR EN MAQUINA",
        "38": "SECADO EXTENDIDO A LA SOMBRA",
        "39": "SECADO EXTENDIDO A LA SOMBRA",
        "40": "SECADO EXTENDIDO A LA SOMBRA",
        "41": "SECADO EXTENDIDO A LA SOMBRA",
        "42": "SECADO EXTENDIDO A LA SOMBRA"
    }

    const valores_secado_2 = {
    }

    const valores_secado_3 = {
    }

    const valores_secado_4 = {
    }

    const valores_secado_5 = {
    }
    
    const valores_planchado_1 = {
        "1": "NO PLANCHAR",
        "2": "PLANCHAR A UNA TEMPERATURA MAX",
        "3": "NO PLANCHAR",
        "4": "NO PLANCHAR",
        "5": "PLANCHAR A UNA TEMPERATURA MAX",
        "6": "NO PLANCHAR",
        "7": "NO PLANCHAR",
        "8": "NO PLANCHAR",
        "9": "NO PLANCHAR",
        "10": "NO PLANCHAR",
        "11": "NO PLANCHAR",
        "12": "NO PLANCHAR",
        "13": "PLANCHAR A UNA TEMPERATURA MAX",
        "14": "PLANCHAR A UNA TEMPERATURA MAX",
        "15": "NO PLANCHAR",
        "16": "PLANCHAR A UNA TEMPERATURA MAX",
        "17": "NO PLANCHAR",
        "18": "NO PLANCHAR",
        "19": "NO PLANCHAR",
        "20": "NO PLANCHAR",
        "21": "NO PLANCHAR",
        "22": "NO PLANCHAR",
        "23": "PLANCHAR A UNA TEMPERATURA MAX",
        "24": "NO PLANCHAR",
        "25": "NO PLANCHAR",
        "26": "PLANCHAR A UNA TEMPERATURA MAX",
        "27": "PLANCHAR A UNA TEMPERATURA MAX",
        "28": "NO PLANCHAR",
        "29": "PLANCHAR A UNA TEMPERATURA MAX",
        "30": "NO PLANCHAR",
        "31": "NO PLANCHAR",
        "32": "NO PLANCHAR",
        "33": "NO PLANCHAR",
        "34": "NO PLANCHAR",
        "35": "NO PLANCHAR",
        "36": "NO PLANCHAR",
        "37": "NO PLANCHAR",
        "38": "PLANCHAR A UNA TEMPERATURA MAX",
        "39": "NO PLANCHAR",
        "40": "PLANCHAR A UNA TEMPERATURA MAX",
        "41": "PLANCHAR A UNA TEMPERATURA MAX",
        "42": "PLANCHAR A UNA TEMPERATURA MAX"
    }

    const valores_planchado_2 = {
        "2":"IMA DE LA BASE DE 110°C, SIN VAPOR",
        "5":"IMA DE LA BASE DE 150°C",
        "13": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "14": "IMA DE LA BASE DE 150°C",
        "16": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "23": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "26": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "27": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "29": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "38": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "40": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "41": "IMA DE LA BASE DE 110°C, SIN VAPOR",
        "42": "IMA DE LA BASE DE 110°C, SIN VAPOR"
    }

    const valores_planchado_3 = {
    }

    const valores_planchado_4 = {  
    }

    const valores_planchado_5 = {
    }

    const valores_cuidado_textil_1 = {
        "1": "NO LIMPIEZA EN SECO",
        "2": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "3": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "4": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "5": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "6": "NO LIMPIEZA EN SECO",
        "7": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "8": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "9": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "10": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "11": "NO LIMPIEZA EN SECO",
        "12": "LIMPIEZA EN SECO PROFESIONAL C",
        "13": "NO LIMPIEZA EN SECO",
        "14": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "15": "NO LIMPIEZA EN SECO",
        "16": "NO LIMPIEZA EN SECO",
        "17": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "18": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "19": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "20": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "21": "NO LIMPIEZA EN SECO",
        "22": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "23": "NO LIMPIEZA EN SECO",
        "24": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "25": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "26": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "27": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "28": "NO LIMPIEZA EN SECO",
        "29": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "30": "LIMPIEZA EN HUMEDO PROFESIONAL", 
        "31": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "32": "LIMPIEZA EN SECO PROFESIONAL C",
        "33": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "34": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "35": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "36": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "37": "LIMPIEZA EN SECO PROFESIONAL C",
        "38": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "39": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "40": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "41": "LIMPIEZA EN HUMEDO PROFESIONAL",
        "42": "LIMPIEZA EN HUMEDO PROFESIONAL",
    }

    const valores_cuidado_textil_2 = {
        "2": "_PROCESO MODERADO",
        "3": "_PROCESO MODERADO",
        "4": "_PROCESO MODERADO",
        "5": "_PROCESO MODERADO",
        "7": "_PROCESO NORMAL",
        "8": "_PROCESO MODERADO",
        "9": "_PROCESO MODERADO",
        "10": "_PROCESO MODERADO",
        "12": "ON TETRACLOROETILENO Y TODOS L",
        "14": "_PROCESO MODERADO",
        "17": "_PROCESO NORMAL",
        "18": "_PROCESO MODERADO",
        "19": "_PROCESO MODERADO",
        "20": "_PROCESO MODERADO",
        "22": "_PROCESO MODERADO",
        "24": "_PROCESO MODERADO",
        "25": "_PROCESO MODERADO",
        "26": "_PROCESO MODERADO",
        "27": "_PROCESO MODERADO",
        "29": "_PROCESO MODERADO",
        "30": "_PROCESO MODERADO", 
        "31": "_PROCESO MODERADO",
        "32": "ON TETRACLOROETILENO Y TODOS L",
        "33": "_PROCESO MODERADO",
        "34": "_PROCESO MODERADO",
        "35": "_PROCESO MODERADO",
        "36": "_PROCESO MODERADO",
        "37": "ON TETRACLOROETILENO Y TODOS L",
        "38": "_PROCESO MODERADO",
        "39": "_PROCESO MODERADO",
        "40": "_PROCESO MODERADO",
        "41": "_PROCESO MODERADO",
        "42": "_PROCESO MODERADO",
    }

    const valores_cuidado_textil_3 = {
        "12": "OS SOLVENTES ESTABLECIDOS PARA",
        "32": "OS SOLVENTES ESTABLECIDOS PARA",
        "37": "OS SOLVENTES ESTABLECIDOS PARA"
    }

    const valores_cuidado_textil_4 = {
        "12": "EL SIMBOLO F._PROCESO MODERA",
        "32": "EL SIMBOLO F._PROCESO MODERA",
        "37": "EL SIMBOLO F._PROCESO MODERA"
    }

    const valores_cuidado_textil_5 = {
        "12": "DO",
        "32": "DO",
        "37": "DO"
    };

    if (getCell('INSTRUCCION_DE_LAVADO_1')) getCell('INSTRUCCION_DE_LAVADO_1').textContent = valores_lavado_1[grupo] || "-";
    if (getCell('INSTRUCCION_DE_LAVADO_2')) getCell('INSTRUCCION_DE_LAVADO_2').textContent = valores_lavado_2[grupo] || "-";
    if (getCell('INSTRUCCION_DE_LAVADO_3')) getCell('INSTRUCCION_DE_LAVADO_3').textContent = valores_lavado_3[grupo] || "-";
    if (getCell('INSTRUCCION_DE_LAVADO_4')) getCell('INSTRUCCION_DE_LAVADO_4').textContent = valores_lavado_4[grupo] || "-";
    if (getCell('INSTRUCCION_DE_LAVADO_5')) getCell('INSTRUCCION_DE_LAVADO_5').textContent = valores_lavado_5[grupo] || "-";
    if (getCell('INSTRUCCION_BLANQUEADO_1')) getCell('INSTRUCCION_BLANQUEADO_1').textContent = valores_blanqueado_1[grupo] || "-";
    if (getCell('INSTRUCCION_BLANQUEADO_2')) getCell('INSTRUCCION_BLANQUEADO_2').textContent = valores_blanqueado_2[grupo] || "-";
    if (getCell('INSTRUCCION_BLANQUEADO_3')) getCell('INSTRUCCION_BLANQUEADO_3').textContent = valores_blanqueado_3[grupo] || "-";
    if (getCell('INSTRUCCION_BLANQUEADO_4')) getCell('INSTRUCCION_BLANQUEADO_4').textContent = valores_blanqueado_4[grupo] || "-";
    if (getCell('INSTRUCCION_BLANQUEADO_5')) getCell('INSTRUCCION_BLANQUEADO_5').textContent = valores_blanqueado_5[grupo] || "-";
    if (getCell('INSTRUCCION_SECADO_1')) getCell('INSTRUCCION_SECADO_1').textContent = valores_secado_1[grupo] || "-";
    if (getCell('INSTRUCCION_SECADO_2')) getCell('INSTRUCCION_SECADO_2').textContent = valores_secado_2[grupo] || "-";
    if (getCell('INSTRUCCION_SECADO_3')) getCell('INSTRUCCION_SECADO_3').textContent = valores_secado_3[grupo] || "-";
    if (getCell('INSTRUCCION_SECADO_4')) getCell('INSTRUCCION_SECADO_4').textContent = valores_secado_4[grupo] || "-";
    if (getCell('INSTRUCCION_SECADO_5')) getCell('INSTRUCCION_SECADO_5').textContent = valores_secado_5[grupo] || "-";
    if (getCell('INSTRUCCION_PLANCHADO_1')) getCell('INSTRUCCION_PLANCHADO_1').textContent = valores_planchado_1[grupo] || "-";
    if (getCell('INSTRUCCION_PLANCHADO_2')) getCell('INSTRUCCION_PLANCHADO_2').textContent = valores_planchado_2[grupo] || "-";
    if (getCell('INSTRUCCION_PLANCHADO_3')) getCell('INSTRUCCION_PLANCHADO_3').textContent = valores_planchado_3[grupo] || "-";
    if (getCell('INSTRUCCION_PLANCHADO_4')) getCell('INSTRUCCION_PLANCHADO_4').textContent = valores_planchado_4[grupo] || "-";
    if (getCell('INSTRUCCION_PLANCHADO_5')) getCell('INSTRUCCION_PLANCHADO_5').textContent = valores_planchado_5[grupo] || "-";
    if (getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_1')) getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_1').textContent = valores_cuidado_textil_1[grupo] || "-";
    if (getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_2')) getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_2').textContent = valores_cuidado_textil_2[grupo] || "-";
    if (getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_3')) getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_3').textContent = valores_cuidado_textil_3[grupo] || "-";
    if (getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_4')) getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_4').textContent = valores_cuidado_textil_4[grupo] || "-";
    if (getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_5')) getCell('INSTRUCC_CUIDADO_TEXTIL_PROF_5').textContent = valores_cuidado_textil_5[grupo] || "-";

    
    
}

// Función para obtener las opciones estáticas de los selects según el campo
function getStaticOptions(fieldName) {
    // Devuelve un array de objetos {value, text} según el campo solicitado
    switch (fieldName) {
        case "tipo":
            return [
                { value: "NACIONAL", text: "NACIONAL" },
                { value: "IMPORTADO", text: "IMPORTADO" }
            ];
        case "LINEA":
            return [
                { value: "Paquete Completo", text: "Paquete Completo" },
                { value: "Colaboracion", text: "Colaboracion" }
            ];
        case "YEAR":
            return [
                { value: "2020", text: "2020" },
                { value: "2021", text: "2021" },
                { value: "2022", text: "2022" },
                { value: "2023", text: "2023" },
                { value: "2024", text: "2024" },
                { value: "2025", text: "2025" },
                { value: "2026", text: "2026" },
                { value: "2027", text: "2027" },
                { value: "2028", text: "2028" },
                { value: "2029", text: "2029" },
                { value: "2030", text: "2030" }
            ];
        case "MES":
            return [
                { value: "01-Enero", text: "01-Enero" },
                { value: "02-Febrero", text: "02-Febrero" },
                { value: "03-Marzo", text: "03-Marzo" },
                { value: "04-Abril", text: "04-Abril" },
                { value: "05-Mayo", text: "05-Mayo" },
                { value: "06-Junio", text: "06-Junio" },
                { value: "07-Julio", text: "07-Julio" },
                { value: "08-Agosto", text: "08-Agosto" },
                { value: "09-Septiembre", text: "09-Septiembre" },
                { value: "10-Octubre", text: "10-Octubre" },
                { value: "11-Noviembre", text: "11-Noviembre" },
                { value: "12-Diciembre", text: "12-Diciembre" }
            ];
        case "OCASION_DE_USO":
            return [
                { value: "WOMAN", text: "WOMAN" },
                { value: "FAVORITO WOMAN", text: "FAVORITO WOMAN" },
                { value: "URBAN", text: "URBAN" },
                { value: "FAVORITO URBAN", text: "FAVORITO URBAN" },
                { value: "ESSENTIALS", text: "ESSENTIALS" },
                { value: "GLAM", text: "GLAM" },
                { value: "WORK", text: "WORK" },
                { value: "DOTACION", text: "DOTACION" },
                { value: "ESPECIALES", text: "ESPECIALES" },
                { value: "JW", text: "JW" }
            ];
        case "MODULO":
            return [
                { value: "M1", text: "M1" },
                { value: "M2", text: "M2" },
                { value: "M3", text: "M3" },
                { value: "M4", text: "M4" }
            ];
        case "CLIMA":
            return [
                { value: "CALIDO", text: "CALIDO" },
                { value: "FRIO", text: "FRIO" },
                { value: "AMBOS", text: "AMBOS" }
            ];
        case "TIENDA":
            return [
                { value: "XS", text: "XS" },
                { value: "S", text: "S" },
                { value: "M", text: "M" },
                { value: "L", text: "L" },
                { value: "XL", text: "XL" },
                { value: "XS-S", text: "XS-S" },
                { value: "XS-S-M", text: "XS-S-M" },
                { value: "XS-S-M-L", text: "XS-S-M-L" },
                { value: "XS-S-M-L-XL", text: "XS-S-M-L-XL" },
                { value: "S-M", text: "S-M" },
                { value: "S-M-L", text: "S-M-L" },
                { value: "S-M-L-XL", text: "S-M-L-XL" },
                { value: "M-L", text: "M-L" },
                { value: "M-L-XL", text: "M-L-XL" },
                { value: "L-XL", text: "L-XL" }
            ];
        case "CLASIFICACION":
            return [
                { value: "SLOW", text: "SLOW" },
                { value: "MEDIUM", text: "MEDIUM" },
                { value: "FAST", text: "FAST" },
                { value: "REPRICE", text: "REPRICE" },
                { value: "CATALOGO", text: "CATALOGO" },
                { value: "SLOW-SLOW", text: "SLOW-SLOW" },
                { value: "SLOW-MEDIUM", text: "SLOW-MEDIUM" },
                { value: "SLOW-FAST", text: "SLOW-FAST" },
                { value: "MEDIUM-SLOW", text: "MEDIUM-SLOW" },
                { value: "MEDIUM-MEDIUM", text: "MEDIUM-MEDIUM" },
                { value: "MEDIUM-FAST", text: "MEDIUM-FAST" },
                { value: "FAST-SLOW", text: "FAST-SLOW" },
                { value: "FAST-MEDIUM", text: "FAST-MEDIUM" },
                { value: "FAST-FAST", text: "FAST-FAST" },
            ];
        case "CLUSTER":
            return [
                {value:"A", text:"A"},
                {value:"B", text:"B"},
                {value:"C", text:"C"},
                {value:"A-B", text:"A-B"},
                {value:"A-B-C", text:"A-B-C"},
                {value:"B-C", text:"B-C"},
                {value:"A-C", text:"A-C"},
            ];
        case "PROVEEDOR":
            return [
                {value:"B&R FASHION", text:"B&R FASHION"},
                {value:"BODEGA DE MODA", text:"BODEGA DE MODA"},
                {value:"CREITEX", text:"CREITEX"},
                {value:"DINAMIC", text:"DINAMIC"},
                {value:"EVERFIT", text:"EVERFIT"},
                {value:"FANDA", text:"FANDA"},
                {value:"GLOBAL CONTEX", text:"GLOBAL CONTEX"},
                {value:"INHOUSE", text:"INHOUSE"},
                {value:"ISHAJON S.A.S.", text:"ISHAJON S.A.S."},
                {value:"J ORTIZ", text:"J ORTIZ"},
                {value:"LA COSTURETA", text:"LA COSTURETA"},
                {value:"LYNETTE", text:"LYNETTE"},
                {value:"PACK PLATINO", text:"PACK PLATINO"},
                {value:"QUANZHOU BLOSSOM", text:"QUANZHOU BLOSSOM"},
                {value:"SINOSKY", text:"SINOSKY"},
                {value:"SODIMCO", text:"SODIMCO"},
                {value:"UGLY DUCK", text:"UGLY DUCK"},
                {value:"XINGI", text:"XINGI"},
                {value:"MICTEX", text:"MICTEX"},
                {value:"VERSATILE TECHNOLOGY", text:"VERSATILE TECHNOLOGY"},
                {value:"DISEX", text:"DISEX"},
                {value:"MONALISA", text:"MONALISA"},
                {value:"PACCO", text:"PACCO"},
                {value:"XABBA", text:"XABBA"},
                {value:"INVERZAMAN", text:"INVERZAMAN"},
                {value:"EMBU", text:"EMBU"},
                {value:"CI-IBLU", text:"CI-IBLU"},
                {value:"TOXIC", text:"TOXIC"},
                {value:"CATNAT", text:"CATNAT"},
                {value:"J ORTIZ", text:"J ORTIZ"},
                {value:"VIKATS", text:"VIKATS"},
                {value:"PORKY", text:"PORKY"},
                {value:"NESD", text:"NESD"},
                {value:"BINBO APPAREL", text:"BINBO APPAREL"},
                {value:"HUZHOU ZHENGXING", text:"HUZHOU ZHENGXING"}
            ];
        case "CATEGORIAS":
            return [
                {value: "BLUSAS", text: "BLUSAS"},
                {value: "CAMISAS", text: "CAMISAS"},
                {value: "CAMISETAS", text: "CAMISETAS"},
                {value: "PUNTO", text: "PUNTO"},
                {value: "FELPA", text: "FELPA"},
                {value: "CHAQUETAS", text: "CHAQUETAS"},
                {value: "VESTIDOS", text: "VESTIDOS"},
                {value: "FALDAS", text: "FALDAS"},
                {value: "PANTALONES", text: "PANTALONES"},
                {value: "JEANS", text: "JEANS"},
            ];
        case "COLOR_FDS":
            return [
                {value: "100", text: "100"}, 
                {value: "101", text: "101"},
                {value: "102", text: "102"},
                {value: "102", text: "102"},
                {value: "103", text: "103"},
                {value: "103", text: "103"},
                {value: "106", text: "106"},
                {value: "106", text: "106"},
                {value: "109", text: "109"},
                {value: "109", text: "109"},
                {value: "110", text: "110"},
                {value: "110", text: "110"},
                {value: "121", text: "121"},
                {value: "121", text: "121"},
                {value: "123", text: "123"},
                {value: "203", text: "203"},
                {value: "207", text: "207"},
                {value: "209", text: "209"},
                {value: "219", text: "219"},
                {value: "220", text: "220"},
                {value: "224", text: "224"},
                {value: "233", text: "233"},
                {value: "237", text: "237"},
                {value: "258", text: "258"},
                {value: "260", text: "260"},
                {value: "263", text: "263"},
                {value: "264", text: "264"},
                {value: "266", text: "266"},
                {value: "277", text: "277"},
                {value: "279", text: "279"},
                {value: "281", text: "281"},
                {value: "283", text: "283"},
                {value: "284", text: "284"},
                {value: "300", text: "300"},
                {value: "301", text: "301"},
                {value: "313", text: "313"},
                {value: "315", text: "315"},
                {value: "319", text: "319"},
                {value: "322", text: "322"},
                {value: "328", text: "328"},
                {value: "337", text: "337"},
                {value: "350", text: "350"},
                {value: "354", text: "354"},
                {value: "356", text: "356"},
                {value: "357", text: "357"},
                {value: "361", text: "361"},
                {value: "362", text: "362"},
                {value: "363", text: "363"},
                {value: "366", text: "366"},
                {value: "368", text: "368"},
                {value: "367", text: "367"},
                {value: "370", text: "370"},
                {value: "372", text: "372"},
                {value: "375", text: "375"},
                {value: "369", text: "369"},
                {value: "380", text: "380"},
                {value: "393", text: "393"},
                {value: "394", text: "394"},
                {value: "395", text: "395"},
                {value: "401", text: "401"},
                {value: "407", text: "407"},
                {value: "417", text: "417"},
                {value: "418", text: "418"},
                {value: "431", text: "431"},
                {value: "454", text: "454"},
                {value: "463", text: "463"},
                {value: "473", text: "473"},
                {value: "480", text: "480"},
                {value: "481", text: "481"},
                {value: "460", text: "460"},
                {value: "461", text: "461"},
                {value: "464", text: "464"},
                {value: "467", text: "467"},
                {value: "475", text: "475"},
                {value: "479", text: "479"},
                {value: "482", text: "482"},
                {value: "484", text: "484"},
                {value: "494", text: "494"},
                {value: "505", text: "505"},
                {value: "504", text: "504"},
                {value: "510", text: "510"},
                {value: "513", text: "513"},
                {value: "515", text: "515"},
                {value: "556", text: "556"},
                {value: "565", text: "565"},
                {value: "567", text: "567"},
                {value: "570", text: "570"},
                {value: "575", text: "575"},
                {value: "579", text: "579"},
                {value: "583", text: "583"},
                {value: "588", text: "588"},
                {value: "591", text: "591"},
                {value: "592", text: "592"},
                {value: "596", text: "596"},
                {value: "597", text: "597"},
                {value: "606", text: "606"},
                {value: "608", text: "608"},
                {value: "611", text: "611"},
                {value: "613", text: "613"},
                {value: "622", text: "622"},
                {value: "623", text: "623"},
                {value: "624", text: "624"},
                {value: "625", text: "625"},
                {value: "626", text: "626"},
                {value: "627", text: "627"},
                {value: "700", text: "700"},
                {value: "701", text: "701"},
                {value: "803", text: "803"},
                {value: "811", text: "811"},
                {value: "815", text: "815"},
                {value: "817", text: "817"},
                {value: "819", text: "819"},
                {value: "999", text: "999"},
            ];
        case "PRINT":
            return [
                {value:"BLANCO", text:"BLANCO"},
                {value:"AMARILLO", text:"AMARILLO"},
                {value:"AZUL", text:"AZUL"},
                {value:"BEIGE", text:"BEIGE"},
                {value:"CAFE", text:"CAFE"},
                {value:"MAGENTA", text:"MAGENTA"},
                {value:"MORADO", text:"MORADO"},
                {value:"NARANJA", text:"NARANJA"},
                {value:"NEGRO", text:"NEGRO"},
                {value:"ROJO", text:"ROJO"},
                {value:"ROSADO", text:"ROSADO"},
                {value:"TURQUEZA", text:"TURQUEZA"},
                {value:"VERDE", text:"VERDE"},
            ];
        case "TALLAS":
            return [
                {value:"XXS", text:"XXS"},
                {value:"XS", text:"XS"},
                {value:"S", text:"S"},
                {value:"M", text:"M"},
                {value:"L", text:"L"},
                {value:"T", text:"T"},
                {value:"XL", text:"XL"},
                {value:"XXL", text:"XXL"},
                {value:"2", text:"2"},
                {value:"4", text:"4"},
                {value:"6", text:"6"},
                {value:"8", text:"8"},
                {value:"10", text:"10"},
                {value:"12", text:"12"},
                {value:"14", text:"14"},
                {value:"16", text:"16"},
                {value:"28", text:"28"},
                {value:"30", text:"30"},
                {value:"32", text:"32"},
                {value:"34", text:"34"},
                {value:"36", text:"36"},
                {value:"UN", text:"UN"},
            ];
        case "TIPO_DE_FIBRA":
            return [
                {value:"NATURAL", text:"NATURAL"},
                {value:"SINTETICA", text:"SINTETICA"}
            ];
        case "DETALLES":
            return [
                {value:"ACCESORIOS", text:"ACCESORIOS"},
                {value:"MANUALIDAD", text:"MANUALIDAD"}
            ];
        case "GRUPO":
            return [
                { value: "1", text: "GRUPO 1" },
                { value: "2", text: "GRUPO 2" },
                { value: "3", text: "GRUPO 2" },
                { value: "4", text: "GRUPO 4" },
                { value: "5", text: "GRUPO 5" },
                { value: "6", text: "GRUPO 6" },
                { value: "7", text: "GRUPO 7" },
                { value: "8", text: "GRUPO 8" },
                { value: "9", text: "GRUPO 9" },
                { value: "10", text: "GRUPO 10" },
                { value: "11", text: "GRUPO 11" },
                { value: "12", text: "GRUPO 12" },
                { value: "13", text: "GRUPO 13" },
                { value: "14", text: "GRUPO 14" },
                { value: "15", text: "GRUPO 15" },
                { value: "16", text: "GRUPO 16" },
                { value: "17", text: "GRUPO 17" },
                { value: "18", text: "GRUPO 18" },
                { value: "19", text: "GRUPO 19" },
                { value: "20", text: "GRUPO 20" },
                { value: "21", text: "GRUPO 21" },
                { value: "22", text: "GRUPO 22" },
                { value: "23", text: "GRUPO 23" },
                { value: "24", text: "GRUPO 24" },
                { value: "25", text: "GRUPO 25" },
                { value: "26", text: "GRUPO 26" },
                { value: "27", text: "GRUPO 27" },
                { value: "28", text: "GRUPO 28" },
                { value: "29", text: "GRUPO 29" },
                { value: "30", text: "GRUPO 30" },
                { value: "31", text: "GRUPO 31" },
                { value: "32", text: "GRUPO 32" },
                { value: "33", text: "GRUPO 33" },
                { value: "34", text: "GRUPO 34" },
                { value: "35", text: "GRUPO 35" },
                { value: "36", text: "GRUPO 36" },
                { value: "37", text: "GRUPO 37" },
                { value: "38", text: "GRUPO 38" },
                { value: "39", text: "GRUPO 39" },
                { value: "40", text: "GRUPO 40" },
                { value: "41", text: "GRUPO 41" },
                { value: "42", text: "GRUPO 42" },

            ];
        case "COMPOSICION_1":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ALGODON TANGÜIS", text: "ALGODON TANGÜIS"},
                {value: "ANGORA", text: "ANGORA"},
                {value: "CUERO", text: "CUERO"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "GAMUZA", text: "GAMUZA"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "NYLON", text: "NYLON"},
                {value: "PLASTICO", text: "PLASTICO"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "POLIESTER MICROFIBRA", text: "POLIESTER MICROFIBRA"},
                {value: "POLIESTER VISCOSA", text: "POLIESTER VISCOSA"},
                {value: "POLIURETANO", text: "POLIURETANO"},
                {value: "POLIVINYL", text: "POLIVINYL"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "SEDA", text: "SEDA"},
                {value: "SINTETICO", text: "SINTETICO"},
                {value: "SPANDEX", text: "SPANDEX"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        case "COMPOSICION_2":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ALGODON TANGÜIS", text: "ALGODON TANGÜIS"},
                {value: "ANGORA", text: "ANGORA"},
                {value: "CUERO", text: "CUERO"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "GAMUZA", text: "GAMUZA"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "NYLON", text: "NYLON"},
                {value: "PLASTICO", text: "PLASTICO"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "POLIESTER MICROFIBRA", text: "POLIESTER MICROFIBRA"},
                {value: "POLIESTER VISCOSA", text: "POLIESTER VISCOSA"},
                {value: "POLIURETANO", text: "POLIURETANO"},
                {value: "POLIVINYL", text: "POLIVINYL"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "SEDA", text: "SEDA"},
                {value: "SINTETICO", text: "SINTETICO"},
                {value: "SPANDEX", text: "SPANDEX"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        case "COMPOSICION_3":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ALGODON TANGÜIS", text: "ALGODON TANGÜIS"},
                {value: "ANGORA", text: "ANGORA"},
                {value: "CUERO", text: "CUERO"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "GAMUZA", text: "GAMUZA"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "NYLON", text: "NYLON"},
                {value: "PLASTICO", text: "PLASTICO"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "POLIESTER MICROFIBRA", text: "POLIESTER MICROFIBRA"},
                {value: "POLIESTER VISCOSA", text: "POLIESTER VISCOSA"},
                {value: "POLIURETANO", text: "POLIURETANO"},
                {value: "POLIVINYL", text: "POLIVINYL"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "SEDA", text: "SEDA"},
                {value: "SINTETICO", text: "SINTETICO"},
                {value: "SPANDEX", text: "SPANDEX"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        case "COMPOSICION_4":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ALGODON TANGÜIS", text: "ALGODON TANGÜIS"},
                {value: "ANGORA", text: "ANGORA"},
                {value: "CUERO", text: "CUERO"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "GAMUZA", text: "GAMUZA"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "NYLON", text: "NYLON"},
                {value: "PLASTICO", text: "PLASTICO"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "POLIESTER MICROFIBRA", text: "POLIESTER MICROFIBRA"},
                {value: "POLIESTER VISCOSA", text: "POLIESTER VISCOSA"},
                {value: "POLIURETANO", text: "POLIURETANO"},
                {value: "POLIVINYL", text: "POLIVINYL"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "SEDA", text: "SEDA"},
                {value: "SINTETICO", text: "SINTETICO"},
                {value: "SPANDEX", text: "SPANDEX"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ALGODON TANGÜIS", text: "ALGODON TANGÜIS"},
                {value: "ANGORA", text: "ANGORA"},
                {value: "CUERO", text: "CUERO"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "GAMUZA", text: "GAMUZA"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "NYLON", text: "NYLON"},
                {value: "PLASTICO", text: "PLASTICO"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "POLIESTER MICROFIBRA", text: "POLIESTER MICROFIBRA"},
                {value: "POLIESTER VISCOSA", text: "POLIESTER VISCOSA"},
                {value: "POLIURETANO", text: "POLIURETANO"},
                {value: "POLIVINYL", text: "POLIVINYL"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "SEDA", text: "SEDA"},
                {value: "SINTETICO", text: "SINTETICO"},
                {value: "SPANDEX", text: "SPANDEX"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        default:
            return [];
    }
}

// Evento principal al cargar la página: inicializa la tabla, listeners y validaciones
document.addEventListener('DOMContentLoaded', () => {
    const skuTableBody = document.querySelector('#skuTable tbody');
    const guardarButton = document.querySelector('#guardarBtn');

    // Evento para guardar los datos de la tabla
    guardarButton.addEventListener('click', () => {
    const allRowData = [];
    let filasInvalidas = [];
    let primerCampoFaltante = null;

    skuTableBody.querySelectorAll('.fila-carga').forEach((row, idx) => {
        const validacion = validarFilaObligatorios(row);
    if (!validacion.esValida) {
        filasInvalidas.push({ fila: idx + 1, campos: validacion.camposFaltantes });
        if (!primerCampoFaltante && validacion.camposFaltantes.length > 0) {
            primerCampoFaltante = row.querySelector(`.campo-formulario[data-campo-nombre="${validacion.camposFaltantes[0]}"]`);
        }
    }

        const validacionComp1 = validarComp1Mayor(row);
    if (!validacionComp1.esValida) {
        filasInvalidas.push({ fila: idx + 1, campos: [validacionComp1.mensaje] });
        if (!primerCampoFaltante) {
            primerCampoFaltante = row.querySelector('.campo-formulario[data-campo-nombre="%_COMP_1"]');
        }
    }
    const validacionForro1 = validarForro1Mayor(row);
    if (!validacionForro1.esValida) {
        filasInvalidas.push({ fila: idx + 1, campos: [validacionForro1.mensaje] });
        if (!primerCampoFaltante) {
            primerCampoFaltante = row.querySelector('.campo-formulario[data-campo-nombre="%_FORRO_1"]');
        }
    }
    const validacionRelleno1 = validarRelleno1Mayor(row);
    if (!validacionRelleno1.esValida) {
        filasInvalidas.push({ fila: idx + 1, campos: [validacionRelleno1.mensaje] });
        if (!primerCampoFaltante) {
            primerCampoFaltante = row.querySelector('.campo-formulario[data-campo-nombre="%_RELLENO_1"]');
        }
    }
        // Solo agrega la fila si es válida
        else {
            const rowData = {};
            row.querySelectorAll('.campo-formulario[data-campo-nombre]').forEach(field => {
                const fieldName = field.dataset.campoNombre;
                if (fieldName) {
                    if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                        rowData[fieldName] = field.value;
                    } else if (field.tagName === 'TD') {
                        rowData[fieldName] = field.textContent.trim();
                    }
                }
            });
            rowData['id'] = ID_CARGA;
            allRowData.push(rowData);
        }
    });

    if (filasInvalidas.length > 0) {
    let mensaje = "Por favor completa los campos obligatorios y revisa las reglas de composición antes de guardar.\n";
    filasInvalidas.forEach(fila => {
        mensaje += `Fila ${fila.fila}: ${fila.campos.join(', ')}\n`;
    });
    alert(mensaje);
    if (primerCampoFaltante) {
        primerCampoFaltante.focus();
    }
    return;
}

    // Si todo está bien, envía los datos
    fetch('../backend/update_manual.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rows: allRowData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
        } else {
            alert('Error al guardar: ' + (data.error || ''));
            if (data.errors && data.errors.length) {
                console.error('Errores:', data.errors);
            }
        }
    })
    .catch(err => {
        alert('Error de red o servidor: ' + err);
    });
    });

    // Lógica para actualizar selects dependientes usando la API

    //Funcion Async para buscar todas los campos "dependientes" en la fila
    async function fetchDependentOptionsAndUpdateRow(rowElement) {
    if (!rowElement) {
        console.error('Row element is null.');
        return;
    }

    // Recolectar la informacion actual de toda la fila, incluyendo campos estaticos
    const formValues = {};
    const formFieldsInRow = rowElement.querySelectorAll('.campo-formulario[data-campo-nombre]');
    formFieldsInRow.forEach(field => {
        const fieldName = field.dataset.campoNombre;
        if (fieldName) {
            if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                formValues[fieldName] = field.value;
            } else if (field.tagName === 'TD') {
                // Si es un TD, podría ser un valor estático mostrado, no un input.
                // Asegúrate que esto es lo que quieres recolectar.
                formValues[fieldName] = field.textContent.trim();
            }
        }
    });
    console.log('Form values collected from row:', formValues);

    // Recolectar los nombres de los campos dependientes en esta fila que necesitan ser actualizados
    const camposDestino = [];
    const dependentSelectsInRow = rowElement.querySelectorAll('select.campo-formulario[data-campo-nombre][data-campo-type="dependent"]');

    dependentSelectsInRow.forEach(selectElement => {
        if (selectElement.dataset.campoNombre) {
            camposDestino.push(selectElement.dataset.campoNombre);
        }
    });

    if (camposDestino.length === 0) {
        console.log('No dependent selects found in this row to update via API.');
        return;
    }

    const postData = {
        campos_destino: camposDestino,
        form_values: formValues
    };

    // Aplicar estado de carga a los selects dependientes
    dependentSelectsInRow.forEach(select => {
        select.disabled = true; // Deshabilitar para evitar interacción durante la carga
        select.style.opacity = '0.5';
    });

    try {
        const response = await fetch('../api/get_opciones.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(postData)
        });

        // Remover estado de carga de los selects dependientes
        dependentSelectsInRow.forEach(select => {
            select.disabled = false; // Habilitar nuevamente
            select.style.opacity = '1';
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error(`HTTP error fetching batched options for dependent fields: ${response.status} ${response.statusText}`, errorText);
            return;
        }

        const batchedResults = await response.json();

        if (typeof batchedResults !== 'object' || batchedResults === null) {
            console.error('Batched response for dependent fields is not a valid object:', batchedResults);
            // Manejo de respuesta no válida
            return;
        }

        camposDestino.forEach(targetFieldName => {
            const opciones = batchedResults[targetFieldName];
            const targetSelectElement = rowElement.querySelector(`select.campo-formulario[data-campo-nombre="${targetFieldName}"][data-campo-type="dependent"]`);

            if (!targetSelectElement) {
                console.warn(`Target select element for ${targetFieldName} not found in row.`);
                return;
            }

            const originalValue = targetSelectElement.value; 
            const dbPreselectedValue = targetSelectElement.dataset.valorActual;

            // Limpiar opciones existentes (Permite el 'Seleccione' por defecto)
            const firstOption = targetSelectElement.querySelector('option:first-child');
            targetSelectElement.innerHTML = ''; // Limpia todas las opciones
            let defaultOptionAdded = false;

            if (firstOption && (firstOption.value === '' || firstOption.value === null || firstOption.dataset.isDefaultPlaceholder === 'true')) {
                const clonedFirstOption = firstOption.cloneNode(true);
                targetSelectElement.appendChild(clonedFirstOption);
                defaultOptionAdded = true;
            }
            if (Array.isArray(opciones)) {
                opciones.forEach(opcion => {
                    const optionElement = document.createElement('option');
                    optionElement.value = opcion;
                    optionElement.textContent = opcion;
                    targetSelectElement.appendChild(optionElement);
                });

                // --- INICIO: Lógica MEJORADA para manejar el valor previo y el valor de la BD ---
                let valueSet = false;

                // Prioridad 1: Usar data-valor-actual (valor de la BD) si existe y es una opción válida
                if (dbPreselectedValue !== undefined && dbPreselectedValue !== '' && [...targetSelectElement.options].some(opt => opt.value === dbPreselectedValue)) {
                    targetSelectElement.value = dbPreselectedValue;
                    valueSet = true;
                }
                // Prioridad 2: Usar el valor original (seleccionado por el usuario antes del refresco) si existe y es una opción válida
                else if (originalValue !== undefined && originalValue !== '' && [...targetSelectElement.options].some(opt => opt.value === originalValue)) {
                    targetSelectElement.value = originalValue;
                    valueSet = true;
                }

                // Prioridad 3: Si no se pudo restaurar y hay una opción por defecto (placeholder), seleccionarla.
                // (Esto ya se maneja si defaultOptionAdded es true y el valor de firstOption es "" o null)
                // Si después de las prioridades 1 y 2, no se estableció valor, y hay una opción por defecto,
                // el select naturalmente quedará en esa opción por defecto si su valor es el primero.
                // Si la opción por defecto tiene un valor específico y no es "", se podría forzar aquí si es necesario.
                if (!valueSet && defaultOptionAdded) {
                     targetSelectElement.value = firstOption.value || ''; // Asegura que se seleccione la opción por defecto
                     valueSet = true; // Técnicamente ya estaría así, pero para ser explícitos.
                }
                // Si después de todo esto, valueSet sigue siendo false,
                // el navegador seleccionará la primera opción si no hay un valor que coincida.

            } else if (opciones !== undefined && opciones !== null) {
                console.error(`Response for dependent field ${targetFieldName} was not an array:`, opciones);
            } else {;
                 if (!defaultOptionAdded && targetSelectElement.options.length === 0) {
                } else if (defaultOptionAdded) {
                    targetSelectElement.value = firstOption.value || ''; 
                }
            }
             if (targetSelectElement.value !== originalValue || (dbPreselectedValue && targetSelectElement.value === dbPreselectedValue)) {
             }

        });

    } catch (error) {
        console.error('Error fetching dependent batched options:', error);
        // Remover estado de carga de los selects dependientes y aplicar un error visual si es necesario
        dependentSelectsInRow.forEach(select => {
            select.disabled = false;
            select.style.opacity = '1';
        });
    }
}

    skuTableBody.addEventListener('change', (event) => {
        const changedElement = event.target;

        // Validar si el elemento cambiado es un campo de formulario
        // y si tiene el atributo data-campo-nombre
        if (changedElement.classList.contains('campo-formulario') && changedElement.dataset.campoNombre) {

            const row = changedElement.closest('.fila-carga');

            if (row) {
                 // Cuando un campo cambia, se actualizan los selects dependientes
                 fetchDependentOptionsAndUpdateRow(row);

            } else {
                console.error('Could not find parent row for changed element.');
            }
        }
    });

    // Función para inicializar los campos de una fila (usuario, fecha, selects, listeners, etc.)
    function initializeRowFields(rowElement) {
    // 1. Usuario y fecha
    rowElement.querySelectorAll('.campo-formulario[data-campo-nombre]').forEach(field => {
        const fieldName = field.dataset.campoNombre;
        if (fieldName === 'usuario') {
            field.textContent = typeof CURRENT_USER !== 'undefined' ? CURRENT_USER : '-';
        } else if (fieldName === 'fecha_creacion') {
            field.textContent = getCurrentDateTimeString();
        }
    });

    // 2. Selects estáticos
    rowElement.querySelectorAll('select.campo-formulario[data-campo-type="static"][data-campo-nombre]').forEach(staticSelect => {
    const fieldName = staticSelect.dataset.campoNombre;
    const staticOptions = getStaticOptions(fieldName);
    const firstOption = staticSelect.querySelector('option:first-child');
    staticSelect.innerHTML = '';
    if (firstOption && (firstOption.value === '' || firstOption.value === null)) {
        staticSelect.appendChild(firstOption.cloneNode(true));
    }
    if (staticOptions && Array.isArray(staticOptions) && staticOptions.length > 0) {
        staticOptions.forEach(opt => {
            const optionElement = document.createElement('option');
            optionElement.value = opt.value;
            optionElement.textContent = opt.text;
            staticSelect.appendChild(optionElement);
        });
    }
    // Selecciona el valor actual si existe
    const valorActual = staticSelect.getAttribute('data-valor-actual');
    if (valorActual) {
        staticSelect.value = valorActual;
    } else {
        staticSelect.value = '';
    }
});

    // 4. Llama a la API para selects dependientes
    fetchDependentOptionsAndUpdateRow(rowElement);

    const moduloSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="MODULO"]');
    if (moduloSelect) {
        moduloSelect.addEventListener('change', function() {
            asignarvalortemporada(this);
        });
    }

    const colorSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="COLOR_FDS"]');
    if (colorSelect) {
        colorSelect.addEventListener('change', function() {
            asignarnomcolor(this);
        });
    }

    const gamaSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="GAMA"]');
    if (gamaSelect) {
        gamaSelect.addEventListener('change', function() {
            asignargamacolor(this);
        });
    }

    const grupoSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="GRUPO"]');
    if (grupoSelect) {
        grupoSelect.addEventListener('change', function() {
            asignarvalorgrupo(this);
        });
    }

    inicializarListenersComposicion(rowElement);
    actualizarTotalComposicion(rowElement);
    inicializarListenersForro(rowElement);
    actualizarTotalForro(rowElement); 
    inicializarListenersRelleno(rowElement);
    actualizarTotalRelleno(rowElement);
    agregarListenerCategoriaTallas(rowElement);
    }

    // Inicializa los campos de todas las filas existentes al cargar la página
    skuTableBody.querySelectorAll('.fila-carga').forEach(async row => {
    initializeRowFields(row);
    // Espera a que los selects dependientes se llenen
    await fetchDependentOptionsAndUpdateRow(row);
    // Ahora dispara los eventos para autollenar dependientes
    dispararEventosAutollenado(row);
});

    // Lógica para eliminar filas (excepto la primera)
    skuTableBody.addEventListener('click', (event) => {
         if (event.target.classList.contains('delete-row')) {
              event.target.closest('.fila-carga').remove();
         }
     });

    // Lógica para proteger la primera fila de ser eliminada
    function protegerPrimeraFila() {
        const filas = document.querySelectorAll('#skuTable tbody .fila-carga');
        filas.forEach((fila, idx) => {
            const btnEliminar = fila.querySelector('.delete-row');
            if (!btnEliminar) return;
            if (idx === 0 || fila.getAttribute('data_fila_original') === "1") {
                btnEliminar.style.display = 'none';
                btnEliminar.disabled = true;
            } else {
                btnEliminar.style.display = '';
                btnEliminar.disabled = false;
            }
        });
    }
    protegerPrimeraFila();
});
/**
 * Valida que los totales de composición, forro y relleno sean exactamente 100,
 * pero para FORRO y RELLENO solo si el select respectivo dice "SI TIENE".
 * Si no tiene, el total debe ser 0.
 * @param {HTMLElement} rowElement
 * @returns {Object} - { esValida: boolean, errores: array }
 */

function dispararEventosAutollenado(rowElement) {
    // Lista de campos que afectan dependientes
    const camposConDependientes = [
        "NOMBRE", 
        "CATEGORIAS"
    ];

    camposConDependientes.forEach(nombreCampo => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombreCampo}"]`);
        if (input) {
            // Dispara el evento 'input' y 'change' por si acaso
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
}


function validarTotalesFila(rowElement) {
    let esValida = true;
    let errores = [];

    // Composición siempre debe ser 100
    const compCell = rowElement.querySelector('.campo-formulario[data-campo-nombre="TOT_COMP"]');
    if (compCell) {
        const valor = parseFloat(compCell.textContent.trim());
        if (isNaN(valor) || valor !== 100) {
            esValida = false;
            errores.push("TOTAL COMPOSICION debe ser exactamente 100");
            compCell.style.backgroundColor = '#ffe0e0';
        } else {
            compCell.style.backgroundColor = '';
        }
    }

    // FORRO: depende del select FORRO
    const forroSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="FORRO"]');
    const forroCell = rowElement.querySelector('.campo-formulario[data-campo-nombre="TOT_FORRO"]');
    if (forroSelect && forroCell) {
        const tieneForro = (forroSelect.value && forroSelect.value.toUpperCase() === "SI TIENE");
        const valor = parseFloat(forroCell.textContent.trim());
        if (tieneForro) {
            if (isNaN(valor) || valor !== 100) {
                esValida = false;
                errores.push("TOTAL FORRO debe ser exactamente 100 (cuando SI TIENE)");
                forroCell.style.backgroundColor = '#ffe0e0';
            } else {
                forroCell.style.backgroundColor = '';
            }
        } else {
            if (valor !== 0) {
                esValida = false;
                errores.push("TOTAL FORRO debe ser 0 (cuando NO TIENE)");
                forroCell.style.backgroundColor = '#ffe0e0';
            } else {
                forroCell.style.backgroundColor = '';
            }
        }
    }

    // RELLENO: depende del select RELLENO
    const rellenoSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="RELLENO"]');
    const rellenoCell = rowElement.querySelector('.campo-formulario[data-campo-nombre="TOT_RELLENO"]');
    if (rellenoSelect && rellenoCell) {
        const tieneRelleno = (rellenoSelect.value && rellenoSelect.value.toUpperCase() === "SI TIENE");
        const valor = parseFloat(rellenoCell.textContent.trim());
        if (tieneRelleno) {
            if (isNaN(valor) || valor !== 100) {
                esValida = false;
                errores.push("TOTAL RELLENO debe ser exactamente 100 (cuando SI TIENE)");
                rellenoCell.style.backgroundColor = '#ffe0e0';
            } else {
                rellenoCell.style.backgroundColor = '';
            }
        } else {
            if (valor !== 0) {
                esValida = false;
                errores.push("TOTAL RELLENO debe ser 0 (cuando NO TIENE)");
                rellenoCell.style.backgroundColor = '#ffe0e0';
            } else {
                rellenoCell.style.backgroundColor = '';
            }
        }
    }

    return { esValida, errores };
}

// Hook para recalcular y validar cada vez que cambian los campos de composición, forro o relleno o los selects de FORRO/RELLENO
function agregarValidacionTotales(rowElement) {
    // Escucha cambios en los campos de composición, forro y relleno
    const campos = [
        '%_COMP_1', '%_COMP_2', '%_COMP_3', '%_COMP_4',
        '%_FORRO_1', '%_FORRO_2',
        '%_RELLENO_1', '%_RELLENO_2'
    ];
    campos.forEach(nombre => {
        const input = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (input) {
            input.addEventListener('input', function() {
                validarTotalesFila(rowElement);
            });
        }
    });

    // También escucha cambios en los selects de FORRO y RELLENO
    ['FORRO', 'RELLENO'].forEach(nombre => {
        const select = rowElement.querySelector(`.campo-formulario[data-campo-nombre="${nombre}"]`);
        if (select) {
            select.addEventListener('change', function() {
                validarTotalesFila(rowElement);
            });
        }
    });
}

const ID_CARGA = <?php echo $id; ?>;

// Aquí puedes reutilizar la mayoría de las funciones JS de carga_manual.php
// Solo cambia el fetch para que haga un UPDATE en vez de un INSERT

document.getElementById('modificarCargaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const row = document.querySelector('.fila-carga');
    const rowData = {};
    let esValida = true;
    let mensaje = "";
    let primerCampoFaltante = null;

    // Validar campos obligatorios
    const validacion = validarFilaObligatorios(row);
    if (!validacion.esValida) {
        esValida = false;
        mensaje += "Por favor completa los campos obligatorios:\n" + validacion.camposFaltantes.join(', ') + "\n";
        if (validacion.camposFaltantes.length > 0) {
            primerCampoFaltante = row.querySelector(`.campo-formulario[data-campo-nombre="${validacion.camposFaltantes[0]}"]`);
        }
    }

    // Validar reglas de composición
    const validacionComp1 = validarComp1Mayor(row);
    if (!validacionComp1.esValida) {
        esValida = false;
        mensaje += validacionComp1.mensaje + "\n";
        if (!primerCampoFaltante) {
            primerCampoFaltante = row.querySelector('.campo-formulario[data-campo-nombre="%_COMP_1"]');
        }
    }
    const validacionForro1 = validarForro1Mayor(row);
    if (!validacionForro1.esValida) {
        esValida = false;
        mensaje += validacionForro1.mensaje + "\n";
        if (!primerCampoFaltante) {
            primerCampoFaltante = row.querySelector('.campo-formulario[data-campo-nombre="%_FORRO_1"]');
        }
    }
    const validacionRelleno1 = validarRelleno1Mayor(row);
    if (!validacionRelleno1.esValida) {
        esValida = false;
        mensaje += validacionRelleno1.mensaje + "\n";
        if (!primerCampoFaltante) {
            primerCampoFaltante = row.querySelector('.campo-formulario[data-campo-nombre="%_RELLENO_1"]');
        }
    }

    // Validar totales de composición, forro y relleno
    const validacionTotales = validarTotalesFila(row);
    if (!validacionTotales.esValida) {
        esValida = false;
        mensaje += validacionTotales.errores.join('\n') + "\n";
        if (!primerCampoFaltante) {
            // Busca el primer campo con error visual
            const errorCell = row.querySelector('.campo-formulario[style*="background-color: rgb(255, 224, 224)"]');
            if (errorCell) primerCampoFaltante = errorCell;
        }
    }

    if (!esValida) {
        alert(mensaje);
        if (primerCampoFaltante) primerCampoFaltante.focus();
        return;
    }

    // Recoge los datos de la fila
    row.querySelectorAll('.campo-formulario[data-campo-nombre]').forEach(field => {
        const fieldName = field.dataset.campoNombre;
        if (fieldName) {
            if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                rowData[fieldName] = field.value;
            } else if (field.tagName === 'TD') {
                rowData[fieldName] = field.textContent.trim();
            }
        }
    });
    rowData['id'] = ID_CARGA;

    console.log('Datos enviados:', rowData);    // Envía como array de filas
    fetch('../backend/update_manual.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ rows: [rowData] })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('¡Datos actualizados correctamente!');
            window.location.href = 'visualizar_cargas.php';
        } else {
            alert('Error al actualizar: ' + (data.error || ''));
        }
    })
    .catch(err => {
        alert('Error de red o servidor: ' + err);
    });
});
</script>
</body>
</html>
