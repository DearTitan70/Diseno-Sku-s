
<?php
// ===============================================
// Establece el tipo de contenido de la respuesta
// ===============================================
header('Content-Type: application/json; charset=utf-8');

// ===============================================
// Incluye el archivo de conexión a la base de datos
// ===============================================
require_once '../conexion.php'; 

// ===============================================
// Verifica que la petición sea de tipo POST
// ===============================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Se requiere POST.']);
    exit;
}

// ===============================================
// Obtiene y decodifica el JSON recibido en el cuerpo de la petición
// ===============================================
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

// ===============================================
// Verifica si hubo error al decodificar el JSON
// ===============================================
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Error en el formato JSON recibido: ' . json_last_error_msg()]);
    exit;
}

// ===============================================
// Inicializa un array para almacenar errores de validación
// ===============================================
$errors = [];

// ===============================================
// Validación del campo 'campo_destino'
// ===============================================
$campo_destino = isset($input['campo_destino']) ? trim($input['campo_destino']) : '';
if (empty($campo_destino)) {
    $errors[] = 'El campo "Campo a Restringir (Destino)" es obligatorio.';
}

// ===============================================
// Validación del campo 'valores_permitidos'
// Espera un array de strings
// ===============================================
$valores_permitidos = isset($input['valores_permitidos']) && is_array($input['valores_permitidos']) ? $input['valores_permitidos'] : [];
if (empty($valores_permitidos)) {
    // Si es una regla ramificada, no es obligatorio tener valores_permitidos
    if (!isset($input['condiciones']['type']) || $input['condiciones']['type'] !== 'branched_conditions') {
        $errors[] = 'Debe proporcionar al menos un "Valor Permitido".';
    }
} else {
    foreach ($valores_permitidos as $key => $valor) {
        if (!is_string($valor) || trim($valor) === '') {
            $errors[] = 'El valor permitido no puede estar vacío.';
        }
        $valores_permitidos[$key] = trim($valor); // Limpiar espacios
    }
}

// ===============================================
// Validación del campo 'condiciones'
// Puede ser un array (formato antiguo) o un objeto (formato nuevo)
// ===============================================
$condiciones = isset($input['condiciones']) ? $input['condiciones'] : []; // Permitir array vacío por defecto

// Logs para depuración (puedes comentar estas líneas en producción)
error_log("Input recibido: " . print_r($input, true));
error_log("Condiciones recibidas: " . print_r($condiciones, true));

// ===============================================
// Función recursiva para validar la estructura de condiciones complejas
// ===============================================
function validateComplexConditionsStructure($structure, &$errors, $is_root = true) {
    if ($is_root) {
        if (is_array($structure)) {
            if (empty($structure)) {
                return true;
            }

            if (isset($structure['operator']) && isset($structure['conditions']) && is_array($structure['conditions'])) {
                // Validar condiciones normales (AND/OR)
                // Continuar con la validación normal
            } elseif (isset($structure['type']) && $structure['type'] === 'branched_conditions') {
                // Validar condiciones ramificadas
                if (!isset($structure['branches']) || !is_array($structure['branches'])) {
                    $errors[] = 'Las condiciones ramificadas deben incluir un array "branches".';
                    return false;
                }
                foreach ($structure['branches'] as $branch) {
                    if (!isset($branch['if']) || !isset($branch['then_allow'])) {
                        $errors[] = 'Cada rama debe incluir "if" y "then_allow".';
                        return false;
                    }
                    // Validar las condiciones "if" de la rama
                    if (!validateComplexConditionsStructure($branch['if'], $errors, false)) {
                        return false;
                    }
                    // Validar los valores permitidos "then_allow"
                    if (!is_array($branch['then_allow']) || empty($branch['then_allow'])) {
                        $errors[] = 'Cada rama debe incluir al menos un valor permitido en "then_allow".';
                        return false;
                    }
                }
                return true;
            } else {
                // Validación para formato antiguo de condiciones simples
                foreach ($structure as $key => $condicion) {
                    if (!is_array($condicion) || !isset($condicion['campo']) || !isset($condicion['valor'])) {
                        $errors[] = "Condicion simple tiene formato incorrecto (falta 'campo' o 'valor').";
                        return false;
                    }
                }
                return true;
            }
        } else {
            $errors[] = 'La estructura de condiciones principal debe ser un objeto {operator: ..., condiciones: [...]} o un array [].';
            return false;
        }
    }

    // Validación de condiciones anidadas (no raíz)
    if (!is_array($structure) || !isset($structure['operator'])) {
        if ($is_root) {
            $errors[] = 'La estructura de condiciones principal no es un objeto/array válido con operador.';
        } else {
            $errors[] = 'Elemento de condición/grupo anidado con formato incorrecto (falta operador).';
        }
        return false;
    }

    // Validar el operador ('AND', 'OR', 'condition')
    $operator = $structure['operator'];

    if ($operator === 'condition') {
        // Validar condición simple (debe tener 'field' y 'value')
        if (!isset($structure['field']) || !isset($structure['value'])) {
            $errors[] = "Objeto de condición simple le falta 'field' o 'value'.";
            return false;
        }
        if (trim($structure['field']) === '') {
            $errors[] = "El nombre del campo en una condición simple no puede estar vacío.";
            return false;
        }
    } else if ($operator === 'AND' || $operator === 'OR') {
        // Validar grupo de condiciones (debe tener array 'conditions')
        if (!isset($structure['conditions']) || !is_array($structure['conditions'])) {
            $errors[] = "Grupo con operador '{$operator}' tiene formato incorrecto (falta array 'condiciones').";
            return false;
        }
        $conditions_list = $structure['conditions'];
        foreach ($conditions_list as $key => $sub_structure) {
            // Validación recursiva de condiciones anidadas
            if (!validateComplexConditionsStructure($sub_structure, $errors, false)) {
                return false;
            }
        }
    } else {
        // Operador inválido
        $errors[] = "Operador de condición/grupo inválido: '{$operator}'. Debe ser 'AND', 'OR' o 'condition'.";
        return false;
    }

    return true;
}

// ===============================================
// Llama a la función de validación para la estructura de condiciones recibida
// ===============================================
if (!validateComplexConditionsStructure($condiciones, $errors, true)) {
    // Si hay errores, se agregarán al array $errors
}

// ===============================================
// Validación del campo 'es_activa' (activo/inactivo)
// ===============================================
$es_activa = isset($input['es_activa']) && $input['es_activa'] == 1 ? 1 : 0;

// ===============================================
// Validación del campo 'nombre_regla' (opcional)
// ===============================================
$nombre_regla = isset($input['nombre_regla']) ? trim($input['nombre_regla']) : null;
if ($nombre_regla === '') $nombre_regla = null; // Guardar como NULL si está vacío

// ===============================================
// Validación del campo 'rule_id' para saber si es UPDATE o INSERT
// ===============================================
$rule_id = isset($input['rule_id']) && is_numeric($input['rule_id']) && $input['rule_id'] > 0 ? (int)$input['rule_id'] : null;

// ===============================================
// Si hay errores de validación, responder y salir
// ===============================================
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode("\n", $errors)]);
    exit;
}

// ===============================================
// Prepara los datos para almacenar en la base de datos
// ===============================================
$condiciones_json = json_encode($condiciones);
$valores_permitidos_json = json_encode($valores_permitidos);

// Verifica que la codificación a JSON fue exitosa
if ($condiciones_json === false || $valores_permitidos_json === false) {
    exit;
}

// ===============================================
// Conexión a la base de datos
// ===============================================
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log("Error de conexión a BD: " . $conn->connect_error);
    echo json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// ===============================================
// Prepara y ejecuta la consulta SQL (INSERT o UPDATE)
// ===============================================
$sql = '';
$types = '';
$params = [];
$action_message = '';

if ($rule_id) { // Actualización de regla existente
    $sql = "UPDATE reglas_dependencia SET nombre_regla = ?, campo_destino = ?, condiciones = ?, valores_permitidos = ?, es_activa = ?, fecha_modificacion = CURRENT_TIMESTAMP WHERE id = ?";
    $types = "ssssii"; // s: string, i: integer
    $params = [
        $nombre_regla,
        $campo_destino,
        $condiciones_json,
        $valores_permitidos_json,
        $es_activa,
        $rule_id
    ];
    $action_message = "actualizada";
} else { // Inserción de nueva regla
    $sql = "INSERT INTO reglas_dependencia (nombre_regla, campo_destino, condiciones, valores_permitidos, es_activa) VALUES (?, ?, ?, ?, ?)";
    $types = "ssssi";
    $params = [
        $nombre_regla,
        $campo_destino,
        $condiciones_json,
        $valores_permitidos_json,
        $es_activa
    ];
    $action_message = "creada";
}

// Prepara la consulta SQL
$stmt = $conn->prepare($sql);

// Verifica si la preparación de la consulta falló
if ($stmt === false) {
    error_log("Error al preparar SQL ({$conn->errno}): {$conn->error}. SQL: {$sql}");
    echo json_encode(['success' => false, 'message' => 'Error interno al preparar la consulta de base de datos.']);
    $conn->close();
    exit;
}

// Vincula los parámetros a la consulta preparada
if (!$stmt->bind_param($types, ...$params)) {
    error_log("Error al vincular parámetros ({$stmt->errno}): {$stmt->error}");
    echo json_encode(['success' => false, 'message' => 'Error interno al vincular parámetros de la consulta.']);
    $stmt->close();
    $conn->close();
    exit;
}

// Ejecuta la consulta
if ($stmt->execute()) {
    $affected_rows = $stmt->affected_rows;

    // Verifica si la inserción fue exitosa (para INSERT, affected_rows debe ser > 0)
    if (!$rule_id && $affected_rows <= 0) {
        error_log("Error en INSERT: 0 filas afectadas después de ejecución exitosa. Consulta: {$sql}");
        echo json_encode(['success' => false, 'message' => 'La regla se ejecutó correctamente pero no se insertó ninguna fila.']);
    } else {
        // Éxito: la regla fue creada o actualizada
        $response = ['success' => true, 'message' => "Regla {$action_message} correctamente."];
        if (!$rule_id) {
            // Si fue una inserción, devolver el nuevo ID de la regla
            $response['new_id'] = $conn->insert_id;
        }
        echo json_encode($response);
    }
} else {
    // Error al ejecutar la consulta
    error_log("Error al ejecutar SQL ({$stmt->errno}): {$stmt->error}. Consulta: {$sql}");

    // Maneja error de clave duplicada (índice UNIQUE)
    if ($conn->errno == 1062) {
        echo json_encode(['success' => false, 'message' => 'Error: Ya existe una regla con características similares (entrada duplicada).']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar los datos en la base de datos. Por favor, revisa los logs.']);
    }
}

// ===============================================
// Cierra la consulta preparada y la conexión a la base de datos
// ===============================================
$stmt->close();
$conn->close();

?>
