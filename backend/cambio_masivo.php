<?php
// Recibe: nombre, nuevoCodigoColor, print (opcional)
$data = json_decode(file_get_contents('php://input'), true);
$nombre = $data['nombre'];
$nuevoCodigoColor = $data['nuevoCodigoColor'];
$print = isset($data['print']) ? $data['print'] : "";

// Mapea los colores
$colores = [
    ["codigo" => "100", "nombre" => "BLANCO", "gama" => "BLANCO"],
    ["codigo" => "101", "nombre" => "OFFWHITE", "gama" => "BLANCO"],
    ["codigo" => "102", "nombre" => "IVORY", "gama" => "BLANCO"],
    ["codigo" => "103", "nombre" => "IVORY", "gama" => "BLANCO"],
    ["codigo" => "106", "nombre" => "BLANCO", "gama" => "BLANCO"],
    ["codigo" => "109", "nombre" => "BEIGE", "gama" => "BEIGE"],
    ["codigo" => "110", "nombre" => "BEIGE", "gama" => "BEIGE"],
    ["codigo" => "121", "nombre" => "ARENA", "gama" => "BEIGE"],
    ["codigo" => "123", "nombre" => "KAKI", "gama" => "BEIGE"],
    ["codigo" => "203", "nombre" => "AMARILLO CLARO", "gama" => "AMARILLO"],
    ["codigo" => "207", "nombre" => "LIMA", "gama" => "AMARILLO"],
    ["codigo" => "209", "nombre" => "AMARILLO QUEMADO", "gama" => "AMARILLO"],
    ["codigo" => "219", "nombre" => "BRIGHT GOLD", "gama" => "AMARILLO"],
    ["codigo" => "220", "nombre" => "TIERRA", "gama" => "AMARILLO"],
    ["codigo" => "224", "nombre" => "FLUORECENTE", "gama" => "AMARILLO"],
    ["codigo" => "233", "nombre" => "CYBER LIME", "gama" => "AMARILLO"],
    ["codigo" => "237", "nombre" => "AMARILLO", "gama" => "AMARILLO"],
    ["codigo" => "258", "nombre" => "NARANJA", "gama" => "NARANJA"],
    ["codigo" => "260", "nombre" => "NARANJA CLARO", "gama" => "NARANJA"],
    ["codigo" => "263", "nombre" => "CORAL", "gama" => "NARANJA"],
    ["codigo" => "264", "nombre" => "CORAL", "gama" => "NARANJA"],
    ["codigo" => "266", "nombre" => "NARANJA", "gama" => "NARANJA"],
    ["codigo" => "277", "nombre" => "CORAL", "gama" => "NARANJA"],
    ["codigo" => "279", "nombre" => "TERRACOTA", "gama" => "NARANJA"],
    ["codigo" => "281", "nombre" => "CORAL", "gama" => "NARANJA"],
    ["codigo" => "283", "nombre" => "TERRACOTA", "gama" => "NARANJA"],
    ["codigo" => "284", "nombre" => "NARANJA", "gama" => "NARANJA"],
    ["codigo" => "300", "nombre" => "ROJO", "gama" => "ROJO"],
    ["codigo" => "301", "nombre" => "ROJO", "gama" => "ROJO"],
    ["codigo" => "313", "nombre" => "ROJO", "gama" => "ROJO"],
    ["codigo" => "315", "nombre" => "ROJO", "gama" => "ROJO"],
    ["codigo" => "319", "nombre" => "ROJO", "gama" => "ROJO"],
    ["codigo" => "322", "nombre" => "VINO", "gama" => "ROJO"],
    ["codigo" => "328", "nombre" => "VINO", "gama" => "ROJO"],
    ["codigo" => "337", "nombre" => "BURGUNDY", "gama" => "ROJO"],
    ["codigo" => "350", "nombre" => "FUCCIA", "gama" => "ROSADO"],
    ["codigo" => "354", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "356", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "357", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "361", "nombre" => "ROSA MARCHITA", "gama" => "ROSADO"],
    ["codigo" => "362", "nombre" => "ROSA MARCHITA", "gama" => "ROSADO"],
    ["codigo" => "363", "nombre" => "ROSA MARCHITA", "gama" => "ROSADO"],
    ["codigo" => "366", "nombre" => "PALO DE ROSA", "gama" => "ROSADO"],
    ["codigo" => "368", "nombre" => "BLUSH", "gama" => "ROSADO"],
    ["codigo" => "367", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "370", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "372", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "375", "nombre" => "FUCSIA", "gama" => "MAGENTA"],
    ["codigo" => "369", "nombre" => "ROSADO", "gama" => "ROSADO"],
    ["codigo" => "380", "nombre" => "MAGENTA", "gama" => "MAGENTA"],
    ["codigo" => "393", "nombre" => "ROSADO", "gama" => "MAGENTA"],
    ["codigo" => "394", "nombre" => "ROSADO", "gama" => "MAGENTA"],
    ["codigo" => "395", "nombre" => "MAUVE", "gama" => "MAGENTA"],
    ["codigo" => "401", "nombre" => "VIOLETA", "gama" => "MORADO"],
    ["codigo" => "407", "nombre" => "PURPURA", "gama" => "MORADO"],
    ["codigo" => "417", "nombre" => "LILA", "gama" => "MORADO"],
    ["codigo" => "418", "nombre" => "LILA CLARO", "gama" => "MORADO"],
    ["codigo" => "431", "nombre" => "MORADO", "gama" => "MORADO"],
    ["codigo" => "454", "nombre" => "AZUL", "gama" => "AZUL"],
    ["codigo" => "463", "nombre" => "AZUL CIELO", "gama" => "AZUL"],
    ["codigo" => "473", "nombre" => "ROYAL", "gama" => "AZUL"],
    ["codigo" => "480", "nombre" => "CLARO", "gama" => "AZUL"],
    ["codigo" => "481", "nombre" => "MEDIO OSC", "gama" => "AZUL"],
    ["codigo" => "460", "nombre" => "CLARO", "gama" => "AZUL"],
    ["codigo" => "461", "nombre" => "CLARO", "gama" => "AZUL"],
    ["codigo" => "464", "nombre" => "MEDIO", "gama" => "AZUL"],
    ["codigo" => "467", "nombre" => "AZUL", "gama" => "AZUL"],
    ["codigo" => "475", "nombre" => "HIELO", "gama" => "AZUL"],
    ["codigo" => "479", "nombre" => "NAVY", "gama" => "AZUL"],
    ["codigo" => "482", "nombre" => "AZUL", "gama" => "AZUL"],
    ["codigo" => "484", "nombre" => "TURQUEZA", "gama" => "TURQUEZA"],
    ["codigo" => "494", "nombre" => "TURQUEZA", "gama" => "TURQUEZA"],
    ["codigo" => "505", "nombre" => "TURQUEZA", "gama" => "TURQUEZA"],
    ["codigo" => "504", "nombre" => "TURQUEZA", "gama" => "TURQUEZA"],
    ["codigo" => "510", "nombre" => "TURQUEZA", "gama" => "TURQUEZA"],
    ["codigo" => "513", "nombre" => "PETROL", "gama" => "TURQUEZA"],
    ["codigo" => "515", "nombre" => "ALPINE GREEN", "gama" => "TURQUEZA"],
    ["codigo" => "556", "nombre" => "VERDE", "gama" => "VERDE"],
    ["codigo" => "565", "nombre" => "VERDE CLARO", "gama" => "VERDE"],
    ["codigo" => "567", "nombre" => "VERDE", "gama" => "VERDE"],
    ["codigo" => "570", "nombre" => "VERDE", "gama" => "VERDE"],
    ["codigo" => "575", "nombre" => "GREEN TE", "gama" => "VERDE"],
    ["codigo" => "579", "nombre" => "VERDE OSCURO", "gama" => "VERDE"],
    ["codigo" => "583", "nombre" => "JADE", "gama" => "VERDE"],
    ["codigo" => "588", "nombre" => "VERDE LIMON", "gama" => "VERDE"],
    ["codigo" => "591", "nombre" => "VERDE OSCURO", "gama" => "VERDE"],
    ["codigo" => "592", "nombre" => "VERDE CHIVE", "gama" => "VERDE"],
    ["codigo" => "596", "nombre" => "OLIVO", "gama" => "VERDE"],
    ["codigo" => "597", "nombre" => "VERDE MILITAR", "gama" => "VERDE"],
    ["codigo" => "606", "nombre" => "CAQUI", "gama" => "CAFE"],
    ["codigo" => "608", "nombre" => "CAQUI", "gama" => "CAFE"],
    ["codigo" => "611", "nombre" => "CAFÉ", "gama" => "CAFE"],
    ["codigo" => "613", "nombre" => "CAFÉ", "gama" => "CAFE"],
    ["codigo" => "622", "nombre" => "CAQUI", "gama" => "CAFE"],
    ["codigo" => "623", "nombre" => "CAQUI", "gama" => "CAFE"],
    ["codigo" => "624", "nombre" => "CHOCOLATE", "gama" => "CAFE"],
    ["codigo" => "625", "nombre" => "CAQUI", "gama" => "CAFE"],
    ["codigo" => "626", "nombre" => "TAUPE", "gama" => "CAFE"],
    ["codigo" => "627", "nombre" => "COFFE", "gama" => "CAFE"],
    ["codigo" => "700", "nombre" => "NEGRO", "gama" => "NEGRO"],
    ["codigo" => "701", "nombre" => "CAVIAR", "gama" => "NEGRO"],
    ["codigo" => "803", "nombre" => "GRIS CLARO", "gama" => "NEGRO"],
    ["codigo" => "811", "nombre" => "GRIS MEDIO", "gama" => "NEGRO"],
    ["codigo" => "815", "nombre" => "GRIS MEDIO", "gama" => "NEGRO"],
    ["codigo" => "817", "nombre" => "GRIS OSCURO", "gama" => "NEGRO"],
    ["codigo" => "819", "nombre" => "GRIS OSCURO", "gama" => "NEGRO"],
    ["codigo" => "999", "nombre" => "MULTICOLOR", "gama" => "MULTICOLOR"]
];
// Busca el color seleccionado
$color = null;
foreach ($colores as $c) {
    if ($c['codigo'] === $nuevoCodigoColor) {
        $color = $c;
        break;
    }
}
if (!$color) {
    echo json_encode(['success' => false, 'message' => 'Color no válido']);
    exit;
}

// Conexión a la base de datos
require_once '../conexion.php';

// Obtener usuario de sesión (ajusta según tu sistema)
session_start();
$usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] . ' ' . ($_SESSION['apellido'] ?? '') : ($rows[0]['usuario'] ?? 'Desconocido');

// Obtener valores anteriores para bitácora (insensible a mayúsculas y espacios)
$stmtOld = $conn->prepare("SELECT id, COLOR_FDS, NOM_COLOR, GAMA, PRINT FROM catalogo_disenos WHERE TRIM(UPPER(NOMBRE))=TRIM(UPPER(?))");
$stmtOld->bind_param("s", $nombre);
$stmtOld->execute();
$resultOld = $stmtOld->get_result();
$registrosAfectados = $resultOld->fetch_all(MYSQLI_ASSOC);

// Realizar el cambio masivo (insensible a mayúsculas y espacios)
if ($color['codigo'] === "999") {
    $stmt = $conn->prepare("UPDATE catalogo_disenos SET COLOR_FDS=?, NOM_COLOR=?, GAMA=?, PRINT=? WHERE TRIM(UPPER(NOMBRE))=TRIM(UPPER(?))");
    $stmt->bind_param("sssss", $color['codigo'], $color['nombre'], $color['gama'], $print, $nombre);
} else {
    $stmt = $conn->prepare("UPDATE catalogo_disenos SET COLOR_FDS=?, NOM_COLOR=?, GAMA=?, PRINT='' WHERE TRIM(UPPER(NOMBRE))=TRIM(UPPER(?))");
    $stmt->bind_param("ssss", $color['codigo'], $color['nombre'], $color['gama'], $nombre);
}
$ok = $stmt->execute();

// Registrar en la bitácora si hubo éxito
if ($ok && !empty($registrosAfectados)) {
    $accion = "CAMBIO_MASIVO_COLOR";
    $fecha = date('Y-m-d H:i:s');
    foreach ($registrosAfectados as $row) {
        // COLOR_FDS
        if ($row['COLOR_FDS'] !== $color['codigo']) {
            $stmtBitacora = $conn->prepare("INSERT INTO bitacora_cambios (usuario, fecha, registro_id, nombre, campo, valor_anterior, valor_nuevo, accion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $campo = "COLOR_FDS";
            $stmtBitacora->bind_param("ssisssss", $usuario, $fecha, $row['id'], $nombre, $campo, $row['COLOR_FDS'], $color['codigo'], $accion);
            $stmtBitacora->execute();
        }
        // NOM_COLOR
        if ($row['NOM_COLOR'] !== $color['nombre']) {
            $stmtBitacora = $conn->prepare("INSERT INTO bitacora_cambios (usuario, fecha, registro_id, nombre, campo, valor_anterior, valor_nuevo, accion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $campo = "NOM_COLOR";
            $stmtBitacora->bind_param("ssisssss", $usuario, $fecha, $row['id'], $nombre, $campo, $row['NOM_COLOR'], $color['nombre'], $accion);
            $stmtBitacora->execute();
        }
        // GAMA
        if ($row['GAMA'] !== $color['gama']) {
            $stmtBitacora = $conn->prepare("INSERT INTO bitacora_cambios (usuario, fecha, registro_id, nombre, campo, valor_anterior, valor_nuevo, accion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $campo = "GAMA";
            $stmtBitacora->bind_param("ssisssss", $usuario, $fecha, $row['id'], $nombre, $campo, $row['GAMA'], $color['gama'], $accion);
            $stmtBitacora->execute();
        }
        // PRINT
        $nuevoPrint = ($color['codigo'] === "999") ? $print : '';
        if ($row['PRINT'] !== $nuevoPrint) {
            $stmtBitacora = $conn->prepare("INSERT INTO bitacora_cambios (usuario, fecha, registro_id, nombre, campo, valor_anterior, valor_nuevo, accion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $campo = "PRINT";
            $stmtBitacora->bind_param("ssisssss", $usuario, $fecha, $row['id'], $nombre, $campo, $row['PRINT'], $nuevoPrint, $accion);
            $stmtBitacora->execute();
        }
    }
}

echo json_encode(['success' => $ok]);
?>