<?php
header('Content-Type: application/json');
include '../conexion.php';
session_start();
$conn->set_charset('utf8mb4');

require_once __DIR__ . '/enviar_correo_cambio_carga.php'; 

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

// NUEVO: Conexión a la base de datos de usuarios para notificaciones
try {
    $dsn_users = "mysql:host=$host;dbname=services_cargamasiva";
    $pdo_users = new PDO($dsn_users, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection to users failed: ' . $e->getMessage()]);
    exit;
}

// Función para obtener destinatarios dinámicamente
function obtenerDestinatarios($pdo_users) {
    $stmt = $pdo_users->prepare("SELECT `correo_electronico` FROM users WHERE notificar = 1");
    $stmt->execute();
    $correos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $correo = $row['correo_electronico'];
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $correos[] = $correo;
        }
    }
    return $correos;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['rows']) || !is_array($input['rows'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

$rows = $input['rows']; 
$updated = 0;
$errors = [];

$fields = [
    'tipo','SAP', 'YEAR', 'MES', 'OCASION_DE_USO', 'NOMBRE', 'MODULO', 'TEMPORADA', 'CAPSULA', 'CLIMA', 'TIENDA', 'CLASIFICACION', 'CLUSTER', 'PROVEEDOR', 'CATEGORIAS', 'SUBCATEGORIAS', 'DISENO', 'DESCRIPCION', 'MANGA', 'TIPO_MANGA', 'PUNO', 'CAPOTA', 'ESCOTE', 'LARGO', 'CUELLO', 'TIRO', 'BOTA', 'CINTURA', 'SILUETA', 'CIERRE', 'GALGA', 'TIPO_GALGA', 'COLOR_FDS', 'NOM_COLOR', 'GAMA', 'PRINT', 'TALLAS', 'TIPO_TEJIDO', 'TIPO_DE_FIBRA', 'BASE_TEXTIL', 'DETALLES', 'SUB_DETALLES', 'GRUPO', 'INSTRUCCION_DE_LAVADO_1', 'INSTRUCCION_DE_LAVADO_2', 'INSTRUCCION_DE_LAVADO_3', 'INSTRUCCION_DE_LAVADO_4', 'INSTRUCCION_DE_LAVADO_5', 'INSTRUCCION_BLANQUEADO_1', 'INSTRUCCION_BLANQUEADO_2', 'INSTRUCCION_BLANQUEADO_3', 'INSTRUCCION_BLANQUEADO_4', 'INSTRUCCION_BLANQUEADO_5', 'INSTRUCCION_SECADO_1', 'INSTRUCCION_SECADO_2', 'INSTRUCCION_SECADO_3', 'INSTRUCCION_SECADO_4', 'INSTRUCCION_SECADO_5', 'INSTRUCCION_PLANCHADO_1', 'INSTRUCCION_PLANCHADO_2', 'INSTRUCCION_PLANCHADO_3', 'INSTRUCCION_PLANCHADO_4', 'INSTRUCCION_PLANCHADO_5', 'INSTRUCC_CUIDADO_TEXTIL_PROF_1', 'INSTRUCC_CUIDADO_TEXTIL_PROF_2', 'INSTRUCC_CUIDADO_TEXTIL_PROF_3', 'INSTRUCC_CUIDADO_TEXTIL_PROF_4', 'INSTRUCC_CUIDADO_TEXTIL_PROF_5', 'COMPOSICION_1', '%_COMP_1', 'COMPOSICION_2', '%_COMP_2', 'COMPOSICION_3', '%_COMP_3', 'COMPOSICION_4', '%_COMP_4', 'TOT_COMP', 'FORRO', 'COMP_FORRO_1', '%_FORRO_1', 'COMP_FORRO_2', '%_FORRO_2', 'TOT_FORRO', 'RELLENO', 'COMP_RELLENO_1', '%_RELLENO_1', 'COMP_RELLENO_2', '%_RELLENO_2', 'TOT_RELLENO', 'precio_compra', 'costo', 'precio_venta'
];

$identifier = 'id';

foreach ($rows as $row) {
    $allowedKeys = array_merge($fields, [$identifier]);
    $row = array_intersect_key($row, array_flip($allowedKeys));

    // Buscar la clave 'id'
    $idKey = null;
    foreach (array_keys($row) as $k) {
        if (trim(mb_strtolower($k)) === 'id') {
            $idKey = $k;
            break;
        }
    }
    if ($idKey === null) {
        $errors[] = "Missing identifier ($identifier) in row. Keys received: [" . implode(', ', array_keys($row)) . "]. Row content: " . json_encode($row);
        continue;
    }
    $idValue = $row[$idKey];

    // 1. Obtener el registro actual antes de actualizar
    $stmtOld = $pdo->prepare("SELECT * FROM catalogo_disenos WHERE `$identifier` = ?");
    $stmtOld->execute([$idValue]);
    $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if (!$oldData) {
        $errors[] = "No existe el registro con id " . $idValue;
        continue;
    }

    // 2. Comparar y registrar cambios en la bitácora
    $usuarioBitacora = isset($_SESSION['nombre']) ? $_SESSION['nombre'] . ' ' . ($_SESSION['apellido'] ?? '') : ($row['usuario'] ?? 'Desconocido');
    date_default_timezone_set('America/Bogota');
    $fechaBitacora = date('Y-m-d H:i:s');

    $cambiosParaCorreo = []; // Recolecta los cambios para el correo

    // Obtener el nombre actual (nuevo) para bitácora y correo
    $nombreRegistro = isset($row['NOMBRE']) ? $row['NOMBRE'] : (isset($oldData['NOMBRE']) ? $oldData['NOMBRE'] : '');

    foreach ($fields as $field) {
        $oldValue = isset($oldData[$field]) ? $oldData[$field] : null;
        $newValue = isset($row[$field]) ? $row[$field] : null;
        // Solo registrar si hay cambio (incluye null vs "")
        if ($oldValue != $newValue) {
            $stmtBitacora = $pdo->prepare("INSERT INTO bitacora_cambios (usuario, fecha, registro_id, nombre, campo, valor_anterior, valor_nuevo, accion) VALUES (?, ?, ?, ?, ?, ?, ?, 'UPDATE')");
            $stmtBitacora->execute([
                $usuarioBitacora,
                $fechaBitacora,
                $idValue,
                $nombreRegistro,
                $field,
                $oldValue,
                $newValue
            ]);
            // Agrega a la lista de cambios para el correo
            $cambiosParaCorreo[] = [
                'campo' => $field,
                'anterior' => $oldValue,
                'nuevo' => $newValue
            ];
        }
    }

    // 3. Actualizar el registro
    $setParts = [];
    $values = [];
    foreach ($fields as $field) {
        $setParts[] = "`$field` = ?";
        $values[] = isset($row[$field]) ? $row[$field] : null;
    }
    $values[] = $idValue;

    $sql = "UPDATE catalogo_disenos SET " . implode(', ', $setParts) . " WHERE `$identifier` = ?";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        if ($stmt->rowCount() > 0) {
            $updated++;
        } else {
            // Si no hubo cambios, verifica si el registro existe
            $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM catalogo_disenos WHERE `$identifier` = ?");
            $stmtCheck->execute([$idValue]);
            if ($stmtCheck->fetchColumn() > 0) {
                $updated++;
            } else {
                $errors[] = "No existe el registro con id " . $idValue;
            }
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }

    // 4. Enviar correo si hubo cambios
    if (!empty($cambiosParaCorreo)) {
        $destinatarios = obtenerDestinatarios($pdo_users);
        if (!empty($destinatarios)) {
            // Ahora se pasa el nombre en vez del id
            enviarCorreoCambioCarga($nombreRegistro, $cambiosParaCorreo, $usuarioBitacora, $fechaBitacora, $destinatarios, $pdo);
        }
    }
}

if ($updated > 0) {
    $response = ['success' => true, 'updated' => $updated];
    if (!empty($errors)) {
        $response['partial_errors'] = $errors;
    }
    echo json_encode($response);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No rows updated', 'errors' => $errors]);
}
?>