<?php
require_once '../conexion.php';  
session_start();
header('Content-Type: application/json');

// Array de colores (debería estar en un archivo aparte para reutilizar)
$COLORES_FDS = [
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

// Leer el body JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del registro original no proporcionado']);
    exit;
}

$id = intval($input['id']);
$nuevas_tallas = isset($input['tallas']) ? $input['tallas'] : 'igual';
$nuevos_colores = isset($input['colores']) ? $input['colores'] : 'igual';
$nuevo_nombre = isset($input['nuevo_nombre']) && !empty($input['nuevo_nombre']) ? $input['nuevo_nombre'] : null;

$conn->begin_transaction();

try {
    // 1. Obtener el registro original
    $stmt = $conn->prepare("SELECT * FROM catalogo_disenos WHERE id = ?");
    if (!$stmt) throw new Exception("Error al preparar la consulta inicial: " . $conn->error);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $registro_original = $result->fetch_assoc();
    $stmt->close();

    if (!$registro_original) {
        throw new Exception('Registro original no encontrado con ID: ' . $id);
    }

    // 2. Determinar las tallas y colores a crear
    $tallas_a_crear = ($nuevas_tallas === 'igual' || !is_array($nuevas_tallas) || empty($nuevas_tallas)) ? [$registro_original['TALLAS']] : $nuevas_tallas;
    $colores_a_crear = ($nuevos_colores === 'igual' || !is_array($nuevos_colores) || empty($nuevos_colores)) ? [$registro_original['COLOR_FDS']] : $nuevos_colores;

    $variaciones_creadas = 0;

    // 3. Bucle para crear cada combinación de talla y color
    foreach ($tallas_a_crear as $talla_actual) {
        foreach ($colores_a_crear as $color_actual) {
            $registro = $registro_original; // Copia del registro base para cada iteración

            // 3.1 Modificar los campos para la nueva variación
            $registro['TALLAS'] = $talla_actual;
            $registro['COLOR_FDS'] = $color_actual;

            if ($nuevo_nombre !== null) {
                $registro['NOMBRE'] = $nuevo_nombre;
            }

            // Buscar nombre y gama del color actual
            $color_encontrado = null;
            foreach ($COLORES_FDS as $c) {
                if ($c['codigo'] === $color_actual) {
                    $color_encontrado = $c;
                    break;
                }
            }
            if ($color_encontrado) {
                $registro['NOM_COLOR'] = $color_encontrado['nombre'];
                $registro['GAMA'] = $color_encontrado['gama'];
            } else {
                // Si no se encuentra, dejar los valores originales o vacíos
                $registro['NOM_COLOR'] = '';
                $registro['GAMA'] = '';
            }

            // 3.2 Preparar el registro para la inserción
            unset($registro['id']); // El id debe ser autoincremental
            $registro['fecha_creacion'] = date('Y-m-d H:i:s'); // Nueva fecha de creación

            if (isset($_SESSION['usuario'])) {
                $registro['usuario'] = $_SESSION['usuario'];
            }

            // 3.3 Construir la consulta de inserción dinámica
            $campos = array_keys($registro);
            $placeholders = array_fill(0, count($campos), '?');
            $tipos = '';
            $valores = [];

            foreach ($campos as $campo) {
                $valor = $registro[$campo];
                if (is_int($valor)) {
                    $tipos .= 'i';
                } elseif (is_double($valor) || is_float($valor)) {
                    $tipos .= 'd';
                } else {
                    $tipos .= 's';
                }
                $valores[] = $valor;
            }

            $campos_sql = implode(',', array_map(function($c){ return "`$c`"; }, $campos));
            $sql = "INSERT INTO catalogo_disenos ($campos_sql) VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en prepare: " . $conn->error);
            }

            $bind_names = [$tipos];
            for ($i=0; $i<count($valores); $i++) {
                $bind_names[] = &$valores[$i];
            }
            call_user_func_array([$stmt, 'bind_param'], $bind_names);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $variaciones_creadas++;
            } else {
                // Si una inserción falla, lanzamos una excepción para revertir la transacción
                throw new Exception("No se pudo crear la variación para Talla: $talla_actual, Color: $color_actual. " . $stmt->error);
            }
            $stmt->close();
        }
    }

    // 4. Si todo fue bien, confirmar los cambios
    $conn->commit();
    if ($variaciones_creadas > 0) {
        echo json_encode(['success' => true, 'message' => "$variaciones_creadas variaciones creadas correctamente.", 'creados' => $variaciones_creadas]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se crearon nuevas variaciones. Puede que ya existan.']);
    }
} catch (Exception $e) {
    $conn->rollback(); // Revertir todos los cambios si algo falló
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
