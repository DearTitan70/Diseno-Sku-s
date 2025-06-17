<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$user = $_SESSION['user_id'] ?? 'Invitado';
$userName = $_SESSION['nombre'] ?? 'Usuario'; 
$userApellido = $_SESSION['apellido'] ?? 'Usuario';
$nombreCompleto = $userName . " " . $userApellido;
$nombreCompleto = $userName . " " . $userApellido;
$datos = json_decode(file_get_contents("php://input"), true)['datos'];
$fecha = date("Y-m-d H:i:s");

$conexion = new mysqli("localhost", "root", "S1ST3NFDS-", "cargamasiva");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


foreach ($datos as $fila) {
    $SAP = $conexion->real_escape_string($fila[0]);
    $YEAR = $conexion->real_escape_string($fila[1]);
    $MES = $conexion->real_escape_string($fila[2]);
    $OCASION_DE_USO = $conexion->real_escape_string($fila[3]);
    $NOMBRE = $conexion->real_escape_string($fila[4]);
    $MODULO = $conexion->real_escape_string($fila[5]);
    $TEMPORADA = $conexion->real_escape_string($fila[6]);
    $CAPSULA = $conexion->real_escape_string($fila[7]);
    $CLIMA = $conexion->real_escape_string($fila[8]);
    $TIENDA = $conexion->real_escape_string($fila[9]);
    $CLASIFICACION = $conexion->real_escape_string($fila[10]);
    $CLUSTER = $conexion->real_escape_string($fila[11]);
    $PROVEEDOR = $conexion->real_escape_string($fila[12]);
    $CATEGORIAS = $conexion->real_escape_string($fila[13]);
    $SUBCATEGORIAS = $conexion->real_escape_string($fila[14]);
    $DISENO = $conexion->real_escape_string($fila[15]);
    $DESCRIPCION = $conexion->real_escape_string($fila[16]);
    $MANGA = $conexion->real_escape_string($fila[17]);
    $TIPO_MANGA = $conexion->real_escape_string($fila[18]);
    $PUNO = $conexion->real_escape_string($fila[19]);
    $CAPOTA = $conexion->real_escape_string($fila[20]);
    $ESCOTE = $conexion->real_escape_string($fila[21]);
    $LARGO = $conexion->real_escape_string($fila[22]);
    $CUELLO = $conexion->real_escape_string($fila[23]);
    $TIRO = $conexion->real_escape_string($fila[24]);
    $BOTA = $conexion->real_escape_string($fila[25]);
    $CINTURA = $conexion->real_escape_string($fila[26]);
    $SILUETA = $conexion->real_escape_string($fila[27]);
    $CIERRE = $conexion->real_escape_string($fila[28]);
    $GALGA = $conexion->real_escape_string($fila[29]);
    $TIPO_GALGA = $conexion->real_escape_string($fila[30]);
    $COLOR_FDS = $conexion->real_escape_string($fila[31]);
    $NOM_COLOR = $conexion->real_escape_string($fila[32]);
    $GAMA = $conexion->real_escape_string($fila[33]);
    $PRINT = $conexion->real_escape_string($fila[34]);
    $TALLAS = $conexion->real_escape_string($fila[35]);
    $TIPO_TEJIDO = $conexion->real_escape_string($fila[36]);
    $TIPO_DE_FIBRA = $conexion->real_escape_string($fila[37]);
    $BASE_TEXTIL = $conexion->real_escape_string($fila[38]);
    $DETALLES = $conexion->real_escape_string($fila[39]);
    $SUB_DETALLES = $conexion->real_escape_string($fila[40]);
    $GRUPO = $conexion->real_escape_string($fila[41]);
    $INSTRUCCION_DE_LAVADO_1 = $conexion->real_escape_string($fila[42]);
    $INSTRUCCION_DE_LAVADO_2 = $conexion->real_escape_string($fila[43]);
    $INSTRUCCION_DE_LAVADO_3 = $conexion->real_escape_string($fila[44]);
    $INSTRUCCION_DE_LAVADO_4 = $conexion->real_escape_string($fila[45]);
    $INSTRUCCION_DE_LAVADO_5 = $conexion->real_escape_string($fila[46]);
    $INSTRUCCION_BLANQUEADO_1 = $conexion->real_escape_string($fila[47]);
    $INSTRUCCION_BLANQUEADO_2 = $conexion->real_escape_string($fila[48]);
    $INSTRUCCION_BLANQUEADO_3 = $conexion->real_escape_string($fila[49]);
    $INSTRUCCION_BLANQUEADO_4 = $conexion->real_escape_string($fila[50]);
    $INSTRUCCION_BLANQUEADO_5 = $conexion->real_escape_string($fila[51]);
    $INSTRUCCION_SECADO_1 = $conexion->real_escape_string($fila[52]);
    $INSTRUCCION_SECADO_2 = $conexion->real_escape_string($fila[53]);
    $INSTRUCCION_SECADO_3 = $conexion->real_escape_string($fila[54]);
    $INSTRUCCION_SECADO_4 = $conexion->real_escape_string($fila[55]);
    $INSTRUCCION_SECADO_5 = $conexion->real_escape_string($fila[56]);
    $INSTRUCCION_PLANCHADO_1 = $conexion->real_escape_string($fila[57]);
    $INSTRUCCION_PLANCHADO_2 = $conexion->real_escape_string($fila[58]);
    $INSTRUCCION_PLANCHADO_3 = $conexion->real_escape_string($fila[59]);
    $INSTRUCCION_PLANCHADO_4 = $conexion->real_escape_string($fila[60]);
    $INSTRUCCION_PLANCHADO_5 = $conexion->real_escape_string($fila[61]);
    $INSTRUCC_CUIDADO_TEXTIL_PROF_1 = $conexion->real_escape_string($fila[62]);
    $INSTRUCC_CUIDADO_TEXTIL_PROF_2 = $conexion->real_escape_string($fila[63]);
    $INSTRUCC_CUIDADO_TEXTIL_PROF_3 = $conexion->real_escape_string($fila[64]);
    $INSTRUCC_CUIDADO_TEXTIL_PROF_4 = $conexion->real_escape_string($fila[65]);
    $INSTRUCC_CUIDADO_TEXTIL_PROF_5 = $conexion->real_escape_string($fila[66]);
    $COMPOSICION_1 = $conexion->real_escape_string($fila[67]);
    $PCT_COMP_1 = $conexion->real_escape_string($fila[68]);
    $COMPOSICION_2 = $conexion->real_escape_string($fila[69]);
    $PCT_COMP_2 = $conexion->real_escape_string($fila[70]);
    $COMPOSICION_3 = $conexion->real_escape_string($fila[71]);
    $PCT_COMP_3 = $conexion->real_escape_string($fila[72]);
    $COMPOSICION_4 = $conexion->real_escape_string($fila[73]);
    $PCT_COMP_4 = $conexion->real_escape_string($fila[74]);
    $TOT_COMP = $conexion->real_escape_string($fila[75]);
    $FORRO = $conexion->real_escape_string($fila[76]);
    $COMP_FORRO_1 = $conexion->real_escape_string($fila[77]);
    $PCT_FORRO_1 = $conexion->real_escape_string($fila[78]);
    $COMP_FORRO_2 = $conexion->real_escape_string($fila[79]);
    $PCT_FORRO_2 = $conexion->real_escape_string($fila[80]);
    $TOT_FORRO = $conexion->real_escape_string($fila[81]);
    $RELLENO = $conexion->real_escape_string($fila[82]);
    $COMP_RELLENO_1 = $conexion->real_escape_string($fila[83]);
    $PCT_RELLENO_1 = $conexion->real_escape_string($fila[84]);
    $COMP_RELLENO_2 = $conexion->real_escape_string($fila[85]);
    $PCT_RELLENO_2 = $conexion->real_escape_string($fila[86]);
    $TOT_RELLENO = $conexion->real_escape_string($fila[87]);
    $XX = $conexion->real_escape_string($fila[88]);
    $usuario = $conexion->real_escape_string($nombreCompleto);
    $fecha_creacion = $conexion->real_escape_string($fecha);

    $sql = "INSERT INTO catalogo_disenos (SAP,YEAR,MES,OCASION_DE_USO,NOMBRE,MODULO,TEMPORADA,CAPSULA,CLIMA,TIENDA,CLASIFICACION,CLUSTER,PROVEEDOR,CATEGORIAS,SUBCATEGORIAS,DISENO,DESCRIPCION,MANGA,TIPO_MANGA,PUNO,CAPOTA,ESCOTE,LARGO,CUELLO,TIRO,BOTA,CINTURA,SILUETA,CIERRE,GALGA,TIPO_GALGA,COLOR_FDS,NOM_COLOR,GAMA,PRINT,TALLAS,TIPO_TEJIDO,TIPO_DE_FIBRA,BASE_TEXTIL,DETALLES,SUB_DETALLES,GRUPO,INSTRUCCION_DE_LAVADO_1,INSTRUCCION_DE_LAVADO_2,INSTRUCCION_DE_LAVADO_3,INSTRUCCION_DE_LAVADO_4,INSTRUCCION_DE_LAVADO_5,INSTRUCCION_BLANQUEADO_1,INSTRUCCION_BLANQUEADO_2,INSTRUCCION_BLANQUEADO_3,INSTRUCCION_BLANQUEADO_4,INSTRUCCION_BLANQUEADO_5,INSTRUCCION_SECADO_1,INSTRUCCION_SECADO_2,INSTRUCCION_SECADO_3,INSTRUCCION_SECADO_4,INSTRUCCION_SECADO_5,INSTRUCCION_PLANCHADO_1,INSTRUCCION_PLANCHADO_2,INSTRUCCION_PLANCHADO_3,INSTRUCCION_PLANCHADO_4,INSTRUCCION_PLANCHADO_5,INSTRUCC_CUIDADO_TEXTIL_PROF_1,INSTRUCC_CUIDADO_TEXTIL_PROF_2,INSTRUCC_CUIDADO_TEXTIL_PROF_3,INSTRUCC_CUIDADO_TEXTIL_PROF_4,INSTRUCC_CUIDADO_TEXTIL_PROF_5,COMPOSICION_1,`%_COMP_1`,COMPOSICION_2,`%_COMP_2`,COMPOSICION_3,`%_COMP_3`,COMPOSICION_4,`%_COMP_4`,TOT_COMP,FORRO,COMP_FORRO_1,`%_FORRO_1`,COMP_FORRO_2,`%_FORRO_2`,TOT_FORRO,RELLENO,COMP_RELLENO_1,`%_RELLENO_1`,COMP_RELLENO_2,`%_RELLENO_2`,TOT_RELLENO,XX, usuario, fecha_creacion) VALUES ('$SAP', '$YEAR', '$MES', '$OCASION_DE_USO', '$NOMBRE', '$MODULO', '$TEMPORADA', '$CAPSULA', '$CLIMA', '$TIENDA', '$CLASIFICACION', '$CLUSTER', '$PROVEEDOR', '$CATEGORIAS', '$SUBCATEGORIAS', '$DISENO', '$DESCRIPCION', '$MANGA', '$TIPO_MANGA', '$PUNO', '$CAPOTA', '$ESCOTE', '$LARGO', '$CUELLO', '$TIRO', '$BOTA', '$CINTURA', '$SILUETA', '$CIERRE', '$GALGA', '$TIPO_GALGA', '$COLOR_FDS', '$NOM_COLOR', '$GAMA', '$PRINT', '$TALLAS', '$TIPO_TEJIDO', '$TIPO_DE_FIBRA', '$BASE_TEXTIL', '$DETALLES', '$SUB_DETALLES', '$GRUPO', '$INSTRUCCION_DE_LAVADO_1', '$INSTRUCCION_DE_LAVADO_2', '$INSTRUCCION_DE_LAVADO_3', '$INSTRUCCION_DE_LAVADO_4', '$INSTRUCCION_DE_LAVADO_5', '$INSTRUCCION_BLANQUEADO_1', '$INSTRUCCION_BLANQUEADO_2', '$INSTRUCCION_BLANQUEADO_3', '$INSTRUCCION_BLANQUEADO_4', '$INSTRUCCION_BLANQUEADO_5', '$INSTRUCCION_SECADO_1', '$INSTRUCCION_SECADO_2', '$INSTRUCCION_SECADO_3', '$INSTRUCCION_SECADO_4', '$INSTRUCCION_SECADO_5', '$INSTRUCCION_PLANCHADO_1', '$INSTRUCCION_PLANCHADO_2', '$INSTRUCCION_PLANCHADO_3', '$INSTRUCCION_PLANCHADO_4', '$INSTRUCCION_PLANCHADO_5', '$INSTRUCC_CUIDADO_TEXTIL_PROF_1', '$INSTRUCC_CUIDADO_TEXTIL_PROF_2', '$INSTRUCC_CUIDADO_TEXTIL_PROF_3', '$INSTRUCC_CUIDADO_TEXTIL_PROF_4', '$INSTRUCC_CUIDADO_TEXTIL_PROF_5', '$COMPOSICION_1', '$PCT_COMP_1', '$COMPOSICION_2', '$PCT_COMP_2', '$COMPOSICION_3', '$PCT_COMP_3', '$COMPOSICION_4', '$PCT_COMP_4', '$TOT_COMP', '$FORRO', '$COMP_FORRO_1', '$PCT_FORRO_1', '$COMP_FORRO_2', '$PCT_FORRO_2', '$TOT_FORRO', '$RELLENO', '$COMP_RELLENO_1', '$PCT_RELLENO_1', '$COMP_RELLENO_2', '$PCT_RELLENO_2', '$TOT_RELLENO', '$XX', '$usuario', '$fecha_creacion'
)";
    $conexion->query($sql);
}

echo "Datos cargados exitosamente.";
?>