<?php
// Incluye el archivo de conexión a la base de datos
include '../conexion.php';
$conn->set_charset('utf8mb4');

// -----------------------------
// 1. Definir la consulta SQL
// -----------------------------
// Consulta SQL para obtener todos los registros del histórico de cargas
$sql = "SELECT tipo, id, SAP, YEAR, MES, OCASION_DE_USO, NOMBRE, MODULO, TEMPORADA, CAPSULA, CLIMA, TIENDA, CLASIFICACION, CLUSTER, PROVEEDOR, CATEGORIAS, SUBCATEGORIAS, DISENO, DESCRIPCION, MANGA, TIPO_MANGA, PUNO, CAPOTA, ESCOTE, LARGO, CUELLO, TIRO, BOTA, CINTURA, SILUETA, CIERRE, GALGA, TIPO_GALGA, COLOR_FDS, NOM_COLOR, GAMA, PRINT, TALLAS, TIPO_TEJIDO, TIPO_DE_FIBRA, BASE_TEXTIL, DETALLES, SUB_DETALLES, GRUPO, INSTRUCCION_DE_LAVADO_1, INSTRUCCION_DE_LAVADO_2, INSTRUCCION_DE_LAVADO_3, INSTRUCCION_DE_LAVADO_4, INSTRUCCION_DE_LAVADO_5, INSTRUCCION_BLANQUEADO_1, INSTRUCCION_BLANQUEADO_2, INSTRUCCION_BLANQUEADO_3, INSTRUCCION_BLANQUEADO_4, INSTRUCCION_BLANQUEADO_5, INSTRUCCION_SECADO_1, INSTRUCCION_SECADO_2, INSTRUCCION_SECADO_3, INSTRUCCION_SECADO_4, INSTRUCCION_SECADO_5, INSTRUCCION_PLANCHADO_1, INSTRUCCION_PLANCHADO_2, INSTRUCCION_PLANCHADO_3, INSTRUCCION_PLANCHADO_4, INSTRUCCION_PLANCHADO_5, INSTRUCC_CUIDADO_TEXTIL_PROF_1, INSTRUCC_CUIDADO_TEXTIL_PROF_2, INSTRUCC_CUIDADO_TEXTIL_PROF_3, INSTRUCC_CUIDADO_TEXTIL_PROF_4, INSTRUCC_CUIDADO_TEXTIL_PROF_5, COMPOSICION_1, `%_COMP_1`, COMPOSICION_2, `%_COMP_2`, COMPOSICION_3, `%_COMP_3`, COMPOSICION_4, `%_COMP_4`, TOT_COMP, FORRO, COMP_FORRO_1, `%_FORRO_1`, COMP_FORRO_2, `%_FORRO_2`, TOT_FORRO, RELLENO, COMP_RELLENO_1, `%_RELLENO_1`, COMP_RELLENO_2, `%_RELLENO_2`, TOT_RELLENO, XX, usuario, fecha_creacion, precio_compra, costo, precio_venta FROM cargas";

// Ejecuta la consulta SQL
$result = $conn->query($sql);

// -----------------------------
// 2. Inicializar el arreglo para resultados
// -----------------------------
// Variable para almacenar los resultados en formato de arreglo asociativo
$historico = [];

// -----------------------------
// 3. Procesar los resultados de la consulta
// -----------------------------
// Verifica si la consulta fue exitosa y si hay resultados
if ($result->num_rows > 0) {
    // Recorre cada fila del resultado y la agrega al arreglo $historico
    while ($row = $result->fetch_assoc()) {
        $historico[] = [
            // Mapea cada columna de la base de datos a un campo del arreglo
            "tipo" => $row["tipo"],
            "id" => $row["id"],
            "SAP" => $row["SAP"],
            "YEAR" => $row["YEAR"],
            "MES" => $row["MES"],
            "OCASION_DE_USO" => $row["OCASION_DE_USO"],
            "NOMBRE" => $row["NOMBRE"],
            "MODULO" => $row["MODULO"],
            "TEMPORADA" => $row["TEMPORADA"],
            "CAPSULA" => $row["CAPSULA"],
            "CLIMA" => $row["CLIMA"],
            "TIENDA" => $row["TIENDA"],
            "CLASIFICACION" => $row["CLASIFICACION"],
            "CLUSTER" => $row["CLUSTER"],
            "PROVEEDOR" => $row["PROVEEDOR"],
            "CATEGORIAS" => $row["CATEGORIAS"],
            "SUBCATEGORIAS" => $row["SUBCATEGORIAS"],
            "DISENO" => $row["DISENO"],
            "DESCRIPCION" => $row["DESCRIPCION"],
            "MANGA" => $row["MANGA"],
            "TIPO_MANGA" => $row["TIPO_MANGA"],
            "PUNO" => $row["PUNO"],
            "CAPOTA" => $row["CAPOTA"],
            "ESCOTE" => $row["ESCOTE"],
            "LARGO" => $row["LARGO"],
            "CUELLO" => $row["CUELLO"],
            "TIRO" => $row["TIRO"],
            "BOTA" => $row["BOTA"],
            "CINTURA" => $row["CINTURA"],
            "SILUETA" => $row["SILUETA"],
            "CIERRE" => $row["CIERRE"],
            "GALGA" => $row["GALGA"],
            "TIPO_GALGA" => $row["TIPO_GALGA"],
            "COLOR_FDS" => $row["COLOR_FDS"],
            "NOM_COLOR" => $row["NOM_COLOR"],
            "GAMA" => $row["GAMA"],
            "PRINT" => $row["PRINT"],
            "TALLAS" => $row["TALLAS"],
            "TIPO_TEJIDO" => $row["TIPO_TEJIDO"],
            "TIPO_DE_FIBRA" => $row["TIPO_DE_FIBRA"],
            "BASE_TEXTIL" => $row["BASE_TEXTIL"],
            "DETALLES" => $row["DETALLES"],
            "SUB_DETALLES" => $row["SUB_DETALLES"],
            "GRUPO" => $row["GRUPO"],
            "INSTRUCCION_DE_LAVADO_1" => $row["INSTRUCCION_DE_LAVADO_1"],
            "INSTRUCCION_DE_LAVADO_2" => $row["INSTRUCCION_DE_LAVADO_2"],
            "INSTRUCCION_DE_LAVADO_3" => $row["INSTRUCCION_DE_LAVADO_3"],
            "INSTRUCCION_DE_LAVADO_4" => $row["INSTRUCCION_DE_LAVADO_4"],
            "INSTRUCCION_DE_LAVADO_5" => $row["INSTRUCCION_DE_LAVADO_5"],
            "INSTRUCCION_BLANQUEADO_1" => $row["INSTRUCCION_BLANQUEADO_1"],
            "INSTRUCCION_BLANQUEADO_2" => $row["INSTRUCCION_BLANQUEADO_2"],
            "INSTRUCCION_BLANQUEADO_3" => $row["INSTRUCCION_BLANQUEADO_3"],
            "INSTRUCCION_BLANQUEADO_4" => $row["INSTRUCCION_BLANQUEADO_4"],
            "INSTRUCCION_BLANQUEADO_5" => $row["INSTRUCCION_BLANQUEADO_5"],
            "INSTRUCCION_SECADO_1" => $row["INSTRUCCION_SECADO_1"],
            "INSTRUCCION_SECADO_2" => $row["INSTRUCCION_SECADO_2"],
            "INSTRUCCION_SECADO_3" => $row["INSTRUCCION_SECADO_3"],
            "INSTRUCCION_SECADO_4" => $row["INSTRUCCION_SECADO_4"],
            "INSTRUCCION_SECADO_5" => $row["INSTRUCCION_SECADO_5"],
            "INSTRUCCION_PLANCHADO_1" => $row["INSTRUCCION_PLANCHADO_1"],
            "INSTRUCCION_PLANCHADO_2" => $row["INSTRUCCION_PLANCHADO_2"],
            "INSTRUCCION_PLANCHADO_3" => $row["INSTRUCCION_PLANCHADO_3"],
            "INSTRUCCION_PLANCHADO_4" => $row["INSTRUCCION_PLANCHADO_4"],
            "INSTRUCCION_PLANCHADO_5" => $row["INSTRUCCION_PLANCHADO_5"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_1" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_1"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_2" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_2"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_3" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_3"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_4" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_4"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_5" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_5"],
            "COMPOSICION_1" => $row["COMPOSICION_1"],
            "%_COMP_1" => $row["%_COMP_1"],
            "COMPOSICION_2" => $row["COMPOSICION_2"],
            "%_COMP_2" => $row["%_COMP_2"],
            "COMPOSICION_3" => $row["COMPOSICION_3"],
            "%_COMP_3" => $row["%_COMP_3"],
            "COMPOSICION_4" => $row["COMPOSICION_4"],
            "%_COMP_4" => $row["%_COMP_4"],
            "TOT_COMP" => $row["TOT_COMP"],
            "FORRO" => $row["FORRO"],
            "COMP_FORRO_1" => $row["COMP_FORRO_1"],
            "%_FORRO_1" => $row["%_FORRO_1"],
            "COMP_FORRO_2" => $row["COMP_FORRO_2"],
            "%_FORRO_2" => $row["%_FORRO_2"],
            "TOT_FORRO" => $row["TOT_FORRO"],
            "RELLENO" => $row["RELLENO"],
            "COMP_RELLENO_1" => $row["COMP_RELLENO_1"],
            "%_RELLENO_1" => $row["%_RELLENO_1"],
            "COMP_RELLENO_2" => $row["COMP_RELLENO_2"],
            "%_RELLENO_2" => $row["%_RELLENO_2"],
            "TOT_RELLENO" => $row["TOT_RELLENO"],
            "XX" => $row["XX"],
            "usuario" => $row["usuario"],
            "fecha_creacion" => $row["fecha_creacion"],
            "precio_compra" => $row["precio_compra"],
            "costo" => $row["costo"],
            "precio_venta" => $row["precio_venta"],
        ];
    }
}

// -----------------------------
// 4. Devolver los resultados en formato JSON
// -----------------------------
// Convierte el arreglo $historico a formato JSON y lo imprime como respuesta
echo json_encode($historico);

// -----------------------------
// 5. Cerrar la conexión a la base de datos
// -----------------------------
$conn->close();
?>
