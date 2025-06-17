
<?php
// Establece el encabezado de la respuesta como JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once '../conexion.php'; 

// Verifica que la petición sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['error' => 'Método no permitido. Solo se permite POST.']);
    exit;
}

// Obtiene y decodifica el cuerpo JSON de la petición
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Valida la estructura de los datos recibidos
if (
    !isset($data['campos_destino']) || !is_array($data['campos_destino']) ||
    !isset($data['form_values']) || !is_array($data['form_values'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos de entrada inválidos. Se requiere campos_destino (array) y form_values (array).']);
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
    exit;
}

// Asigna los datos recibidos a variables locales
$camposDestino = $data['campos_destino'];
$formValues = $data['form_values'];

/**
 * Función recursiva para evaluar la lógica de condiciones.
 * Soporta operadores AND, OR y condiciones simples.
 * @param array $conditionStructure Estructura de la condición a evaluar
 * @param array $formValues Valores actuales del formulario
 * @return bool Resultado de la evaluación
 */
function evaluateConditionLogic($conditionStructure, $formValues) {
    if (!is_array($conditionStructure)) {
        return false;
    }

    // Si la estructura tiene un operador lógico
    if (isset($conditionStructure['operator'])) {
        $operator = $conditionStructure['operator'];
        $conditions = $conditionStructure['conditions'] ?? []; 

        // Validación de la estructura de condiciones
        if (!is_array($conditions)) {
            error_log("Error: 'conditions' no es un array en estructura con operador: " . json_encode($conditionStructure));
            return false;
        }

        // Operador AND: todas las sub-condiciones deben cumplirse
        if ($operator === 'AND') {
            foreach ($conditions as $subCondition) {
                if (!evaluateConditionLogic($subCondition, $formValues)) {
                    return false; 
                }
            }
            return true;

        // Operador OR: al menos una sub-condición debe cumplirse
        } elseif ($operator === 'OR') {
            foreach ($conditions as $subCondition) {
                if (evaluateConditionLogic($subCondition, $formValues)) {
                    return true; 
                }
            }
            return false;

        // Condición simple: compara el valor de un campo con el valor requerido
        } elseif ($operator === 'condition' && isset($conditionStructure['field']) && isset($conditionStructure['value'])) {
            $field = $conditionStructure['field'];
            $requiredValue = $conditionStructure['value'];

            $actualValue = array_key_exists($field, $formValues) ? $formValues[$field] : null;

            // Limpia espacios en blanco si son strings
            if (is_string($actualValue)) $actualValue = trim($actualValue);
            if (is_string($requiredValue)) $requiredValue = trim($requiredValue);

            $isMatch = ($actualValue !== null && $actualValue !== '' && $actualValue === $requiredValue);

            return $isMatch; 

        // Operador desconocido o estructura inválida
        } else {
            error_log("Error: Operador desconocido o estructura 'condition' inválida: " . json_encode($conditionStructure));
            return false;
        }
    }

    // Estructura de condición inválida (sin 'operator')
    error_log("Error: Estructura de condición sin 'operator' pasada a evaluateConditionLogic: " . json_encode($conditionStructure));
    return false;
}

// Array para almacenar los resultados de cada campo destino
$resultadosBatched = [];

// Procesa cada campo destino solicitado
foreach ($camposDestino as $campoDestino) {
    $campoDestino = trim($campoDestino);
    if (empty($campoDestino) || !is_string($campoDestino)) {
        continue;
    }

    $todosValoresPermitidosParaCampo = []; // Acumula todos los valores permitidos para este campo
    $defaultValuesForField = []; // Acumula valores por defecto si ninguna regla coincide

    // Consulta todas las reglas activas para este campo destino
    $stmt = $conn->prepare("SELECT condiciones, valores_permitidos FROM services_cargamasiva.reglas_dependencia WHERE campo_destino = ? AND es_activa = 1");
    if ($stmt === false) {
        error_log("Error al preparar consulta para {$campoDestino}: " . $conn->error);
        $resultadosBatched[$campoDestino] = ['error' => 'Error interno al consultar reglas (prepare)'];
        continue;
    }

    $stmt->bind_param("s", $campoDestino);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        error_log("Error al ejecutar consulta para {$campoDestino}: " . $stmt->error);
        $resultadosBatched[$campoDestino] = ['error' => 'Error interno al consultar reglas (execute)'];
        $stmt->close();
        continue;
    }

    // Bandera para saber si alguna regla coincidió
    $ruleFoundAndMatched = false; 

    // Procesa cada regla recuperada para este campo destino
    while ($regla = $result->fetch_assoc()) {
        $condicionesJson = $regla['condiciones'];
        $valoresPermitidosJsonBd = $regla['valores_permitidos'];

        // Decodifica las condiciones y los valores permitidos desde JSON
        $condiciones = json_decode($condicionesJson, true);
        $valoresPermitidosReglaBd = json_decode($valoresPermitidosJsonBd, true); 

        // Valida la estructura de las condiciones
        if (!is_array($condiciones)) {
            error_log("Error decodificando JSON 'condiciones' para campo {$campoDestino}. JSON: " . $condicionesJson);
            continue;
        }
        // Permite que valores_permitidos_bd sea null/vacío para reglas branched
        if (!is_array($valoresPermitidosReglaBd) && $valoresPermitidosReglaBd !== null && $valoresPermitidosReglaBd !== '') {
            error_log("Error decodificando JSON 'valores_permitidos' para campo {$campoDestino}. JSON: " . $valoresPermitidosJsonBd);
            $valoresPermitidosReglaBd = null; 
        }

        // --- Determina el tipo de regla y evalúa/extrae valores ---

        // 1. Regla Avanzada (branched_conditions)
        if (isset($condiciones['type']) && $condiciones['type'] === 'branched_conditions' && isset($condiciones['branches'])) {
            $branchMatched = false; 

            // Valida que branches sea un array
            if (!is_array($condiciones['branches'])) {
                error_log("Formato inválido: 'branches' no es un array en regla branched para {$campoDestino}. JSON: " . $condicionesJson);
                continue;
            }

            // Evalúa cada rama de la regla branched
            foreach ($condiciones['branches'] as $branch) {
                if (isset($branch['if']) && isset($branch['then_allow'])) {
                    // Si la condición de la rama se cumple, toma los valores de 'then_allow'
                    if (evaluateConditionLogic($branch['if'], $formValues)) {
                        if (is_array($branch['then_allow'])) {
                            $todosValoresPermitidosParaCampo = array_merge($todosValoresPermitidosParaCampo, $branch['then_allow']);
                            $branchMatched = true;
                            $ruleFoundAndMatched = true; 
                        } else {
                            error_log("Formato inválido: 'then_allow' no es un array en rama coincidente para {$campoDestino}. JSON: " . $condicionesJson);
                        }
                    }
                } else {
                    error_log("Formato inválido: Rama sin 'if' o 'then_allow' para {$campoDestino}. JSON: " . json_encode($branch));
                }
            } 

            // Si ninguna rama coincidió, recolecta los valores por defecto (default_allow)
            if (!$branchMatched) {
                if (isset($condiciones['default_allow']) && is_array($condiciones['default_allow'])) {
                    $defaultValuesForField = array_merge($defaultValuesForField, $condiciones['default_allow']);
                } else {
                    error_log("Regla branched para {$campoDestino} no tuvo rama coincidente y 'default_allow' inválido. JSON: " . $condicionesJson);
                }
            }

        // 2. Regla Sencilla (con "operator" como AND/OR)
        } elseif (isset($condiciones['operator'])) {
            // Si la condición se cumple, toma los valores permitidos de la BD
            if (evaluateConditionLogic($condiciones, $formValues)) {
                if (is_array($valoresPermitidosReglaBd)) {
                    $todosValoresPermitidosParaCampo = array_merge($todosValoresPermitidosParaCampo, $valoresPermitidosReglaBd);
                    $ruleFoundAndMatched = true; 
                } else {
                    error_log("Regla sencilla para {$campoDestino} coincidió, pero 'valores_permitidos' de la BD no es un array. JSON BD: " . $valoresPermitidosJsonBd);
                }
            }

        // Estructura desconocida de regla
        } else {
            error_log("Regla para {$campoDestino} tiene una estructura JSON desconocida. JSON: " . $condicionesJson);
        }

    }

    $stmt->close();

    // --- Lógica para decidir los valores finales ---
    // Si al menos una regla coincidió, se usan esos valores.
    // Si ninguna regla coincidió, se usan los valores por defecto recolectados.
    // Si no hay valores, el resultado será un array vacío.
    if (!$ruleFoundAndMatched && !empty($defaultValuesForField)) {
        $todosValoresPermitidosParaCampo = $defaultValuesForField; 
    }

    // Elimina duplicados y reindexa el array de valores permitidos
    $resultadosBatched[$campoDestino] = array_values(array_unique($todosValoresPermitidosParaCampo));
}

// Cierra la conexión a la base de datos
$conn->close();

// Devuelve el resultado final en formato JSON
echo json_encode($resultadosBatched);

?>
