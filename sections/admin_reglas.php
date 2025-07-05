<?php
require_once __DIR__ . '/../backend/auth.php';

require_login_and_role(1);

$campos_destino_posibles = [
    'SAP', 'YEAR', 'MES', 'OCASION_DE_USO', 'NOMBRE', 'MODULO', 'TEMPORADA', 'CAPSULA', 'CLIMA', 'TIENDA', 'CLASIFICACION', 'CLUSTER', 'PROVEEDOR', 'CATEGORIAS', 'SUBCATEGORIAS', 'DISENO', 'DESCRIPCION', 'MANGA', 'TIPO_MANGA', 'PUNO', 'CAPOTA', 'ESCOTE', 'LARGO', 'CUELLO', 'TIRO', 'BOTA', 'CINTURA', 'SILUETA', 'CIERRE', 'GALGA', 'TIPO_GALGA', 'COLOR_FDS', 'NOM_COLOR', 'GAMA', 'PRINT', 'TALLAS', 'TIPO_TEJIDO', 'TIPO_DE_FIBRA', 'BASE_TEXTIL', 'DETALLES', 'SUB_DETALLES', 'GRUPO', 'INSTRUCCION_DE_LAVADO_1', 'INSTRUCCION_DE_LAVADO_2', 'INSTRUCCION_DE_LAVADO_3', 'INSTRUCCION_DE_LAVADO_4', 'INSTRUCCION_DE_LAVADO_5', 'INSTRUCCION_BLANQUEADO_1', 'INSTRUCCION_BLANQUEADO_2', 'INSTRUCCION_BLANQUEADO_3', 'INSTRUCCION_BLANQUEADO_4', 'INSTRUCCION_BLANQUEADO_5', 'INSTRUCCION_SECADO_1', 'INSTRUCCION_SECADO_2', 'INSTRUCCION_SECADO_3', 'INSTRUCCION_SECADO_4', 'INSTRUCCION_SECADO_5', 'INSTRUCCION_PLANCHADO_1', 'INSTRUCCION_PLANCHADO_2', 'INSTRUCCION_PLANCHADO_3', 'INSTRUCCION_PLANCHADO_4', 'INSTRUCCION_PLANCHADO_5', 'INSTRUCC_CUIDADO_TEXTIL_PROF_1', 'INSTRUCC_CUIDADO_TEXTIL_PROF_2', 'INSTRUCC_CUIDADO_TEXTIL_PROF_3', 'INSTRUCC_CUIDADO_TEXTIL_PROF_4', 'INSTRUCC_CUIDADO_TEXTIL_PROF_5', 'COMPOSICION_1', '%_COMP_1', 'COMPOSICION_2', '%_COMP_2', 'COMPOSICION_3', '%_COMP_3', 'COMPOSICION_4', '%_COMP_4', 'TOT_COMP', 'FORRO', 'COMP_FORRO_1', '%_FORRO_1', 'COMP_FORRO_2', '%_FORRO_2', 'TOT_FORRO', 'RELLENO', 'COMP_RELLENO_1', '%_RELLENO_1', 'COMP_RELLENO_2', '%_RELLENO_2', 'TOT_RELLENO', 'XX'
];

$campos_origen_posibles = [
    'SAP', 'YEAR', 'MES', 'OCASION_DE_USO', 'NOMBRE', 'MODULO', 'TEMPORADA', 'CAPSULA', 'CLIMA', 'TIENDA', 'CLASIFICACION', 'CLUSTER', 'PROVEEDOR', 'CATEGORIAS', 'SUBCATEGORIAS', 'DISENO', 'DESCRIPCION', 'MANGA', 'TIPO_MANGA', 'PUNO', 'CAPOTA', 'ESCOTE', 'LARGO', 'CUELLO', 'TIRO', 'BOTA', 'CINTURA', 'SILUETA', 'CIERRE', 'GALGA', 'TIPO_GALGA', 'COLOR_FDS', 'NOM_COLOR', 'GAMA', 'PRINT', 'TALLAS', 'TIPO_TEJIDO', 'TIPO_DE_FIBRA', 'BASE_TEXTIL', 'DETALLES', 'SUB_DETALLES', 'GRUPO', 'INSTRUCCION_DE_LAVADO_1', 'INSTRUCCION_DE_LAVADO_2', 'INSTRUCCION_DE_LAVADO_3', 'INSTRUCCION_DE_LAVADO_4', 'INSTRUCCION_DE_LAVADO_5', 'INSTRUCCION_BLANQUEADO_1', 'INSTRUCCION_BLANQUEADO_2', 'INSTRUCCION_BLANQUEADO_3', 'INSTRUCCION_BLANQUEADO_4', 'INSTRUCCION_BLANQUEADO_5', 'INSTRUCCION_SECADO_1', 'INSTRUCCION_SECADO_2', 'INSTRUCCION_SECADO_3', 'INSTRUCCION_SECADO_4', 'INSTRUCCION_SECADO_5', 'INSTRUCCION_PLANCHADO_1', 'INSTRUCCION_PLANCHADO_2', 'INSTRUCCION_PLANCHADO_3', 'INSTRUCCION_PLANCHADO_4', 'INSTRUCCION_PLANCHADO_5', 'INSTRUCC_CUIDADO_TEXTIL_PROF_1', 'INSTRUCC_CUIDADO_TEXTIL_PROF_2', 'INSTRUCC_CUIDADO_TEXTIL_PROF_3', 'INSTRUCC_CUIDADO_TEXTIL_PROF_4', 'INSTRUCC_CUIDADO_TEXTIL_PROF_5', 'COMPOSICION_1', '%_COMP_1', 'COMPOSICION_2', '%_COMP_2', 'COMPOSICION_3', '%_COMP_3', 'COMPOSICION_4','%_COMP_4', 'TOT_COMP', 'FORRO', 'COMP_FORRO_1', '%_FORRO_1', 'COMP_FORRO_2', '%_FORRO_2', 'TOT_FORRO', 'RELLENO', 'COMP_RELLENO_1', '%_RELLENO_1', 'COMP_RELLENO_2', '%_RELLENO_2', 'TOT_RELLENO', 'XX'
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Reglas</title>
    <style>

        :root {
            --color-background: #F9F3E5; /* Fondo claro y elegante */
            --color-text-dark: #000000; /* Texto oscuro principal */
            --color-primary: #879683; /* Verde/Gris principal, para elementos interactivos */
            --color-secondary: #5A6B58; /* Un tono más oscuro del primario, para hover/activos */
            --color-highlight: #C5D4C1; /* Un tono más claro, para bordes o detalles */
            --color-logout: #a0a0a0; /* Gris para el botón de cerrar sesión */
            --color-logout-hover: #8a8a8a; /* Gris más oscuro para hover de cerrar sesión */
            
            /* Nuevas variables para mejorar el diseño */
            --shadow-soft: 0 8px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.12);
            --border-radius: 12px;
            --transition-standard: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            
            /* Variables para mensajes de estado */
            --color-success-bg: #E8F5E9;
            --color-success-text: #2E7D32;
            --color-success-border: #A5D6A7;
            --color-error-bg: #FFEBEE;
            --color-error-text: #C62828;
            --color-error-border: #EF9A9A;
        }

        body {
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            padding: 0;
            margin: 0;
            background-color: var(--color-background);
            color: var(--color-text-dark);
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            max-width: 1000px;
            margin: 40px auto;
            transition: var(--transition-standard);
        }

        .container:hover {
            box-shadow: var(--shadow-hover);
        }

        h1, h3 {
            color: var(--color-secondary);
            margin-top: 0;
            border-bottom: 2px solid var(--color-highlight);
            padding-bottom: 10px;
            font-weight: 600;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 25px;
        }

        h3 {
            font-size: 1.5rem;
            margin-top: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--color-secondary);
            transition: var(--transition-standard);
        }

        input[type="text"], select, textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid var(--color-highlight);
            border-radius: var(--border-radius);
            box-sizing: border-box;
            font-size: 1rem;
            transition: var(--transition-standard);
            background-color: #fff;
        }

        input[type="text"]:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
        }

        button {
            padding: 12px 20px;
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            margin-right: 10px;
            transition: var(--transition-standard);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: var(--color-secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        button:active {
            transform: translateY(0);
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        .remove-btn {
            background-color: #d9534f;
            font-size: 0.9rem;
            padding: 8px 14px;
        }

        .remove-btn:hover {
            background-color: #c9302c;
        }

        .add-btn {
            background-color: var(--color-primary);
            margin-top: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .add-btn:hover {
            background-color: var(--color-secondary);
        }

        .add-btn::before {
            content: "+";
            margin-right: 5px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        fieldset {
            border: 1px solid var(--color-highlight);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            background-color: rgba(255, 255, 255, 0.7);
            transition: var(--transition-standard);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        fieldset:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            border-color: var(--color-primary);
        }

        legend {
            font-weight: 600;
            color: var(--color-primary);
            padding: 0 15px;
            font-size: 1.1rem;
        }

        .dynamic-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            border: 1px dashed var(--color-highlight);
            border-radius: var(--border-radius);
            background-color: #fff;
            flex-wrap: wrap;
            transition: var(--transition-standard);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dynamic-row:hover {
            border-color: var(--color-primary);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .dynamic-row label {
            margin-bottom: 0;
            width: auto;
            flex-shrink: 0;
        }

        .dynamic-row input, .dynamic-row select {
            width: auto;
            flex-grow: 1;
            margin-bottom: 0;
            min-width: 150px;
        }

        .dynamic-row .remove-btn {
            flex-shrink: 0;
        }

        #status-message {
            margin-top: 25px;
            padding: 18px;
            border-radius: var(--border-radius);
            display: none;
            font-weight: 500;
            text-align: center;
            animation: slideIn 0.4s ease-out;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success {
            background-color: var(--color-success-bg);
            color: var(--color-success-text);
            border: 1px solid var(--color-success-border);
        }

        .error {
            background-color: var(--color-error-bg);
            color: var(--color-error-text);
            border: 1px solid var(--color-error-border);
        }

        /* Estilos para la nueva interfaz de condiciones complejas */
        .condition-group {
            border: 1px solid var(--color-highlight);
            padding: 15px;
            margin-bottom: 15px;
            background-color: rgba(197, 212, 193, 0.1);
            border-radius: var(--border-radius);
            transition: var(--transition-standard);
        }

        .condition-group:hover {
            border-color: var(--color-primary);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.07);
        }

        .condition-group legend {
            font-weight: 600;
            color: var(--color-secondary);
            padding: 0 10px;
            font-size: 1rem;
        }

        .condition-group .dynamic-row {
            border: none;
            padding: 12px;
            margin-bottom: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
        }

        .condition-group-actions {
            margin-top: 15px;
            text-align: right;
        }

        .branch {
            border: 1px dashed var(--color-primary);
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgba(197, 212, 193, 0.15);
            border-radius: var(--border-radius);
            transition: var(--transition-standard);
            animation: fadeIn 0.6s ease-out;
            position: relative;
        }

        .branch:hover {
            border-style: solid;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .branch legend {
            font-weight: 600;
            color: var(--color-secondary);
            padding: 0 10px;
            font-size: 1.05rem;
        }

        .branch .dynamic-row {
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .branch-actions {
            text-align: right;
            margin-top: 15px;
        }

        .allowed-values-section {
            border-top: 1px solid var(--color-highlight);
            margin-top: 25px;
            padding-top: 20px;
        }

        /* Show/Hide containers */
        .hidden {
            display: none;
        }

        /* Estilos para radio buttons y checkboxes */
        input[type="radio"], input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
            accent-color: var(--color-primary);
        }

        /* Estilos para etiquetas inline */
        label[style*="inline-flex"] {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            cursor: pointer;
            transition: var(--transition-standard);
            padding: 5px 10px;
            border-radius: var(--border-radius);
        }

        label[style*="inline-flex"]:hover {
            background-color: rgba(197, 212, 193, 0.2);
        }

        /* Botón de volver al menú */
        a[href="index.php"] button {
            background-color: var(--color-logout);
            transition: var(--transition-standard);
            display: inline-flex;
            align-items: center;
        }

        a[href="index.php"] button:hover {
            background-color: var(--color-logout-hover);
        }

        a[href="index.php"] button::before {
            content: "←";
            margin-right: 8px;
            font-weight: bold;
        }

        /* Botón de reset */
        #reset-btn {
            background-color: #f0ad4e;
        }

        #reset-btn:hover {
            background-color: #ec971f;
        }

        /* Separador horizontal */
        hr {
            border: none;
            height: 1px;
            background-color: var(--color-highlight);
            margin: 30px 0;
        }

        /* Estilos para texto pequeño (small) */
        small {
            color: #666;
            font-style: italic;
            display: block;
            margin-top: 8px;
            transition: var(--transition-standard);
        }

        small:hover {
            color: #444;
        }

        /* Animaciones para elementos que se añaden dinámicamente */
        @keyframes pulseHighlight {
            0% { box-shadow: 0 0 0 0 rgba(135, 150, 131, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(135, 150, 131, 0); }
            100% { box-shadow: 0 0 0 0 rgba(135, 150, 131, 0); }
        }

        .dynamic-row:last-child {
            animation: fadeIn 0.5s ease-out, pulseHighlight 1.5s ease-out;
        }

        /* Mejoras para dispositivos móviles */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px 10px;
            }
            
            .dynamic-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .dynamic-row input, .dynamic-row select {
                width: 100%;
            }
            
            .dynamic-row .remove-btn {
                align-self: flex-end;
                margin-top: 10px;
            }
            
            button {
                width: 100%;
                margin-bottom: 10px;
                margin-right: 0;
            }
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Administrar Reglas de Dependencia</h1>
    <a href="index.php" style="text-decoration: none; color: #337ab7;">
        <button>Volver al menu</button>
    </a>
    <br><br>

    <fieldset>
        <legend>Crear / Editar Regla</legend>
        <form id="rule-form">
            <input type="hidden" id="rule_id" name="rule_id" value="">

            <div>
                <label for="nombre_regla">Nombre Descriptivo:</label>
                <input type="text" id="nombre_regla" name="nombre_regla" placeholder="Ej: Subcategorías para Blusas Verano">
            </div>

            <div>
                <label for="campo_destino">Campo a Restringir (Destino):</label>
                <select id="campo_destino" name="campo_destino" >
                    <option value="">-- Seleccione el campo cuyas opciones dependen de otros --</option>
                    <?php foreach ($campos_destino_posibles as $campo): ?>
                        <option value="<?php echo htmlspecialchars($campo); ?>"><?php echo htmlspecialchars($campo); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <fieldset>
                <legend>Tipo de Lógica de Condiciones</legend>
                <div>
                    <label style="display: inline-flex; align-items: center; gap: 5px; margin-right: 20px;">
                        <input type="radio" name="rule_type" value="simple_and" checked>
                        Condiciones Simples (Todas se cumplen - AND)
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 5px;">
                         <input type="radio" name="rule_type" value="branched">
                         Condiciones Ramificadas (IF ... THEN ALLOW ...)
                    </label>
                </div>
            </fieldset>

            <fieldset id="conditions-fieldset">
                <legend>Condiciones</legend>

                <div id="simple-conditions-container">
                    <div id="simple-condition-rows">
                         </div>
                    <button type="button" class="add-btn" id="add-simple-condition-btn">+ Añadir Condición Simple</button>
                    <small style="display: block; margin-top: 5px; color: #666;">La regla se aplicará si TODAS estas condiciones se cumplen.</small>
                </div>

                <div id="branched-conditions-container" class="hidden">
                     <p>Define ramas condicionales. La regla aplicará los valores permitidos de las ramas cuyas condiciones se cumplan.</p>
                     <div id="branches-container">
                         </div>
                    <button type="button" class="add-btn" id="add-branch-btn">+ Añadir Rama Condicional</button>

                    <div class="allowed-values-section">
                         <label>Valores Permitidos por Defecto (si ninguna rama aplica):</label>
                         <div id="default-allowed-values-container">
                             </div>
                          <button type="button" class="add-btn" id="add-default-allowed-value-btn">+ Añadir Valor por Defecto</button>
                          <small style="display: block; margin-top: 5px; color: #666;">Estos valores se permitirán si la regla es de tipo "Condiciones Ramificadas" y ninguna de las ramas anteriores se cumple para una fila específica.</small>
                    </div>
                </div>

            </fieldset>

            <fieldset id="allowed-values-fieldset" class="allowed-values-section">
                <legend>Valores Permitidos (para Reglas de Condiciones Simples)</legend>
                <div id="simple-allowed-values-container">
                                    </div>
                <button type="button" class="add-btn" id="add-simple-allowed-value-btn">+ Añadir Valor Permitido</button>
                 <small style="display: block; margin-top: 5px; color: #666;">Estos valores se permitirán si la regla es de tipo "Condiciones Simples" y se cumplen sus condiciones. Debes añadir al menos uno.</small>
            </fieldset>

            <div>
                 <label style="display: inline-flex; align-items: center; gap: 5px;">
                     <input type="checkbox" id="es_activa" name="es_activa" value="1" checked style="width: auto;">
                     Regla Activa
                 </label>
            </div>

            <hr style="margin: 20px 0;">

            <button type="submit">Guardar Regla</button>
            <button type="button" id="reset-btn" style="background-color: #f0ad4e;">Limpiar Formulario</button>
             </form>
         <div id="status-message"></div> </fieldset>

    </div>

        <template id="simple-condition-row-template">
        <div class="dynamic-row condition-row simple-condition-row">
            <label>Si Campo:</label>
            <select name="simple_condition_field[]"  class="condition-field">
                <option value="">-- Campo Origen --</option>
                <?php foreach ($campos_origen_posibles as $campo): ?>
                    <option value="<?php echo htmlspecialchars($campo); ?>"><?php echo htmlspecialchars($campo); ?></option>
                <?php endforeach; ?>
            </select>
            <label>es igual a:</label>             <input type="text" name="simple_condition_value[]" placeholder="Valor Requerido"  class="condition-value">
            <button type="button" class="remove-btn" onclick="removeRow(this)">Quitar</button>
        </div>
    </template>

        <template id="simple-allowed-value-row-template">
         <div class="dynamic-row allowed-value-row simple-allowed-value-row">
            <label>Permitir Valor:</label>
            <input type="text" name="simple_allowed_value[]" placeholder="Valor Permitido"  class="allowed-value">
            <button type="button" class="remove-btn" onclick="removeRow(this)">Quitar</button>
        </div>
    </template>

    <template id="branch-template">
        <div class="branch">
             <legend>Rama Condicional</legend>
             <p>SI se cumplen estas condiciones:</p>
             <div class="branch-conditions-container">
                 <div class="dynamic-row condition-row branch-condition-row">
                     <label>Si Campo:</label>
                     <select name="branch_condition_field"  class="condition-field">
                         <option value="">-- Campo Origen --</option>
                          <?php foreach ($campos_origen_posibles as $campo): ?>
                             <option value="<?php echo htmlspecialchars($campo); ?>"><?php echo htmlspecialchars($campo); ?></option>
                         <?php endforeach; ?>
                     </select>
                     <label>es igual a:</label> <input type="text" name="branch_condition_value" placeholder="Valor Requerido"  class="condition-value">
                     </div>
             </div>
             <button type="button" class="add-btn add-branch-condition-btn" style="font-size: 0.9em; padding: 5px 10px;">+ Añadir Otra Condición (AND)</button>


             <p style="margin-top: 15px;">ENTONCES se permiten estos valores:</p>
             <div class="branch-allowed-values-container">
                 <div class="dynamic-row allowed-value-row branch-allowed-value-row">
                      <label>Permitir Valor:</label>
                      <input type="text" name="branch_allowed_value[]" placeholder="Valor Permitido"  class="allowed-value">
                      <button type="button" class="remove-btn remove-branch-allowed-value-btn" onclick="removeRow(this)">Quitar</button>
                  </div>
             </div>
             <button type="button" class="add-btn add-branch-allowed-value-btn" style="font-size: 0.9em; padding: 5px 10px;">+ Añadir Valor Permitido</button>


             <div class="branch-actions">
                  <button type="button" class="remove-btn remove-branch-btn" onclick="removeRow(this)">Quitar Rama</button>
             </div>
        </div>
    </template>

    <template id="default-allowed-value-template">
        <div class="dynamic-row allowed-value-row default-allowed-value-row">
            <label>Permitir Valor por Defecto:</label>
            <input type="text" name="default_allowed_value[]" placeholder="Valor Permitido por Defecto"  class="allowed-value">
            <button type="button" class="remove-btn" onclick="removeRow(this)">Quitar</button>
        </div>
    </template>


<script>
    /**
     * Añade una fila/clon de un template a un contenedor específico.
     * @param {HTMLElement} containerElement - Contenedor donde se añade la fila.
     * @param {string} templateId - ID del template HTML.
     * @param {object|null} initialContent - Valores iniciales para los campos (opcional).
     */
    function addRow(containerElement, templateId, initialContent = null) {
        const template = document.getElementById(templateId);
        if (template && containerElement) {
            const clone = template.content.cloneNode(true);
             // If initialContent is provided, try to populate the cloned row
             if (initialContent) {
                 // This part needs to be smarter to handle different template types
                 // For now, a basic example for simple condition/value rows
                 if (templateId === 'simple-condition-row-template' || templateId === 'branch-condition-row-template') { // Need separate template for branch conditions
                     const fieldSelect = clone.querySelector('.condition-field');
                     const valueInput = clone.querySelector('.condition-value');
                     if (fieldSelect && initialContent.campo) fieldSelect.value = initialContent.campo;
                     if (valueInput && initialContent.valor !== undefined) valueInput.value = initialContent.valor;
                 } else if (templateId === 'simple-allowed-value-row-template' || templateId === 'branch-allowed-value-template' || templateId === 'default-allowed-value-template') { // Need separate template for branch allowed values
                      const valueInput = clone.querySelector('.allowed-value');
                      if (valueInput && initialContent !== undefined) valueInput.value = initialContent; // initialContent is the value itself for allowed values
                 }
             }
            containerElement.appendChild(clone);
        } else {
            console.error("No se encontró el template o el contenedor:", templateId, containerElement);
        }
    }

    /**
     * Elimina la fila dinámica (condición, valor, rama, etc.) asociada al botón.
     * @param {HTMLElement} button - Botón que disparó la acción.
     */
    function removeRow(button) {
        const rowElement = button.closest('.dynamic-row, .branch'); // Can remove dynamic-row or branch
        if (rowElement) {
            rowElement.remove();
        }
    }

    // ===============================
    // Funciones para condiciones simples (AND)
    // ===============================
    function addSimpleConditionRow(initialContent = null) {
        // Añade una fila de condición simple al contenedor correspondiente
        const container = document.getElementById('simple-condition-rows');
        addRow(container, 'simple-condition-row-template', initialContent);
    }

    function addSimpleAllowedValueRow(initialContent = null) {
        // Añade una fila de valor permitido simple al contenedor correspondiente
        const container = document.getElementById('simple-allowed-values-container');
        addRow(container, 'simple-allowed-value-row-template', initialContent);
    }

    // ===============================
    // Funciones para condiciones ramificadas (IF/THEN)
    // ===============================
    function addBranch() {
        // Añade una nueva rama condicional (con condiciones y valores permitidos)
         const branchesContainer = document.getElementById('branches-container');
         const template = document.getElementById('branch-template');
         if (template && branchesContainer) {
             const clone = template.content.cloneNode(true);
             const branchConditionsContainer = clone.querySelector('.branch-conditions-container');
             if (branchConditionsContainer) {
                 const branchConditionTemplate = document.getElementById('simple-condition-row-template'); // Reusing for now, but better to have a dedicated one
                 if (branchConditionTemplate) {
                     const condClone = branchConditionTemplate.content.cloneNode(true);
                     condClone.querySelector('.condition-field').name = 'branch_condition_field';
                     condClone.querySelector('.condition-value').name = 'branch_condition_value'; 
                     branchConditionsContainer.appendChild(condClone);
                 }
             }
             
              const branchAllowedValuesContainer = clone.querySelector('.branch-allowed-values-container');
              if (branchAllowedValuesContainer) {
                  const allowedValueTemplate = document.getElementById('simple-allowed-value-row-template'); // Reusing for now, but better dedicated
                  if (allowedValueTemplate) {
                       const valClone = allowedValueTemplate.content.cloneNode(true);
                       valClone.querySelector('.allowed-value').name = 'branch_allowed_value[]'; // Update name
                       branchAllowedValuesContainer.appendChild(valClone);
                  }
              }

             branchesContainer.appendChild(clone);
             const addBranchConditionBtn = branchesContainer.lastElementChild.querySelector('.add-branch-condition-btn');
              if (addBranchConditionBtn) {
                  addBranchConditionBtn.addEventListener('click', function() {
                      addConditionToBranch(addBranchConditionBtn.closest('.branch').querySelector('.branch-conditions-container'));
                  });
              }
             const addBranchAllowedValueBtn = branchesContainer.lastElementChild.querySelector('.add-branch-allowed-value-btn');
              if (addBranchAllowedValueBtn) {
                  addBranchAllowedValueBtn.addEventListener('click', function() {
                      addAllowedValueToBranch(addBranchAllowedValueBtn.closest('.branch').querySelector('.branch-allowed-values-container'));
                  });
              }
         } else {
              console.error("No se encontró el template o el contenedor:", 'branch-template', branchesContainer);
         }
    }

    function addConditionToBranch(containerElement, initialContent = null) {
         // Añade una condición a una rama específica
         const template = document.getElementById('simple-condition-row-template'); 
         if (template && containerElement) {
             const clone = template.content.cloneNode(true);
             clone.querySelector('.condition-field').name = 'branch_condition_field[]'; 
             clone.querySelector('.condition-value').name = 'branch_condition_value[]';

             if (initialContent) {
                 const fieldSelect = clone.querySelector('.condition-field');
                 const valueInput = clone.querySelector('.condition-value');
                 if (fieldSelect && initialContent.campo) fieldSelect.value = initialContent.campo;
                 if (valueInput && initialContent.valor !== undefined) valueInput.value = initialContent.valor;
             }

             containerElement.appendChild(clone);
         } else {
             console.error("No se encontró el template o el contenedor:", 'simple-condition-row-template', containerElement);
         }
    }

     function addAllowedValueToBranch(containerElement, initialContent = null) {
        // Añade un valor permitido a una rama específica
         const template = document.getElementById('simple-allowed-value-row-template'); 
         if (template && containerElement) {
             const clone = template.content.cloneNode(true);
             clone.querySelector('.allowed-value').name = 'branch_allowed_value[]'; 

             if (initialContent !== undefined) {
                  const valueInput = clone.querySelector('.allowed-value');
                  if (valueInput) valueInput.value = initialContent;
             }

             containerElement.appendChild(clone);
         } else {
              console.error("No se encontró el template o el contenedor:", 'simple-allowed-value-row-template', containerElement);
         }
     }


    function addDefaultAllowedValue() {
        // Añade un valor permitido por defecto para ramas
         const container = document.getElementById('default-allowed-values-container');
         addRow(container, 'default-allowed-value-template');
    }


    // ===============================
    // Lógica de visibilidad según tipo de regla
    // ===============================
    function updateRuleTypeUI() {
        // Muestra/oculta secciones según el tipo de regla seleccionado (AND simple o ramificada)
        const ruleType = document.querySelector('input[name="rule_type"]:checked').value;
        const simpleConditionsDiv = document.getElementById('simple-conditions-container');
        const branchedConditionsDiv = document.getElementById('branched-conditions-container');
        const simpleAllowedValuesFieldset = document.getElementById('allowed-values-fieldset'); 

        if (ruleType === 'simple_and') {
            simpleConditionsDiv.classList.remove('hidden');
            branchedConditionsDiv.classList.add('hidden');
            simpleAllowedValuesFieldset.classList.remove('hidden');
            simpleAllowedValuesFieldset.querySelector('legend').textContent = 'Valores Permitidos';
            simpleConditionsDiv.closest('fieldset').querySelector('legend').textContent = 'Condiciones (AND)';


        } else if (ruleType === 'branched') {
            simpleConditionsDiv.classList.add('hidden');
            branchedConditionsDiv.classList.remove('hidden');
            simpleAllowedValuesFieldset.classList.add('hidden');
             branchedConditionsDiv.closest('fieldset').querySelector('legend').textContent = 'Condiciones Ramificadas';

        }
    }

    // ===============================
    // Reseteo del formulario y UI
    // ===============================
    function resetForm() {
        // Limpia todos los campos y contenedores dinámicos del formulario
         const form = document.getElementById('rule-form');
         form.reset();
         document.getElementById('simple-condition-rows').innerHTML = '';
         document.getElementById('simple-allowed-values-container').innerHTML = '';
           document.getElementById('branches-container').innerHTML = ''; 
           document.getElementById('default-allowed-values-container').innerHTML = ''; 

         document.getElementById('rule_id').value = '';
         const statusDiv = document.getElementById('status-message');
         if (statusDiv) {
             statusDiv.style.display = 'none';
             statusDiv.textContent = ''; 
             statusDiv.className = ''; 
         }
         document.querySelector('input[name="rule_type"][value="simple_and"]').checked = true;
         updateRuleTypeUI();
    }

    // ===============================
    // Recolección de datos y armado del JSON para enviar al backend
    // ===============================
    function collectFormDataAndBuildJson() {
        // Construye el objeto de datos de la regla según el tipo (AND simple o ramificada)
        // Incluye validaciones y estructura el JSON para el backend
        const form = document.getElementById('rule-form');
        const ruleType = document.querySelector('input[name="rule_type"]:checked').value;

         const ruleData = {
            rule_id: document.getElementById('rule_id').value,
            nombre_regla: document.getElementById('nombre_regla').value.trim(),
            campo_destino: document.getElementById('campo_destino').value,
            es_activa: document.getElementById('es_activa').checked ? 1 : 0,
            condiciones: null, 
            valores_permitidos: [] 
        };

        if (ruleType === 'simple_and') {
            const simpleConditionRows = form.querySelectorAll('#simple-condition-rows .simple-condition-row');
            const simpleConditions = [];
            simpleConditionRows.forEach(row => {
                const field = row.querySelector('.condition-field').value.trim();
                const value = row.querySelector('.condition-value').value.trim();
                if (field !== '' && value !== '') {
                    simpleConditions.push({ "operator": "condition", "field": field, "value": value });
                } else if (field !== '' || value !== '') {
                    console.warn("Simple condition row incomplete, ignoring:", row);
                }
            });

            // Para reglas simples (AND), las condiciones deben ser un objeto con "operator" y "conditions"
            if (simpleConditions.length > 0) {
                ruleData.condiciones = { "operator": "AND", "conditions": simpleConditions };
            } else {
                ruleData.condiciones = []; // Si no hay condiciones, envía un array vacío
            }

            const simpleAllowedValueRows = form.querySelectorAll('#simple-allowed-values-container .simple-allowed-value-row');
            const simpleAllowedValues = [];
            simpleAllowedValueRows.forEach(row => {
                const value = row.querySelector('.allowed-value').value.trim();
                if (value !== '') {
                    simpleAllowedValues.push(value);
                }
            });
            ruleData.valores_permitidos = simpleAllowedValues; 


        } else if (ruleType === 'branched') {
            const branchesContainer = document.getElementById('branches-container');
            const branches = [];
             function collectBranchConditions(branchElement) {
                 const branchConditionRows = branchElement.querySelectorAll('.branch-conditions-container .condition-row');
                 const conditions = [];
                 branchConditionRows.forEach(row => {
                     const field = row.querySelector('.condition-field').value.trim();
                     const value = row.querySelector('.condition-value').value.trim();
                     if (field !== '' && value !== '') {
                          conditions.push({ "operator": "condition", "field": field, "value": value });
                     } else if (field !== '' || value !== '') {
                         console.warn("Branch condition row incomplete, ignoring:", row);
                     }
                 });

                 if (conditions.length > 0) {
                     return { "operator": "AND", "conditions": conditions };
                 }
                 return null;
             }

             function collectBranchAllowedValues(branchElement) {
                 const branchAllowedValueRows = branchElement.querySelectorAll('.branch-allowed-values-container .allowed-value-row');
                 const allowedValues = [];
                 branchAllowedValueRows.forEach(row => {
                     const value = row.querySelector('.allowed-value').value.trim();
                     if (value !== '') {
                         allowedValues.push(value);
                     }
                 });
                 return allowedValues;
             }


            const branchElements = branchesContainer.querySelectorAll('.branch');
            branchElements.forEach(branchElement => {
                 const branchIfConditions = collectBranchConditions(branchElement);
                 const branchThenAllow = collectBranchAllowedValues(branchElement);

                 if (branchIfConditions && branchThenAllow.length > 0) { 
                     branches.push({
                         "if": branchIfConditions,
                         "then_allow": branchThenAllow
                     });
                 } else {
                     console.warn("Ignoring incomplete or invalid branch:", branchElement);
                 }
            });

             const defaultAllowedValueRows = form.querySelectorAll('#default-allowed-values-container .default-allowed-value-row');
             const defaultAllowedValues = [];
             defaultAllowedValueRows.forEach(row => {
                 const value = row.querySelector('.allowed-value').value.trim();
                 if (value !== '') {
                      defaultAllowedValues.push(value);
                 }
             });

            if (branches.length > 0) {
                 ruleData.condiciones = {
                     "type": "branched_conditions",
                     "branches": branches,
                     "default_allow": defaultAllowedValues
                 };
            } else {
                 console.error("Branched rule type requires at least one valid branch.");
                 alert("Error: Las Reglas Ramificadas requieren al menos una Rama Condicional completa.");
                 return null; 
            }

            ruleData.valores_permitidos = [];
        }

        if (!ruleData.campo_destino) {
             alert("Error: Debes seleccionar el 'Campo a Restringir (Destino)'.");
             return null;
        }

         if (ruleType === 'simple_and' && ruleData.valores_permitidos.length === 0) {
              alert("Error: Las Reglas Simples requieren al menos un 'Valor Permitido'.");
              return null;
         }

        return ruleData; 
    }


    // ===============================
    // Inicialización de eventos y lógica al cargar la página
    // ===============================
    document.addEventListener('DOMContentLoaded', function() {
        // Asigna eventos a los botones de añadir/quitar filas dinámicas
        document.getElementById('add-simple-condition-btn').addEventListener('click', () => addSimpleConditionRow());
        document.getElementById('add-simple-allowed-value-btn').addEventListener('click', () => addSimpleAllowedValueRow());
        document.getElementById('add-branch-btn').addEventListener('click', () => addBranch());
        document.getElementById('add-default-allowed-value-btn').addEventListener('click', () => addDefaultAllowedValue());

        // Asigna evento al botón de reset
        const resetBtn = document.getElementById('reset-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', resetForm);
        }

        // Asigna evento a los radio buttons para cambiar la UI según el tipo de regla
        document.querySelectorAll('input[name="rule_type"]').forEach(radio => {
            radio.addEventListener('change', updateRuleTypeUI);
        });

        // Inicializa la UI según el tipo de regla por defecto
        updateRuleTypeUI();


        // Asigna evento al submit del formulario para enviar los datos al backend por fetch
        const ruleForm = document.getElementById('rule-form');
        if (ruleForm) {
            ruleForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Evita el envío tradicional HTML

                const ruleData = collectFormDataAndBuildJson();

                if (!ruleData) {
                     return;
                }

                console.log("Rule data being sent:", ruleData); 


                // --- Envío de datos al backend (PHP) ---
                const statusDiv = document.getElementById('status-message');
                statusDiv.style.display = 'none'; // Ocultar mensaje previo
                statusDiv.className = ''; // Limpiar clases


                fetch('../backend/guardar_regla.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(ruleData)
                })
                .then(response => {
                    if (!response.ok) {
                         // Intentar obtener más detalles del error si es posible
                        return response.text().then(text => {
                            throw new Error(`Error HTTP: ${response.status}. Respuesta: ${text}`);
                        });
                    }
                     // Verificar si la respuesta parece JSON antes de intentar parsearla
                     const contentType = response.headers.get("content-type");
                     if (contentType && contentType.indexOf("application/json") !== -1) {
                         return response.json();
                     } else {
                         return response.text().then(text => {
                            throw new Error(`Respuesta inesperada del servidor (no es JSON): ${text}`);
                         });
                     }
                })
                .then(data => {
                    if (data.success) {
                        statusDiv.textContent = data.message || '¡Regla guardada con éxito!';
                        statusDiv.className = 'success'; 
                        resetForm();
                    } else {
                        statusDiv.textContent = 'Error al guardar: ' + (data.message || 'Ocurrió un error desconocido.');
                        statusDiv.className = 'error'; 
                    }
                    statusDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error en la petición fetch:', error);
                    statusDiv.textContent = 'Error grave: ' + error.message;
                    statusDiv.className = 'error';
                    statusDiv.style.display = 'block';
                });
            }); 
        } 

    });
</script>