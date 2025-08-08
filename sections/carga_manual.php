
<?php
require_once __DIR__ . '/../backend/auth.php';

// Permite acceso a admin (1) y editor (2)
require_login_and_role([1, 2]);

$userName = $_SESSION['nombre'] ?? 'Usuario';
$userApellido = $_SESSION['apellido'] ?? 'Usuario';
$userRoleName = $_SESSION['user_role_name'] ?? 'Invitado';

date_default_timezone_set('America/Bogota');
$fecha_actual = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Manual</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="../js/tallas_dinamicas.js"></script>
    <script src="../js/ocultar_columnas.js"></script>
    <script src="../js/filtrar_categorias.js"></script>
    <script src="../js/replicar_filas_color.js"></script>
    <script src="../js/table-horizontal-scroll-sync.js"></script>
    <script src="../js/borrador_carga_manual.js"></script>
    <script src="../js/borrador_carga_manual_ui.js"></script>
    <script src="../js/function.js"></script>
    <script src="../js/function_update_rows.js"></script>
    <script src="../js/reproSelect.js"></script>
</head>
<body>
<div class="container">
    <h2>Carga Manual</h2>
    <div style="background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; padding: 18px 24px; margin-bottom: 32px; text-align: left; color: #5a6b58; font-size: 1.08em;">
        <strong>Antes de empezar:</strong>
        <ul style="margin-top: 10px;">
            <li>
            <b>Funciones principales:</b>
            <ul>
                <li>Permite ingresar manualmente información detallada de productos, incluyendo atributos como tipo, usuario, fecha de creación, composición, precios, tallas, colores, entre otros.</li>
                <li>Facilita la actualización y gestión de datos relevantes para inventario, ventas y reportes.</li>
            </ul>
            </li>
            <li>
            <b>Reglas y recomendaciones:</b>
            <ul>
                <li><b>Formato de los datos:</b> Asegúrate de que los datos ingresados correspondan al formato requerido para cada campo (por ejemplo, fechas en formato AAAA-MM-DD, precios en formato numérico, etc.). No dejes campos obligatorios vacíos. Y utiliza MAYUSCULAS para los campos abiertos</li>
                <li><b>Consistencia:</b> Utiliza nomenclaturas y valores consistentes, especialmente en campos como categorías, colores, tallas y proveedores, para evitar duplicidades o registros incorrectos.</li>
                <li>Recuerda<b> no llenar </b>el campo SAP, este se generara automaticamente.</li>
                <li><b>Validación:</b> Antes de guardar, revisa cuidadosamente la información ingresada. Utiliza las funciones de validación y vista previa si están disponibles.</li>
                <li><b>Evita caracteres especiales:</b> No utilices caracteres especiales no permitidos (por ejemplo, comillas, signos de puntuación innecesarios) que puedan causar errores en la base de datos.</li>
                <li><b>Soporte:</b> Si tienes dudas sobre el significado o formato de algún campo, comunícate con el área de soporte antes de continuar.</li>
            </ul>
            </li>
            <li>
            <b>Recomendación final:</b> Realiza cargas de prueba con pocos registros antes de procesar grandes volúmenes de datos. Esto te permitirá identificar y corregir posibles errores sin afectar la integridad de la información.
            </li>
        </ul>
        </div>

    <!-- Botón para volver al menú principal -->
    <div>
        <a href="index.php">
            <button>Volver al menu</button>     
        </a>
    </div>
    <div class = "opciones">
        <button id="abrirReplicarModalBtn" class="btn-replicar-modal">Replicar filas por tallas</button>
        <button id="abrirReplicarColorModalBtn" class="btn-replicar-color-modal">Replicar filas por color</button>
        <button id="abrirReproModalBtn" class="btn-repro-modal">Creacion en base a otro material (Re-Pro)</button>
        <button id="guardarBtn" class="btn btn-success">Guardar cambios</button>
        <button id="limpiarTablaBtn" style="background-color: #e74c3c; margin-left: 5px;">Limpiar</button>
    </div>
    <div class="custom-scrollbar-container" id="top-scrollbar-container">
    <div class="custom-scrollbar-track">
        <div class="custom-scrollbar-thumb" id="top-scrollbar-thumb"></div>
    </div>
    </div>
    <div class="table-container">
        <!-- 
        =========================
        TABLA PRINCIPAL DE CARGA
        =========================
        Cada columna representa un campo de la carga manual de productos.
        -->
        <table id="skuTable">
            <thead>
                <tr class="table-header">
                    <!-- Encabezados de la tabla, cada uno con su data-campo-nombre para identificación -->
                    <th data-campo-nombre="tipo">TIPO DE PRODUCTO</th>
                    <th data-campo-nombre="LINEA">LINEA DEL PRODUCTO</th>
                    <th data-campo-nombre="usuario">USUARIO</th>
                    <th data-campo-nombre="fecha_creacion">FECHA DE CREACION</th>
                    <th data-campo-nombre="SAP">SAP</th>
                    <th data-campo-nombre="YEAR">AÑO</th>
                    <th data-campo-nombre="MES">MES</th>
                    <th data-campo-nombre="OCASION_DE_USO">OCASION DE USO</th>
                    <th data-campo-nombre="NOMBRE" id="NOMBRE-HEADER">NOMBRE</th>
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
                    <th data-campo-nombre="DESCRIPCION" id="DESCRIPCION-HEADER">DESCRIPCION</th>
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
                    <th data-campo-nombre="COLOR_FDS"><button id="consultarColores" class="btn btn-abrir-colores">Ver colores</button><br>COLOR FDS</th>
                    <th data-campo-nombre="NOM_COLOR">NOMBRE COLOR</th>
                    <th data-campo-nombre="GAMA">GAMA</th>
                    <th data-campo-nombre="PRINT">PRINT</th>
                    <th data-campo-nombre="TALLAS">TALLAS</th>
                    <th data-campo-nombre="TIPO_TEJIDO">TIPO DE TEJIDO</th>
                    <th data-campo-nombre="TIPO_DE_FIBRA">TIPO DE FIBRA</th>
                    <th data-campo-nombre="BASE_TEXTIL">BASE TEXTIL</th>
                    <th data-campo-nombre="DETALLES">DETALLES</th>
                    <th data-campo-nombre="SUB_DETALLES">SUB-DETALLES</th>
                    <th data-campo-nombre="GRUPO"><button id="consultar_grupos">Mostrar / Ocultar</button>GRUPO</th>
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
                    <th data-campo-nombre="XX">XX</th>
                    <th data-campo-nombre="precio_compra">PRECIO DE COMPRA</th>
                    <th data-campo-nombre="costo">COSTO</th>
                    <th data-campo-nombre="precio_venta">PRECIO DE VENTA</th>
                    <th data-campo-nombre="ACCIONES">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fila-carga" data_fila_original="1"> 
                    <!-- 
                    =========================
                    FILA DE EJEMPLO/INICIAL
                    =========================
                    Cada celda contiene un input/select o un valor estático, según el campo.
                    -->
                    <td>
                        <select class="campo-formulario" data-campo-nombre="tipo" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="LINEA" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="usuario" data-campo-type="static"><?php echo htmlspecialchars($userName); echo " "; echo htmlspecialchars($userApellido) ?></td>
                    <td class="campo-formulario" data-campo-nombre="fecha_creacion" data-campo-type="static"><?php echo htmlspecialchars($fecha_actual); ?></td>
                    <td>
                        <input type="text"  class="campo-formulario" data-campo-nombre="SAP" data-campo-type="static" readonly></input>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="YEAR" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="MES" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="OCASION_DE_USO" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="campo-formulario" id="NOMBRE" data-campo-nombre="NOMBRE" data-campo-type="static" oninput="this.value=this.value.toUpperCase();">
                    </td>
                    <td>
                        <select id="mod" class="campo-formulario" data-campo-nombre="MODULO" data-campo-type="static" onchange="asignarvalortemporada(this)">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td id="temp" class="campo-formulario" data-campo-nombre="TEMPORADA" data-campo-type="static">-</td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CAPSULA" data-campo-type="variable">
                            <option value="">Seleccione</option>
                            <!-- Las opciones se llenarán dinámicamente por JS -->
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CLIMA" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIENDA" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CLASIFICACION" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CLUSTER" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="PROVEEDOR" data-campo-type="variable">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CATEGORIAS" data-campo-type="static">
                            <option value="">Seleccione</option> 
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="SUBCATEGORIAS" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="DISENO" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="campo-formulario" id="DESCRIPCION" data-campo-nombre="DESCRIPCION" data-campo-type="static" oninput="this.value=this.value.toUpperCase();">
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="MANGA" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_MANGA" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="PUNO"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CAPOTA"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="ESCOTE"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="LARGO"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CUELLO" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIRO"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="BOTA"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CINTURA"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="SILUETA"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="CIERRE"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="GALGA"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_GALGA"
                        data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td >
                        <select id="color_fds" class="campo-formulario" data-campo-nombre="COLOR_FDS" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td id="nom_color" class="campo-formulario" data-campo-nombre="NOM_COLOR" data-campo-type="static">-</td> 
                    <td id="gama" class="campo-formulario" data-campo-nombre="GAMA" data-campo-type="static">-</td>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="PRINT" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TALLAS" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_TEJIDO" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="TIPO_DE_FIBRA" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="BASE_TEXTIL" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="DETALLES" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="SUB_DETALLES" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="GRUPO" data-campo-type="static" id="grupo">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_1" data-campo-type="static" id="instruccion_lavado_1">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_2" data-campo-type="static" id="instruccion_lavado_2">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_3" data-campo-type="static" id="instruccion_lavado_3">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_4" data-campo-type="static" id="instruccion_lavado_4">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_DE_LAVADO_5" data-campo-type="static" id="instruccion_lavado_5">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_1" data-campo-type="static" id="instruccion_blanqueado_1">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_2" data-campo-type="static" id="instruccion_blanqueado_2">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_3" data-campo-type="static" id="instruccion_blanqueado_3">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_4" data-campo-type="static" id="instruccion_blanqueado_4">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_BLANQUEADO_5" data-campo-type="static" id="instruccion_blanqueado_5">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_1" data-campo-type="static" id="instruccion_secado_1">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_2" data-campo-type="static" id="instruccion_secado_2">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_3" data-campo-type="static" id="instruccion_secado_3">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_4" data-campo-type="static" id="instruccion_secado_4">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_SECADO_5" data-campo-type="static" id="instruccion_secado_5">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_1" data-campo-type="static" id="instruccion_planchado_1">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_2" data-campo-type="static" id="instruccion_planchado_2">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_3" data-campo-type="static" id="instruccion_planchado_3">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_4" data-campo-type="static" id="instruccion_planchado_4">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCCION_PLANCHADO_5" data-campo-type="static" id="instruccion_planchado_5">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_1" data-campo-type="static" id="instruccion_cuidado_textil_1">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_2" data-campo-type="static" id="instruccion_cuidado_textil_2">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_3" data-campo-type="static" id="instruccion_cuidado_textil_3">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_4" data-campo-type="static" id="instruccion_cuidado_textil_4">-</td>
                    <td class="campo-formulario" data-campo-nombre="INSTRUCC_CUIDADO_TEXTIL_PROF_5" data-campo-type="static" id="instruccion_cuidado_textil_5">-</td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_1" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_1" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_2" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_2" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_3" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_3" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMPOSICION_4" data-campo-type="static">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_COMP_4" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="TOT_COMP" data-campo-type="static">-
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="FORRO" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_FORRO_1" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_FORRO_1" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_FORRO_2" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_FORRO_2" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="TOT_FORRO" data-campo-type="static">-
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="RELLENO" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_RELLENO_1" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_RELLENO_1" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <select class="campo-formulario" data-campo-nombre="COMP_RELLENO_2" data-campo-type="dependent">
                            <option value="">Seleccione</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="%_RELLENO_2" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td class="campo-formulario" data-campo-nombre="TOT_RELLENO" data-campo-type="static">-
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="XX" data-campo-type="static" readonly></input>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="precio_compra" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="costo" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td>
                        <input type="number" class="campo-formulario" data-campo-nombre="precio_venta" data-campo-type="static" oninput="this.value=this.value.toUpperCase();"></input>
                    </td>
                    <td><button class="delete-row">Eliminar</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="modal_grupos" hidden>
        <h3>Grupos de instrucciones</h3>
        <table id="table_groups">
            <thead>
                <tr>
                    <th>SAP</th>                       
                    <th>INSTRUCCION_DE_LAVADO_1</th>       
                    <th>INSTRUCCION_DE_LAVADO_2</th>       
                    <th>INSTRUCCION_DE_LAVADO_3</th>       
                    <th>INSTRUCCION_DE_LAVADO_4</th>       
                    <th>INSTRUCCION_DE_LAVADO_5</th>       
                    <th>INSTRUCCION_DE_BLANQUEADO_1</th>   
                    <th>INSTRUCCION_DE_BLANQUEADO_2</th>   
                    <th>INSTRUCCION_DE_BLANQUEADO_3</th>   
                    <th>INSTRUCCION_DE_BLANQUEADO_4</th>   
                    <th>INSTRUCCION_DE_BLANQUEADO_5</th>   
                    <th>INSTRUCCION_DE_SECADO_1</th>       
                    <th>INSTRUCCION_DE_SECADO_2</th>       
                    <th>INSTRUCCION_DE_SECADO_3</th>       
                    <th>INSTRUCCION_DE_SECADO_4</th>       
                    <th>INSTRUCCION_DE_SECADO_5</th>       
                    <th>INSTRUCCION_DE_PLANCHADO_1</th>    
                    <th>INSTRUCCION_DE_PLANCHADO_2</th>    
                    <th>INSTRUCCION_DE_PLANCHADO_3</th>    
                    <th>INSTRUCCION_DE_PLANCHADO_4</th>    
                    <th>INSTRUCCION_DE_PLANCHADO_5</th>    
                    <th>INSTRUCC_CUIDADO_TEXTIL_PROF_1</th>
                    <th>INSTRUCC_CUIDADO_TEXTIL_PROF_2</th>
                    <th>INSTRUCC_CUIDADO_TEXTIL_PROF_3</th>
                    <th>INSTRUCC_CUIDADO_TEXTIL_PROF_4</th>
                    <th>INSTRUCC_CUIDADO_TEXTIL_PROF_5</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
    </div>
    <div id="reproModal" class="modal" hidden>
        <div class="modal-contenido">
            <span class="close">X</span>
            <div class="modal-header">
                <h3>Creacion de Re-pros</h3>
            </div>
            <div class="modal-content">
                <div class="modal-label">
                    <label for="reproSelect">Material base:</label>
                </div>
                <select class="reproSelect" size="10">
                </select>
            </div>
            <div class="aceptar">
                <button type="button" id="aceptarReproBtn" class="btn">Aceptar</button>
            </div>
        </div>
    </div>
    <div id="coloresModal" class="modal-colores" hidden>
        <div class="modal-colores-contenido">
            <span class="close-colores">X</span>
            <div class="modal-colores-header">
                <h3>Tabla de colores</h3>
            </div>
            <div class="tabla-colores">
                <table>
                <thead>
                    <tr>
                        <th>Codigo de color</th>
                        <th>Nombre de color</th>
                        <th>Gama de color</th>
                    <tr>
                </thead>
                <tbody id="tableBodyColores"></tbody>
            </table>
            </div>       
        </div>
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
    "tipo", "LINEA", "usuario", "fecha_creacion", "YEAR", "TOT_COMP", "TIPO_TEJIDO", "TIPO_DE_FIBRA", "TIENDA", "TEMPORADA", "TALLAS", "SUBCATEGORIAS", "PROVEEDOR", "OCASION_DE_USO", "NOM_COLOR", "NOMBRE", "MODULO", "MES", "GRUPO", "GAMA", "DESCRIPCION", "COLOR_FDS", "CLUSTER", "CLIMA", "CLASIFICACION", "CATEGORIAS", "CAPSULA", "BASE_TEXTIL", "%_COMP_1", "COMPOSICION_1"
];

/**
 * Valida que el campo principal de un grupo (%_COMP_1, %_FORRO_1, %_RELLENO_1) sea el mayor o igual
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

    // Ahora valida que el primero sea mayor o igual que los otros
    if (!valores.slice(1).every(v => valores[0] >= v)) {
        esValida = false;
        mensaje = `${label} 1 debe ser mayor o igual que los otros ${count - 1} porcentajes`;
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
            // Llena todos los selects de CAPSULA con las opciones recibidas
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
            });
        })
        .catch(err => {
            console.error('Error cargando cápsulas:', err);
        });
}

function cargarProveedoresEnSelects() {
    fetch('../api/get_proveedores.php')
        .then(response => response.json())
        .then(data => {
            // Llena todos los selects de CAPSULA con las opciones recibidas
            document.querySelectorAll('select.campo-formulario[data-campo-nombre="PROVEEDOR"]').forEach(select => {
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
            });
        })
        .catch(err => {
            console.error('Error cargando cápsulas:', err);
        });
}

// Llama a la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarCapsulasEnSelects);
document.addEventListener('DOMContentLoaded', cargarProveedoresEnSelects);

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

function validarPrintMulticolor(rowElement) {
    const colorFdsSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="COLOR_FDS"]');
    const printSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="PRINT"]');
    if (!colorFdsSelect || !printSelect) return true;

    const colorFdsValue = colorFdsSelect.value.trim();
    const printValue = printSelect.value.trim();

    // Si COLOR_FDS no es 999 y PRINT tiene valor, error
    if (colorFdsValue !== "999" && printValue !== "") {
        alert("Solo Multicolor (999) puede tener Print. Por favor, deje el campo PRINT vacío para este color.");
        printSelect.value = "";
        printSelect.focus();
        printSelect.style.backgroundColor = "#ffe0e0";
        setTimeout(() => { printSelect.style.backgroundColor = ""; }, 1500);
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const guardarBtn = document.getElementById('guardarBtn');
    if (guardarBtn) {
        guardarBtn.addEventListener('click', function(e) {
            let errorEncontrado = false;
            const skuTableBody = document.querySelector('#skuTable tbody');
            skuTableBody.querySelectorAll('.fila-carga').forEach(row => {
                if (!validarPrintMulticolor(row)) {
                    errorEncontrado = true;
                }
            });
            if (errorEncontrado) {
                e.preventDefault();
                alert("Corrige los errores de Print antes de guardar.");
            }
        });
    }
});

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
        "35": "LAVAR A MANO",
        "36": "LAVAR A MANO",
        "37": "NO LAVAR",
        "38": "LAVAR A MANO",
        "39": "LAVAR A MANO",
        "40": "LAVAR A MANO",
        "41": "LAVAR A MANO",
        "42": "LAVAR A MANO",
        "43": "LAVAR A MANO TEMPERATURA"
    }

    const valores_lavado_2 = {
        "43": "MAXIMA 40°"
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
        "42": "NO USAR BLANQUEADOR",
        "43": "NO USAR BLANQUEADOR"
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
        "42": "SECADO EXTENDIDO A LA SOMBRA",
        "43": "SECADO EXTENDIDO A LA SOMBRA"
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
        "42": "PLANCHAR A UNA TEMPERATURA MAX",
        "43": "NO PLANCHAR"
    }

    const valores_planchado_2 = {
        "2":"IMA DE LA BASE DE 110°C, SIN",
        "5":"IMA DE LA BASE DE 150°C",
        "13": "IMA DE LA BASE DE 110°C, SIN",
        "14": "IMA DE LA BASE DE 150°C",
        "16": "IMA DE LA BASE DE 110°C, SIN",
        "23": "IMA DE LA BASE DE 110°C, SIN",
        "26": "IMA DE LA BASE DE 110°C, SIN",
        "27": "IMA DE LA BASE DE 110°C, SIN",
        "29": "IMA DE LA BASE DE 110°C, SIN",
        "38": "IMA DE LA BASE DE 110°C, SIN",
        "40": "IMA DE LA BASE DE 110°C, SIN",
        "41": "IMA DE LA BASE DE 110°C, SIN",
        "42": "IMA DE LA BASE DE 110°C, SIN"
    }

    const valores_planchado_3 = {
        "2": "VAPOR",
        "13": "VAPOR",
        "16": "VAPOR",
        "23": "VAPOR",
        "26": "VAPOR",
        "27": "VAPOR",
        "29": "VAPOR",
        "38": "VAPOR",
        "40": "VAPOR",
        "41": "VAPOR",
        "42": "VAPOR",
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
        "43": "NO LIMPIEZA EN SECO",
    }

    const valores_cuidado_textil_2 = {
        "2": "_PROCESO MODERADO",
        "3": "_PROCESO MODERADO",
        "4": "_PROCESO MODERADO",
        "5": "_PROCESO NORMAL",
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
        case "LINEA":
            return [
                { value: "Paquete Completo", text: "Paquete Completo" },
                { value: "Colaboracion", text: "Colaboracion" },
                { value: "Muestras", text: "Muestras" }
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
                { value: "JW", text: "JW" },
                { value: "WORK", text: "WORK" },
                { value: "DOTACION", text: "DOTACION" },
                { value: "ESPECIALES", text: "ESPECIALES" },
                { value: "OUTLET", text: "OUTLET" },
                { value: "DENIM", text: "DENIM" }
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
                { value: "100", text: "100" },
                { value: "101", text: "101" },
                { value: "102", text: "102" },
                { value: "103", text: "103" },
                { value: "109", text: "109" },
                { value: "121", text: "121" },
                { value: "123", text: "123" },
                { value: "203", text: "203" },
                { value: "204", text: "204" },
                { value: "205", text: "205" },
                { value: "207", text: "207" },
                { value: "208", text: "208" },
                { value: "209", text: "209" },
                { value: "218", text: "218" },
                { value: "220", text: "220" },
                { value: "224", text: "224" },
                { value: "258", text: "258" },
                { value: "260", text: "260" },
                { value: "263", text: "263" },
                { value: "264", text: "264" },
                { value: "266", text: "266" },
                { value: "270", text: "270" },
                { value: "275", text: "275" },
                { value: "276", text: "276" },
                { value: "277", text: "277" },
                { value: "279", text: "279" },
                { value: "281", text: "281" },
                { value: "283", text: "283" },
                { value: "284", text: "284" },
                { value: "300", text: "300" },
                { value: "312", text: "312" },
                { value: "313", text: "313" },
                { value: "315", text: "315" },
                { value: "318", text: "318" },
                { value: "319", text: "319" },
                { value: "322", text: "322" },
                { value: "328", text: "328" },
                { value: "330", text: "330" },
                { value: "337", text: "337" },
                { value: "350", text: "350" },
                { value: "353", text: "353" },
                { value: "354", text: "354" },
                { value: "356", text: "356" },
                { value: "357", text: "357" },
                { value: "358", text: "358" },
                { value: "361", text: "361" },
                { value: "362", text: "362" },
                { value: "363", text: "363" },
                { value: "365", text: "365" },
                { value: "366", text: "366" },
                { value: "367", text: "367" },
                { value: "368", text: "368" },
                { value: "369", text: "369" },
                { value: "370", text: "370" },
                { value: "375", text: "375" },
                { value: "377", text: "377" },
                { value: "380", text: "380" },
                { value: "393", text: "393" },
                { value: "399", text: "399" },
                { value: "401", text: "401" },
                { value: "407", text: "407" },
                { value: "417", text: "417" },
                { value: "418", text: "418" },
                { value: "424", text: "424" },
                { value: "431", text: "431" },
                { value: "452", text: "452" },
                { value: "453", text: "453" },
                { value: "454", text: "454" },
                { value: "459", text: "459" },
                { value: "460", text: "460" },
                { value: "462", text: "462" },
                { value: "463", text: "463" },
                { value: "464", text: "464" },
                { value: "467", text: "467" },
                { value: "473", text: "473" },
                { value: "475", text: "475" },
                { value: "476", text: "476" },
                { value: "479", text: "479" },
                { value: "480", text: "480" },
                { value: "481", text: "481" },
                { value: "482", text: "482" },
                { value: "483", text: "483" },
                { value: "484", text: "484" },
                { value: "490", text: "490" },
                { value: "491", text: "491" },
                { value: "503", text: "503" },
                { value: "504", text: "504" },
                { value: "505", text: "505" },
                { value: "510", text: "510" },
                { value: "513", text: "513" },
                { value: "515", text: "515" },
                { value: "556", text: "556" },
                { value: "565", text: "565" },
                { value: "566", text: "566" },
                { value: "567", text: "567" },
                { value: "570", text: "570" },
                { value: "572", text: "572" },
                { value: "575", text: "575" },
                { value: "576", text: "576" },
                { value: "579", text: "579" },
                { value: "581", text: "581" },
                { value: "583", text: "583" },
                { value: "587", text: "587" },
                { value: "588", text: "588" },
                { value: "592", text: "592" },
                { value: "596", text: "596" },
                { value: "597", text: "597" },
                { value: "605", text: "605" },
                { value: "606", text: "606" },
                { value: "608", text: "608" },
                { value: "609", text: "609" },
                { value: "611", text: "611" },
                { value: "613", text: "613" },
                { value: "614", text: "614" },
                { value: "623", text: "623" },
                { value: "624", text: "624" },
                { value: "625", text: "625" },
                { value: "626", text: "626" },
                { value: "627", text: "627" },
                { value: "700", text: "700" },
                { value: "701", text: "701" },
                { value: "803", text: "803" },
                { value: "811", text: "811" },
                { value: "815", text: "815" },
                { value: "819", text: "819" },
                { value: "821", text: "821" },
                { value: "999", text: "999" }
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
                { value: "3", text: "GRUPO 3" },
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
                { value: "43", text: "GRUPO 43" }

            ];
        case "COMPOSICION_1":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "PU", text: "PU"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        case "COMPOSICION_2":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "PU", text: "PU"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        case "COMPOSICION_3":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "PU", text: "PU"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
        case "COMPOSICION_4":
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "PU", text: "PU"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
                {value: "TENCEL", text: "TENCEL"},
                {value: "VISCOSA", text: "VISCOSA"},
            ];
            return [
                {value: "ACRILICO", text: "ACRILICO"},
                {value: "ALGODON", text: "ALGODON"},
                {value: "ALGODON MEZCLAS", text: "ALGODON MEZCLAS"},
                {value: "ELASTANO", text: "ELASTANO"},
                {value: "ELASTOMERO", text: "ELASTOMERO"},
                {value: "LANA", text: "LANA"},
                {value: "LINO", text: "LINO"},
                {value: "LUREX", text: "LUREX"},
                {value: "LYCRA", text: "LYCRA"},
                {value: "MODAL", text: "MODAL"},
                {value: "POLIAMIDA", text: "POLIAMIDA"},
                {value: "POLIPROPILENO", text: "POLIPROPILENO"},
                {value: "POLIESTER", text: "POLIESTER"},
                {value: "POLIESTER MEZCLAS", text: "POLIESTER MEZCLAS"},
                {value: "PU", text: "PU"},
                {value: "RAYON", text: "RAYON"},
                {value: "RAYON VISCOSA", text: "RAYON VISCOSA"},
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
    fetch('../backend/save_manual.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rows: allRowData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('¡Datos guardados correctamente! Filas insertadas: ' + data.inserted);
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

        // Recoletar la informacion actual de toda la fila, incluyendo campos estaticos
        const formValues = {};
        const formFieldsInRow = rowElement.querySelectorAll('.campo-formulario[data-campo-nombre]');
        formFieldsInRow.forEach(field => {
             const fieldName = field.dataset.campoNombre;
             if (fieldName) {
                  if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                      formValues[fieldName] = field.value;
                  } else if (field.tagName === 'TD') {
                       formValues[fieldName] = field.textContent.trim();
                  }
             }
        })
        console.log('Form values collected from row:', formValues)
        ;

        // Recolectar los nombres de los campos dependientes en esta fila que necesitan ser actualizados
        const camposDestino = [];
        // Selecciona elementos con clase "campo-formulario" y atributos "data-campo-nombre" y "data-campo-type" igual a "dependent"
        const dependentSelectsInRow = rowElement.querySelectorAll('select.campo-formulario[data-campo-nombre][data-campo-type="dependent"]');

        dependentSelectsInRow.forEach(selectElement => {
            if (selectElement.dataset.campoNombre) {
                camposDestino.push(selectElement.dataset.campoNombre);
            }
        });

        // Si no hay selects dependientes en esta fila, no es necesario hacer la llamada a la API
        if (camposDestino.length === 0) {
            console.log('No dependent selects found in this row to update via API.');
            return;
        }

        // Preparar los datos para enviar a la API
        const postData = {
            campos_destino: camposDestino, // Solo enviar los campos dependientes
            form_values: formValues       // Enviar todos los valores de la fila
        };
         // Aplicar estado de carga a los selects dependientes
         dependentSelectsInRow.forEach(select => {
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
                  select.style.opacity = '1';
             });

            if (!response.ok) {
                const errorText = await response.text();
                console.error(`HTTP error fetching batched options for dependent fields: ${response.status} ${response.statusText}`, errorText);
                 // Manejo de error visual
                 dependentSelectsInRow.forEach(select => {
                 });
                 return;
            }

            const batchedResults = await response.json();

            if (typeof batchedResults !== 'object' || batchedResults === null) {
                 console.error('Batched response for dependent fields is not a valid object:', batchedResults);
                 // Manejo de respuesta no válida
                 dependentSelectsInRow.forEach(select => {
                 });
                 return;
            }

            // Iteraar sobre los selects dependientes en la fila
            // Iterar sobre los nombres de los campos dependientes para asegurar que sean los mismos que los de la respuesta
            camposDestino.forEach(targetFieldName => {
                // El php podria devolver un array vacio si no hay opciones para ese campo
                const opciones = batchedResults[targetFieldName];

                const targetSelectElement = rowElement.querySelector(`select.campo-formulario[data-campo-nombre="${targetFieldName}"][data-campo-type="dependent"]`);

                if (!targetSelectElement) {
                     // Podria no pasar si el select fue eliminado o no existe
                     return;
                }

                 const originalValue = targetSelectElement.value;

                // Limpiar opciones existetes (Permite el 'Seleccione' por defecto)
                const firstOption = targetSelectElement.querySelector('option:first-child');
                targetSelectElement.innerHTML = '';
                let defaultOptionAdded = false;
                if (firstOption && (firstOption.value === '' || firstOption.value === null)) {
                     targetSelectElement.appendChild(firstOption.cloneNode(true));
                     defaultOptionAdded = true;
                }

                // llENAR CON OPCIONES NUEVAS y manejar el valor original si existe
                if (Array.isArray(opciones)) { // Validar si las opciones son un array
                    opciones.forEach(opcion => {
                        const optionElement = document.createElement('option');
                        optionElement.value = opcion;
                        optionElement.textContent = opcion;
                        targetSelectElement.appendChild(optionElement);
                    });

                    // --- Manejar el valor previo ---
                    if ([...targetSelectElement.options].some(option => option.value === originalValue)) {
                        targetSelectElement.value = originalValue;
                    } else {
                         // Si el valor previo ya no es valido, mantener el valor por defecto
                         targetSelectElement.value = defaultOptionAdded ? (firstOption.value || '') : '';
                    }

                } else if (opciones !== undefined && opciones !== null) { 
                     console.error(`Response for dependent field ${targetFieldName} was not an array:`, opciones);
                }

            });


        } catch (error) {
            console.error('Error fetching dependent batched options:', error);
             // Remover estado de carga de los selects dependientes y aplicar un error visual
             dependentSelectsInRow.forEach(select => {
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
        staticSelect.value = '';
    });

    // 3. Limpiar inputs/textareas
    rowElement.querySelectorAll('input.campo-formulario, textarea.campo-formulario').forEach(input => {
        input.value = '';
    });

    // 4. Llama a la API para selects dependientes
    fetchDependentOptionsAndUpdateRow(rowElement);

    // 5. Listeners específicos
    const moduloSelect = rowElement.querySelector('select.campo-formulario[data-campo-nombre="MODULO"]');
    if (moduloSelect) {
        moduloSelect.addEventListener('change', function() {
            asignarvalortemporada(this);
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

    // 6. Inicializar validación Print/Multicolor
    inicializarValidacionPrintMulticolor(rowElement);

    // 7. Otros listeners
    inicializarListenersComposicion(rowElement);
    actualizarTotalComposicion(rowElement);
    inicializarListenersForro(rowElement);
    actualizarTotalForro(rowElement); 
    inicializarListenersRelleno(rowElement);
    actualizarTotalRelleno(rowElement);
    agregarListenerCategoriaTallas(rowElement);
}

    // Inicializa los campos de todas las filas existentes al cargar la página
    skuTableBody.querySelectorAll('.fila-carga').forEach(row => {
        initializeRowFields(row); 
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

document.addEventListener('DOMContentLoaded', function() {
    const guardarBtn = document.getElementById('guardarBtn');
    if (guardarBtn) {
        guardarBtn.addEventListener('click', function(e) {
            // Validar Print/Multicolor en todas las filas
            if (!validarTodasLasFilasPrintMulticolor()) {
                e.preventDefault();
                alert("Corrige los errores de Print antes de guardar. Solo Multicolor (999) puede tener Print.");
                return;
            }
            
            // Resto de la lógica de validación y guardado...
        });
    }
});
/**
 * Valida que los totales de composición, forro y relleno sean exactamente 100,
 * pero para FORRO y RELLENO solo si el select respectivo dice "SI TIENE".
 * Si no tiene, el total debe ser 0.
 * @param {HTMLElement} rowElement
 * @returns {Object} - { esValida: boolean, errores: array }
 */
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

// Integrar la validación al inicializar filas y al guardar
document.addEventListener('DOMContentLoaded', () => {
    const skuTableBody = document.querySelector('#skuTable tbody');
    skuTableBody.querySelectorAll('.fila-carga').forEach(row => {
        agregarValidacionTotales(row);
    });

    const guardarButton = document.querySelector('#guardarBtn');
    guardarButton.addEventListener('click', (e) => {
        let filasInvalidas = [];
        skuTableBody.querySelectorAll('.fila-carga').forEach((row, idx) => {
            const validacionTotales = validarTotalesFila(row);
            if (!validacionTotales.esValida) {
                filasInvalidas.push({ fila: idx + 1, campos: validacionTotales.errores });
            }
        });

        if (filasInvalidas.length > 0) {
            let mensaje = "Revisa los siguientes totales:\n";
            filasInvalidas.forEach(fila => {
                mensaje += `Fila ${fila.fila}: ${fila.campos.join(', ')}\n`;
            });
            alert(mensaje);
            e.preventDefault();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Selecciona el contenedor de la tabla
    const tableContainer = document.querySelector('.table-container');
    if (!tableContainer) return;

    // Crea el contenedor del indicador
    let progressBarContainer = document.createElement('div');
    progressBarContainer.className = 'table-scroll-progress-container';

    // Crea la barra de progreso
    let progressBar = document.createElement('div');
    progressBar.className = 'table-scroll-progress-bar';

    progressBarContainer.appendChild(progressBar);

    // Inserta el indicador después de la tabla
    tableContainer.parentNode.insertBefore(progressBarContainer, tableContainer.nextSibling);

    // Función para actualizar el ancho del indicador
    function updateProgressBar() {
        const scrollLeft = tableContainer.scrollLeft;
        const scrollWidth = tableContainer.scrollWidth;
        const clientWidth = tableContainer.clientWidth;
        const percent = scrollWidth > clientWidth
            ? (scrollLeft / (scrollWidth - clientWidth)) * 100
            : 0;
        progressBar.style.width = percent + '%';
    }

    // Actualiza al hacer scroll
    tableContainer.addEventListener('scroll', updateProgressBar);

    // Inicializa
    updateProgressBar();

    // Si la tabla cambia de tamaño, actualiza el indicador
    window.addEventListener('resize', updateProgressBar);
});

document.addEventListener('DOMContentLoaded', function () {
    const limpiarBtn = document.getElementById('limpiarTablaBtn');
    const skuTableBody = document.querySelector('#skuTable tbody');

    limpiarBtn.addEventListener('click', function () {
        if (!confirm('¿Estás seguro de que deseas limpiar toda la tabla? Esta acción no se puede deshacer.')) {
            return;
        }

        // Elimina todas las filas excepto la primera
        const filas = skuTableBody.querySelectorAll('.fila-carga');
        filas.forEach((fila, idx) => {
            if (idx > 0) fila.remove();
        });

        // Limpia todos los campos de la primera fila
        const primeraFila = skuTableBody.querySelector('.fila-carga');
        if (primeraFila) {
            // Limpia inputs y textareas
            primeraFila.querySelectorAll('input, textarea').forEach(input => {
                input.value = '';
            });
            // Limpia selects (deja en blanco o en la opción por defecto)
            primeraFila.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            // Limpia celdas de texto (TDs que no son inputs/selects)
            primeraFila.querySelectorAll('td.campo-formulario').forEach(td => {
                // Si es usuario o fecha, los reinicializa
                if (td.dataset.campoNombre === 'usuario') {
                    td.textContent = typeof CURRENT_USER !== 'undefined' ? CURRENT_USER : '-';
                } else if (td.dataset.campoNombre === 'fecha_creacion') {
                    td.textContent = getCurrentDateTimeString();
                } else if (!td.querySelector('input') && !td.querySelector('select') && !td.querySelector('textarea')) {
                    td.textContent = '-';
                }
            });

            // Vuelve a inicializar la fila (opciones, listeners, dependientes, etc.)
            if (typeof initializeRowFields === 'function') {
                initializeRowFields(primeraFila);
            }
        }
    });
});

// Función para inicializar listeners de Print/Multicolor en una fila específica
function inicializarValidacionPrintMulticolor(rowElement) {
    if (!rowElement) return;
    
    const printSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="PRINT"]');
    const colorFdsSelect = rowElement.querySelector('.campo-formulario[data-campo-nombre="COLOR_FDS"]');
    
    // Función de validación específica para esta fila
    function validarPrintMulticolorFila() {
        if (!colorFdsSelect || !printSelect) return true;

        const colorFdsValue = colorFdsSelect.value.trim();
        const printValue = printSelect.value.trim();

        if (colorFdsValue !== "999" && printValue !== "") {
            alert("Solo Multicolor (999) puede tener Print. Por favor, deje el campo PRINT vacío para este color.");
            printSelect.value = "";
            printSelect.focus();
            printSelect.style.backgroundColor = "#ffe0e0";
            setTimeout(() => { printSelect.style.backgroundColor = ""; }, 1500);
            return false;
        }
        return true;
    }
    
    // Remover listeners existentes antes de agregar nuevos
    if (printSelect) {
        printSelect.removeEventListener('change', validarPrintMulticolorFila);
        printSelect.addEventListener('change', validarPrintMulticolorFila);
    }
    
    if (colorFdsSelect) {
        colorFdsSelect.removeEventListener('change', validarPrintMulticolorFila);
        colorFdsSelect.addEventListener('change', validarPrintMulticolorFila);
    }
}
// Función para validar todas las filas antes de guardar
function validarTodasLasFilasPrintMulticolor() {
    const skuTableBody = document.querySelector('#skuTable tbody');
    let errorEncontrado = false;
    
    if (skuTableBody) {
        skuTableBody.querySelectorAll('.fila-carga').forEach(row => {
            const colorFdsSelect = row.querySelector('.campo-formulario[data-campo-nombre="COLOR_FDS"]');
            const printSelect = row.querySelector('.campo-formulario[data-campo-nombre="PRINT"]');
            
            if (colorFdsSelect && printSelect) {
                const colorFdsValue = colorFdsSelect.value.trim();
                const printValue = printSelect.value.trim();
                
                if (colorFdsValue !== "999" && printValue !== "") {
                    errorEncontrado = true;
                    printSelect.style.backgroundColor = "#ffe0e0";
                    setTimeout(() => { printSelect.style.backgroundColor = ""; }, 3000);
                }
            }
        });
    }
    return !errorEncontrado;
}

document.getElementById("consultar_grupos").addEventListener("click", function () {
    const modal = document.getElementById("modal_grupos");
    const grupobody = document.getElementById("tableBody");

    if (modal.hasAttribute("hidden")) {
        modal.removeAttribute("hidden");
    } else {
        modal.setAttribute("hidden", "");
        return; 
    }

    fetch("../backend/obtener_grupos.php")
        .then(response => response.json())
        .then(grupos => {
            crearTabla(grupos);
        })
        .catch(error => console.error("Error cargando catálogo:", error));

    function crearTabla(grupos) {
        grupobody.innerHTML = ""; 

        grupos.forEach((group) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${group.SAP}</td>                       
                <td>${group.INSTRUCCION_DE_LAVADO_1}</td>       
                <td>${group.INSTRUCCION_DE_LAVADO_2}</td>       
                <td>${group.INSTRUCCION_DE_LAVADO_3}</td>       
                <td>${group.INSTRUCCION_DE_LAVADO_4}</td>       
                <td>${group.INSTRUCCION_DE_LAVADO_5}</td>       
                <td>${group.INSTRUCCION_DE_BLANQUEADO_1}</td>   
                <td>${group.INSTRUCCION_DE_BLANQUEADO_2}</td>   
                <td>${group.INSTRUCCION_DE_BLANQUEADO_3}</td>   
                <td>${group.INSTRUCCION_DE_BLANQUEADO_4}</td>   
                <td>${group.INSTRUCCION_DE_BLANQUEADO_5}</td>   
                <td>${group.INSTRUCCION_DE_SECADO_1}</td>       
                <td>${group.INSTRUCCION_DE_SECADO_2}</td>       
                <td>${group.INSTRUCCION_DE_SECADO_3}</td>       
                <td>${group.INSTRUCCION_DE_SECADO_4}</td>       
                <td>${group.INSTRUCCION_DE_SECADO_5}</td>       
                <td>${group.INSTRUCCION_DE_PLANCHADO_1}</td>    
                <td>${group.INSTRUCCION_DE_PLANCHADO_2}</td>    
                <td>${group.INSTRUCCION_DE_PLANCHADO_3}</td>    
                <td>${group.INSTRUCCION_DE_PLANCHADO_4}</td>    
                <td>${group.INSTRUCCION_DE_PLANCHADO_5}</td>    
                <td>${group.INSTRUCC_CUIDADO_TEXTIL_PROF_1}</td>
                <td>${group.INSTRUCC_CUIDADO_TEXTIL_PROF_2}</td>
                <td>${group.INSTRUCC_CUIDADO_TEXTIL_PROF_3}</td>
                <td>${group.INSTRUCC_CUIDADO_TEXTIL_PROF_4}</td>
                <td>${group.INSTRUCC_CUIDADO_TEXTIL_PROF_5}</td>
            `;
            grupobody.appendChild(row);
        });
    }
});

const modal = document.querySelector(".modal");
const botonAbrir = document.querySelector(".btn-repro-modal");
const botonCerrar = document.querySelector(".close")
const nav = document.getElementById("top-scrollbar-container");
const tableRow = document.querySelector(".table-header");

function abrirModalRepros() {
    modal.classList.add('mostrar');
    document.body.style.overflow = 'hidden';
    nav.style.position = 'static';
    tableRow.style.position = 'sticky';
    cargarMaterialesEnSelect();
}

function cerrarModalRepros() {
    modal.classList.remove('mostrar');
    document.body.style.overflow = 'auto';
    nav.style.position = 'sticky';
    tableRow.style.position = '';
}

botonAbrir.addEventListener('click', abrirModalRepros);
botonCerrar.addEventListener('click', cerrarModalRepros);

/**
 * Carga la lista de materiales existentes en el select del modal de Re-Pro.
 */
async function cargarMaterialesEnSelect() {
    const reproSelect = document.querySelector('.reproSelect');
    if (!reproSelect) return;

    try {
        const response = await fetch('../api/get_materiales_repro.php');
        if (!response.ok) {
            throw new Error('Error al cargar los materiales para Re-Pro.');
        }
        const materiales = await response.json();

        reproSelect.innerHTML = '<option value="">Seleccione un material base</option>';
        materiales.forEach(material => {
            const option = document.createElement('option');
            option.value = material.id;
            option.textContent = `${material.SAP || 'SIN_SAP'} - ${material.NOMBRE}`;
            reproSelect.appendChild(option);
        });

    } catch (error) {
        console.error(error);
        reproSelect.innerHTML = '<option value="">Error al cargar materiales</option>';
    }
}

/**
 * Rellena la primera fila de la tabla con los datos de un material base.
 * @param {object} data - Los datos del material a replicar.
 */
async function populateFirstRowWithData(data) {
    const firstRow = document.querySelector('#skuTable tbody .fila-carga');
    if (!firstRow) {
        console.error("No se encontró la primera fila para poblar.");
        return;
    }

    // Almacena los valores de los campos dependientes para establecerlos más tarde
    const dependentValues = {};
    const changedElements = [];

    // 1. PRIMER PASO: Establecer todos los campos NO dependientes y recolectar los valores de los dependientes.
    for (const key in data) {
        if (Object.hasOwnProperty.call(data, key)) {
            const value = data[key];
            const field = firstRow.querySelector(`.campo-formulario[data-campo-nombre="${key}"]`);

            if (field) {
                // Si es un select dependiente, solo guardamos su valor por ahora.
                if (field.tagName === 'SELECT' && field.dataset.campoType === 'dependent') {
                    dependentValues[key] = value;
                } else {
                    // Si no es dependiente, establecemos su valor inmediatamente.
                    if (field.tagName === 'INPUT' || field.tagName === 'SELECT' || field.tagName === 'TEXTAREA') {
                        field.value = value;
                    } else if (field.tagName === 'TD') {
                        field.textContent = value;
                    }
                    changedElements.push(field);
                }
            }
        }
    }

    // 2. Resetear campos que deben ser únicos para el nuevo material.
    const sapField = firstRow.querySelector('.campo-formulario[data-campo-nombre="SAP"]');
    if (sapField) sapField.value = '';

    const userField = firstRow.querySelector('.campo-formulario[data-campo-nombre="usuario"]');
    if (userField) userField.textContent = CURRENT_USER;

    const dateField = firstRow.querySelector('.campo-formulario[data-campo-nombre="fecha_creacion"]');
    if (dateField) dateField.textContent = getCurrentDateTimeString();

    // 3. Disparar eventos en los campos "padre" para que la lógica de dependencia se inicie.
    changedElements.forEach(element => {
        element.dispatchEvent(new Event('change', { bubbles: true }));
        element.dispatchEvent(new Event('input', { bubbles: true }));
    });

    // 4. ESPERAR a que los selects dependientes se llenen con sus nuevas opciones.
    await fetchDependentOptionsAndUpdateRow(firstRow);

    // 5. SEGUNDO PASO: Ahora que los selects dependientes están poblados, establecer sus valores.
    for (const key in dependentValues) {
        const value = dependentValues[key];
        const field = firstRow.querySelector(`.campo-formulario[data-campo-nombre="${key}"]`);
        if (field && [...field.options].some(opt => opt.value === value)) {
            field.value = value;
            field.dispatchEvent(new Event('change', { bubbles: true })); // Disparar por si este es padre de otro
        }
    }

    // 6. Finalmente, actualizar todos los totales calculados.
    actualizarTotalComposicion(firstRow);
    actualizarTotalForro(firstRow);
    actualizarTotalRelleno(firstRow);

    alert('Fila poblada con la información del material base. Por favor, realice los ajustes necesarios.');
}

document.getElementById('aceptarReproBtn').addEventListener('click', async () => {
    const reproSelect = document.querySelector('.reproSelect');
    const selectedId = reproSelect.value;

    if (!selectedId) {
        alert('Por favor, seleccione un material base.');
        return;
    }

    try {
        const response = await fetch(`../api/get_carga_by_id.php?id=${selectedId}`);
        if (!response.ok) throw new Error('No se pudo obtener la información del material.');
        
        const result = await response.json();
        if (result.success && result.carga) {
            await populateFirstRowWithData(result.carga); // Añadimos await aquí
            cerrarModalRepros();
        } else {
            throw new Error(result.message || 'La respuesta del servidor no fue exitosa.');
        }
    } catch (error) {
        console.error('Error al procesar el Re-Pro:', error);
        alert(error.message);
    }
});

const modalColores = document.querySelector(".modal-colores");
const botonAbrirColores = document.querySelector(".btn-abrir-colores");
const botonCerrarColores = document.querySelector(".close-colores");

function abrirModalColores() {
    modalColores.classList.add('mostrar');
    document.body.style.overflow = 'hidden';
    nav.style.position = 'static';
    tableRow.style.position = 'sticky';
    llenarTableBodyColores();
}

function cerrarModalColores() {
    modalColores.classList.remove('mostrar');
    document.body.style.overflow = 'auto';
    nav.style.position = 'sticky';
    tableRow.style.position = '';
}

botonAbrirColores.addEventListener('click', abrirModalColores);
botonCerrarColores.addEventListener('click', cerrarModalColores);

function llenarTableBodyColores() {
    const tbody = document.getElementById('tableBodyColores');
    if (!tbody) return;

    tbody.innerHTML = ''; 

    if (!Array.isArray(COLORES_FDS) || COLORES_FDS.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3">No hay colores disponibles.</td></tr>';
        return;
    }

    COLORES_FDS.forEach(color => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${color.codigo}</td>
            <td>${color.nombre}</td>
            <td>${color.gama}</td>
        `;
        tbody.appendChild(tr);
    });
};

</script>
</body>
</html>