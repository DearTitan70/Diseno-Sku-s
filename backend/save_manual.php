<?php
header('Content-Type: application/json');
include '../conexion.php';
session_start();

require_once __DIR__ . '/enviar_correo_nueva_carga.php';

$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['rows']) || !is_array($input['rows'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

$rows = $input['rows'];
$inserted = 0;
$errors = [];

$fields = [
    'tipo', 'LINEA', 'usuario','fecha_creacion','SAP', 'YEAR', 'MES', 'OCASION_DE_USO', 'NOMBRE', 'MODULO', 'TEMPORADA', 'CAPSULA', 'CLIMA', 'TIENDA', 'CLASIFICACION', 'CLUSTER', 'PROVEEDOR', 'CATEGORIAS', 'SUBCATEGORIAS', 'DISENO', 'DESCRIPCION', 'MANGA', 'TIPO_MANGA', 'PUNO', 'CAPOTA', 'ESCOTE', 'LARGO', 'CUELLO', 'TIRO', 'BOTA', 'CINTURA', 'SILUETA', 'CIERRE', 'GALGA', 'TIPO_GALGA', 'COLOR_FDS', 'NOM_COLOR', 'GAMA', 'PRINT', 'TALLAS', 'TIPO_TEJIDO', 'TIPO_DE_FIBRA', 'BASE_TEXTIL', 'DETALLES', 'SUB_DETALLES', 'GRUPO', 'INSTRUCCION_DE_LAVADO_1', 'INSTRUCCION_DE_LAVADO_2', 'INSTRUCCION_DE_LAVADO_3', 'INSTRUCCION_DE_LAVADO_4', 'INSTRUCCION_DE_LAVADO_5', 'INSTRUCCION_BLANQUEADO_1', 'INSTRUCCION_BLANQUEADO_2', 'INSTRUCCION_BLANQUEADO_3', 'INSTRUCCION_BLANQUEADO_4', 'INSTRUCCION_BLANQUEADO_5', 'INSTRUCCION_SECADO_1', 'INSTRUCCION_SECADO_2', 'INSTRUCCION_SECADO_3', 'INSTRUCCION_SECADO_4', 'INSTRUCCION_SECADO_5', 'INSTRUCCION_PLANCHADO_1', 'INSTRUCCION_PLANCHADO_2', 'INSTRUCCION_PLANCHADO_3', 'INSTRUCCION_PLANCHADO_4', 'INSTRUCCION_PLANCHADO_5', 'INSTRUCC_CUIDADO_TEXTIL_PROF_1', 'INSTRUCC_CUIDADO_TEXTIL_PROF_2', 'INSTRUCC_CUIDADO_TEXTIL_PROF_3', 'INSTRUCC_CUIDADO_TEXTIL_PROF_4', 'INSTRUCC_CUIDADO_TEXTIL_PROF_5', 'COMPOSICION_1', '%_COMP_1', 'COMPOSICION_2', '%_COMP_2', 'COMPOSICION_3', '%_COMP_3', 'COMPOSICION_4', '%_COMP_4', 'TOT_COMP', 'FORRO', 'COMP_FORRO_1', '%_FORRO_1', 'COMP_FORRO_2', '%_FORRO_2', 'TOT_FORRO', 'RELLENO', 'COMP_RELLENO_1', '%_RELLENO_1', 'COMP_RELLENO_2', '%_RELLENO_2', 'TOT_RELLENO', 'XX', 'precio_compra', 'costo', 'precio_venta'
];

// Cambiar de idsInsertados a nombresInsertados
$nombresInsertados = [];
foreach ($rows as $row) {
    $placeholders = [];
    $values = [];
    foreach ($fields as $field) {
        $placeholders[] = '?';
        $values[] = isset($row[$field]) ? $row[$field] : null;
    }

    $sql = "INSERT INTO catalogo_disenos (" . implode(',', array_map(fn($f) => "`$f`", $fields)) . ") VALUES (" . implode(',', $placeholders) . ")";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $inserted++;
        // Guardar el NOMBRE insertado
        if (isset($row['NOMBRE'])) {
            $nombresInsertados[] = $row['NOMBRE'];
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

// Usuario y fecha para el correo
$usuarioBitacora = isset($_SESSION['nombre']) ? $_SESSION['nombre'] . ' ' . ($_SESSION['apellido'] ?? '') : ($rows[0]['usuario'] ?? 'Desconocido');
date_default_timezone_set('America/Bogota');
$fechaBitacora = date('Y-m-d H:i:s');

// Obtener destinatarios dinámicamente de la base de datos
$destinatarios = [];
try {
    $sqlDest = "SELECT correo FROM users WHERE notificar = 1 AND correo IS NOT NULL AND correo != ''";
    $stmtDest = $pdo->query($sqlDest);
    while ($rowDest = $stmtDest->fetch(PDO::FETCH_ASSOC)) {
        $destinatarios[] = $rowDest['correo'];
    }
} catch (Exception $e) {
    $errors[] = "Error obteniendo destinatarios: " . $e->getMessage();
}

// Enviar correo solo si hubo inserciones y hay destinatarios
if ($inserted > 0 && !empty($nombresInsertados) && !empty($destinatarios)) {
    enviarCorreoNuevaCarga($nombresInsertados, $usuarioBitacora, $fechaBitacora, $destinatarios);
}

if ($inserted > 0) {
    echo json_encode(['success' => true, 'inserted' => $inserted, 'errors' => $errors]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No rows inserted', 'errors' => $errors]);
}
?>