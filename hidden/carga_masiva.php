<?php
include '.../conexion.php';
session_start();

$reglasValidacionData = [];

// Seleccionamos solo los campos necesarios para la lógica JS
$sql = "SELECT campo_destino, condiciones, valores_permitidos FROM reglas_dependencia WHERE es_activa = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $campoDestino = $row['campo_destino'];
        $condiciones = json_decode($row['condiciones'], true); // true para array asociativo
        $valoresPermitidos = json_decode($row['valores_permitidos'], true);

        if ($condiciones === null || $valoresPermitidos === null) {
            error_log("JSON inválido para la regla con campo_destino: " . $campoDestino);
            continue; 
        }

        if (!isset($reglasValidacionData[$campoDestino])) {
            $reglasValidacionData[$campoDestino] = [];
        }

        $reglasValidacionData[$campoDestino][] = [
            'condiciones' => $condiciones, 
            'valores_permitidos' => $valoresPermitidos 
        ];
    }
} else {
    echo "No se encontraron reglas de validación.";
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de CSV con Previsualización y Validación</title>
    <style>
        .tabla-container {
            overflow-x: auto;
            max-width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        table {
            border-collapse: collapse;
            white-space: nowrap; /* Evita que el texto se rompa en múltiples líneas */
            min-width: 100%;
        }
        th, td {
            padding: 6px 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            min-width: 80px; /* Ancho mínimo para cada celda */
            max-width: 200px; /* Ancho máximo de cada celda */
            overflow: hidden;
            text-overflow: ellipsis; /* Muestra ... si el contenido es muy largo */
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .controls {
            margin-bottom: 15px;
        }
        #preview {
            max-height: 70vh; /* Altura máxima del contenedor */
            overflow-y: auto; /* Scroll vertical */
        }
        .file-input {
            margin-right: 10px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        #btnCargar {
            display: none;
            margin-top: 10px;
        }
        .empty-cell {
            background-color: #f5f5f5;
            color: #aaa;
            font-style: italic;
        }
        .error-cell {
            background-color: #ffebee;
            color: #d32f2f;
        }
        .warning-msg {
            color: #ff9800;
            font-weight: bold;
        }
        .error-msg {
            color: #d32f2f;
            font-weight: bold;
        }
        .error-list {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff8e1;
            border-left: 4px solid #ff9800;
            max-height: 200px;
            overflow-y: auto;
        }
        #validacionStatus {
            margin-top: 15px;
            padding: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Carga de CSV con Previsualización y Validación</h1>
    <a href="index.php">
        <button>Volver al menu</button>
    </a>
    <br><br>
    <div class="controls">
        <input type="file" id="archivoCSV" accept=".csv" class="file-input">
        <button onclick="mostrarPreview()">Previsualizar</button>
        <button id="btnValidar" style="display:none;" onclick="validarDatos()">Validar Datos</button>
        <button id="btnCargar" style="display:none;" onclick="confirmarCarga()">Cargar Datos</button>
    </div>
    
    <div id="validacionStatus"></div>
    <div id="preview"></div>

    <script>
    let datosCSV = [];
    let encabezados = [];
    let columnasIndices = {};
    let erroresValidacion = [];

    const reglasValidacion = <?php echo json_encode($reglasValidacionData); ?>;

    console.log('Reglas de validación cargadas desde la BD:', reglasValidacion);

    // =====================================================================
    // FUNCIÓN PARA EVALUAR LA ESTRUCTURA COMPLEJA DE CONDICIONES (Recursiva)
    // =====================================================================
    function evaluateConditionStructure(conditionStructure, rowData, columnasIndices) {
        // --- Fallback para estructuras de condiciones antiguas (array simple AND) ---
        if (Array.isArray(conditionStructure)) {
            // console.log("Evaluando estructura antigua (array AND):", conditionStructure);
            for (const simpleCondition of conditionStructure) {
                // Validar el formato básico de la condición simple
                if (typeof simpleCondition !== 'object' || simpleCondition === null || !simpleCondition.hasOwnProperty('campo') || !simpleCondition.hasOwnProperty('valor')) {
                    console.warn("Formato de condición simple inválido:", simpleCondition);
                    return false; // Considerar inválido si el formato es incorrecto
                }
                const colIndex = columnasIndices[simpleCondition.campo];
                // Si la columna no existe o el valor en la fila no coincide, la condición falla
                if (colIndex === undefined || rowData[colIndex] !== simpleCondition.valor) {
                    return false;
                }
            }
            return true; // Todas las condiciones simples en el array fueron verdaderas
        }
        // --- Fin Fallback ---


        // --- Evaluación de la nueva estructura compleja (objeto con operator/conditions) ---
        if (!conditionStructure || typeof conditionStructure !== 'object') {
            console.error("Estructura de condición compleja inválida:", conditionStructure);
            return false; // Considerar inválido si no es un objeto
        }

        const operator = conditionStructure.operator;
        const conditions = conditionStructure.conditions; // Este 'conditions' es el array dentro del objeto operador


        if (operator === 'condition') {
            const field = conditionStructure.field;
            const value = conditionStructure.value;
            // Validar que una condición simple dentro de la estructura compleja tenga campo y valor
            if (field === undefined || value === undefined) {
                console.error("Objeto de condición simple con operator 'condition' le falta 'field' o 'value':", conditionStructure);
                return false;
            }
            const colIndex = columnasIndices[field];
            if (colIndex === undefined) {
                // La columna de la condición no existe en el CSV, la condición no se puede cumplir
                // console.log(`Columna '${field}' para condición no encontrada en CSV.`);
                return false;
            }
            // Evaluar la condición simple (ejemplo con igualdad)
            return rowData[colIndex] === value;
            // TODO: Implementar otros operadores si son necesarios (>, <, !=, includes, startsWith, etc.)

        }

        // Validar que si el operador es AND/OR, 'conditions' sea un array y no esté vacío
        if ((operator === 'AND' || operator === 'OR')) {
            if (!Array.isArray(conditions) || conditions.length === 0) {
                console.error(`Grupo con operador '${operator}' no tiene un array 'conditions' o está vacío:`, conditionStructure);
                return false;
            }

            if (operator === 'AND') {
                for (const subConditionStructure of conditions) {
                    if (!evaluateConditionStructure(subConditionStructure, rowData, columnasIndices)) {
                        return false; // Si alguna sub-condición en el AND es falsa, todo el AND es falso
                    }
                }
                return true; // Si todas las sub-condiciones en el AND son verdaderas, todo el AND es verdadero
            }

            if (operator === 'OR') {
                for (const subConditionStructure of conditions) {
                    if (evaluateConditionStructure(subConditionStructure, rowData, columnasIndices)) {
                        return true; // Si alguna sub-condición en el OR es verdadera, todo el OR es verdadero
                    }
                }
                return false; // Si todas las sub-condiciones en el OR son falsas, todo el OR es falso
            }
        }


        console.error("Operador desconocido o estructura inválida en:", conditionStructure);
        return false; // Tratar cualquier otra estructura o operador desconocido como falso
    }
    // =====================================================================
    // FIN FUNCIÓN evaluateConditionStructure
    // =====================================================================


    function mostrarPreview() {
        const archivo = document.getElementById('archivoCSV').files[0];
        if (!archivo) return alert("Selecciona un archivo CSV.");

        const lector = new FileReader();
        lector.onload = function(e) {
            try {
                const texto = e.target.result;
                const filas = texto.split("\n");
                if (filas.length < 2) { // Need at least header and one data row
                    alert("El archivo debe contener al menos encabezados y una fila de datos.");
                    document.getElementById("preview").innerHTML = "";
                    document.getElementById("btnValidar").style.display = "none";
                    document.getElementById("btnCargar").style.display = "none";
                    return;
                }

                datosCSV = [];

                // Usar exclusivamente punto y coma como delimitador
                const delimitador = ";";

                // Encontrar la fila con más columnas para establecer la estructura (omitimos la primera fila que podría ser un título general)
                let maxColumnas = 0;
                for(let i = 1; i < filas.length; i++) { // Start from the second row (index 1)
                     if (filas[i].trim()) {
                        const numCols = filas[i].split(delimitador).length;
                        maxColumnas = Math.max(maxColumnas, numCols);
                      }
                }
                if (maxColumnas === 0) {
                    alert("No se encontraron datos o encabezados válidos en el archivo.");
                    document.getElementById("preview").innerHTML = "";
                    document.getElementById("btnValidar").style.display = "none";
                    document.getElementById("btnCargar").style.display = "none";
                    return;
                }


                let html = "<div class='tabla-container'><table>";

                // Encabezados (Fila 2 del CSV, índice 1 del array filas)
                encabezados = filas[1].split(delimitador).map(h => h.trim());
                 // Ajustar encabezados para tener el mismo número de columnas que maxColumnas
                while(encabezados.length < maxColumnas) {
                    encabezados.push(''); // Fill with empty strings if header row is shorter
                }

                html += "<tr>";
                columnasIndices = {}; // Reset indices
                for (let i = 0; i < maxColumnas; i++) {
                    const headerText = encabezados[i] || ('Columna ' + (i+1)); // Use default if header is empty
                    html += `<th>${headerText}</th>`;
                    if (encabezados[i]) { // Only map if header is not empty
                        columnasIndices[encabezados[i]] = i;
                    }
                }
                html += "</tr>";

                // Filas de datos (empezando desde la tercera fila, índice 2 del array filas)
                for (let i = 2; i < filas.length; i++) {
                    const fila = filas[i].trim();
                    if (!fila) continue; // Saltar filas vacías

                    const celdas = fila.split(delimitador);
                    const filaDatos = [];

                    html += `<tr data-fila="${i-2}">`; // data-fila starts from 0 for data rows
                    for (let j = 0; j < maxColumnas; j++) {
                        let valor = "";

                        if (j < celdas.length) {
                            valor = celdas[j].trim();
                        }
                         filaDatos.push(valor); // Always push a value to maintain column count


                            html += `<td title="${valor}" data-col="${j}">${valor === '' ? '&nbsp;' : valor}</td>`; // Show &nbsp; for empty cells for better display
                        }
                        html += "</tr>";

                        datosCSV.push(filaDatos);
                    }

                    html += "</table></div>";
                    document.getElementById("preview").innerHTML = html;
                    document.getElementById("btnValidar").style.display = "inline-block";
                    document.getElementById("btnCargar").style.display = "none"; // Hide cargar until validated
                    document.getElementById("validacionStatus").style.display = "none"; // Hide previous status
                } catch (error) {
                    console.error("Error al procesar el archivo:", error);
                    alert("Error al procesar el archivo: " + error.message);
                    document.getElementById("preview").innerHTML = "";
                    document.getElementById("btnValidar").style.display = "none";
                    document.getElementById("btnCargar").style.display = "none";
                    document.getElementById("validacionStatus").style.display = "none";
                }
            };
            lector.readAsText(archivo);
        }

        function validarDatos() {
        erroresValidacion = [];
        let hayErrores = false;
        let totalFilas = datosCSV.length;
        let totalErrores = 0;

        const celdas = document.querySelectorAll("td");
        celdas.forEach(celda => {
            celda.classList.remove("error-cell");
        });

        datosCSV.forEach((fila, filaIndex) => {
            Object.keys(reglasValidacion).forEach(campoDestino => {
                const colDestinoIndex = columnasIndices[campoDestino];

                // Solo validar si el campo destino existe en el CSV y tiene reglas de validación
                if (colDestinoIndex !== undefined && reglasValidacion[campoDestino]) {
                    const valorCampoDestino = fila[colDestinoIndex];
                    const reglasParaCampoDestino = reglasValidacion[campoDestino];

                    // REVISED LOGIC START
                    let finalAllowedValues = new Set();
                    let anyDependencyRuleEvaluated = false; // Flag if we encountered *any* rule for this field

                    reglasParaCampoDestino.forEach(regla => {
                        anyDependencyRuleEvaluated = true; // We are processing a rule for this field

                        // Check if the rule uses the new branched logic for allowed values
                        if (regla.condiciones && typeof regla.condiciones === 'object' && regla.condiciones.type === 'branched_conditions' && Array.isArray(regla.condiciones.branches)) {
                            // Rule uses the new branched logic for allowed values
                            let anyBranchConditionMet = false;
                            if (Array.isArray(regla.condiciones.branches)) { // Double check safety
                                regla.condiciones.branches.forEach(branch => {
                                    // Verificar que la rama tenga la estructura esperada (if y then_allow)
                                    if (branch.if && branch.then_allow && Array.isArray(branch.then_allow)) {
                                        // Evaluate the 'if' condition for this branch using the helper function
                                        if (evaluateConditionStructure(branch.if, fila, columnasIndices)) {
                                            anyBranchConditionMet = true;
                                            // Add allowed values from THIS branch's 'then_allow'
                                            branch.then_allow.forEach(val => finalAllowedValues.add(val));
                                        }
                                    } else {
                                        console.warn("Malformed branch in branched_conditions for rule:", regla);
                                    }
                                });
                            }

                            // If no branch conditions were met, add default_allow values if they exist and are an array
                            if (!anyBranchConditionMet && regla.condiciones.default_allow && Array.isArray(regla.condiciones.default_allow)) {
                                regla.condiciones.default_allow.forEach(val => finalAllowedValues.add(val));
                            }

                        } else {
                            // Rule uses the older condition structure (simple array or object {operator, conditions})
                            // and defines allowed values in rule.valores_permitidos column.
                            // Check if its overall conditions are met using the evaluateConditionStructure function
                            const conditionsMet = evaluateConditionStructure(regla.condiciones, fila, columnasIndices);

                            if (conditionsMet) {
                                // If this rule's general conditions are met, add its allowed values from the DB column
                                if (regla.valores_permitidos && Array.isArray(regla.valores_permitidos)) {
                                    regla.valores_permitidos.forEach(allowedValue => {
                                        finalAllowedValues.add(allowedValue);
                                    });
                                } else if (regla.valores_permitidos && typeof regla.valores_permitidos === 'string') {
                                    // Handle single string allowed value for compatibility
                                    finalAllowedValues.add(regla.valores_permitidos);
                                } else {
                                   console.warn("Regla con condiciones cumplidas tiene valores_permitidos inválidos o vacíos:", regla);
                                }
                            }
                        }
                    });


                    // 2. Validar el valor de la celda contra el conjunto total de valores permitidos.
                    // Validar solo si encontramos *alguna* regla para este campo Y el valor de la celda NO está vacío.
                    if (anyDependencyRuleEvaluated && valorCampoDestino !== "") {
                        // Si el valor de la celda NO está en el conjunto final de valores permitidos
                        if (!finalAllowedValues.has(valorCampoDestino)) {
                            const error = {
                                fila: filaIndex,
                                columna: colDestinoIndex,
                                // Mensaje incluye la lista combinada de valores permitidos de todas las fuentes aplicables
                                mensaje: `Fila ${filaIndex + 1}: El valor "${valorCampoDestino}" para "${campoDestino}" no es válido según las reglas de dependencia aplicables. Valores permitidos: [${Array.from(finalAllowedValues).join(", ")}].`
                            };

                            // Evitar errores duplicados para la misma celda
                            const errorExiste = erroresValidacion.some(e => e.fila === filaIndex && e.columna === colDestinoIndex);
                            if (!errorExiste) {
                                erroresValidacion.push(error);
                                marcarError(filaIndex, colDestinoIndex);
                                hayErrores = true;
                                totalErrores++;
                            }
                        }

                        // TODO: Opcional: Validar si el campo destino es obligatorio cuando finalAllowedValues.size > 0 y valorCampoDestino === ""

                    } else if (anyDependencyRuleEvaluated && valorCampoDestino === "" && finalAllowedValues.size === 0) {
                        // TODO: Opcional: Validar si el campo destino debe estar vacío según las reglas aplicables (si finalAllowedValues es 0)
                    }
                    // Si !anyDependencyRuleEvaluated, no hay reglas para este campo, o ninguna regla activa,
                    // por lo que cualquier valor (o vacío) es considerado válido por las reglas de dependencia.
                }
            });
        });

        // Mostrar resultados
        const validacionDiv = document.getElementById("validacionStatus");
        validacionDiv.style.display = "block";

        if (hayErrores) {
            validacionDiv.className = "error-list";
            const uniqueErrorCells = new Set(erroresValidacion.map(e => `${e.fila}-${e.columna}`)).size;
            validacionDiv.innerHTML = `<p class="error-msg">Se encontraron ${totalErrores} errores en ${uniqueErrorCells} celdas.</p>`;
            const erroresHTML = erroresValidacion.slice(0, 10).map(err => `<li>Fila ${err.fila + 1}, Columna "${encabezados[err.columna] || err.columna}": ${err.mensaje.substring(err.mensaje.indexOf(":") + 1).trim()}</li>`).join("");
            validacionDiv.innerHTML += `<ol>${erroresHTML}</ol>`;
            if (erroresValidacion.length > 10) {
                validacionDiv.innerHTML += `<p>... y ${erroresValidacion.length - 10} errores más. Corrige estos errores antes de cargar los datos.</p>`;
            }
            document.getElementById("btnCargar").style.display = "none";
        } else {
            validacionDiv.className = "error-list"; // Puedes reutilizar la clase o crear una para éxito
            validacionDiv.style.backgroundColor = "#e8f5e9"; // Fondo verde claro
            validacionDiv.style.borderLeft = "4px solid #4caf50"; // Borde verde
            validacionDiv.innerHTML = `<p style="color: #4caf50; font-weight: bold;">¡Validación exitosa! Los ${totalFilas} registros cumplen con todas las reglas.</p>`;
            document.getElementById("btnCargar").style.display = "inline-block";
        }
    }

    // La función marcarError() se mantiene igual
    function marcarError(filaIndex, colIndex) {
        const celda = document.querySelector(`tr[data-fila="${filaIndex}"] td[data-col="${colIndex}"]`);
        if (celda) {
            celda.classList.add("error-cell");
        }
    }

    // La función confirmarCarga() se mantiene igual
    function confirmarCarga() {
            if (erroresValidacion.length > 0) {
                alert("No se pueden cargar los datos porque contienen errores. Por favor, corrige los errores primero.");
                return;
            }

            if (confirm("¿Estás seguro de que deseas cargar los datos a la base de datos?")) {
                fetch('../backend/cargar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ datos: datosCSV })
                })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                })
                .catch(error => {
                    alert("Error al cargar los datos: " + error.message);
                });
            }
        }

    </script>
</body>
</html>