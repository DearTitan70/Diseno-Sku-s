
<?php
require_once __DIR__ . '/../backend/auth.php';

// Permite acceso a admin (1) 
require_login_and_role(1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Reglas de Dependencia</title>
    <link rel="icon" type="image/x-icon" href="../img/FDS_Favicon.png">
    <style>
        /* ===========================
           VARIABLES CSS Y ESTILOS BASE
           =========================== */
        :root {
            --color-background: #F9F3E5;
            --color-text-dark: #000000;
            --color-primary: #879683;
            --color-secondary: #5A6B58;
            --color-highlight: #C5D4C1;
            --color-logout: #a0a0a0;
            --color-logout-hover: #8a8a8a;
            --color-error: #e74c3c;
            --color-success: #27ae60;
            --transition-speed: 0.3s;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        /* Estilos generales para el body y tipografía */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-background);
            color: var(--color-text-dark);
            margin: 0;
            padding: 2em;
            line-height: 1.6;
            transition: background-color var(--transition-speed);
        }

        /* Título principal */
        h1 {
            color: var(--color-secondary);
            border-bottom: 2px solid var(--color-primary);
            padding-bottom: 0.5em;
            margin-bottom: 1.5em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* ===========================
           ESTILOS DE FORMULARIO Y SECCIONES
           =========================== */
        .form-section {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 1.5em;
            margin-bottom: 2em;
            box-shadow: var(--box-shadow);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            animation: slideIn 0.5s ease-out;
        }

        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Etiquetas y campos de entrada */
        label {
            display: block;
            margin-top: 1em;
            margin-bottom: 0.5em;
            color: var(--color-secondary);
            font-weight: 500;
            transition: color var(--transition-speed);
        }
        label:hover {
            color: var(--color-text-dark);
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            max-width: 400px;
            padding: 0.8em;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1em;
            transition: border var(--transition-speed), box-shadow var(--transition-speed);
            background-color: rgba(255, 255, 255, 0.8);
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
        }

        /* Estilos para checkboxes y radios */
        input[type="checkbox"],
        input[type="radio"] {
            margin-right: 0.5em;
            transform: scale(1.2);
            accent-color: var(--color-primary);
        }

        /* Botones generales */
        button {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 0.8em 1.5em;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: background-color var(--transition-speed), transform var(--transition-speed);
            margin-top: 1em;
            box-shadow: var(--box-shadow);
        }
        button:hover {
            background-color: var(--color-secondary);
            transform: translateY(-2px);
        }
        button:active {
            transform: translateY(0);
        }

        /* Contenedores de condiciones, valores y ramas */
        .condiciones, 
        .valores-permitidos, 
        .branches {
            margin-left: 2em;
            padding: 1em;
            border-left: 3px solid var(--color-highlight);
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Rama individual */
        .branch {
            border: 1px solid var(--color-highlight);
            background-color: rgba(197, 212, 193, 0.1);
            padding: 1.5em;
            margin-bottom: 1.5em;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
            animation: fadeIn 0.5s ease-in-out;
        }
        .branch:hover {
            border-color: var(--color-primary);
            background-color: rgba(197, 212, 193, 0.2);
        }

        /* Acciones del formulario */
        .actions {
            margin-top: 2em;
            display: flex;
            align-items: center;
            gap: 1em;
        }

        /* Clases utilitarias */
        .hidden { display: none; }
        .error { color: var(--color-error); font-weight: 500; padding: 0.5em; animation: shake 0.5s ease-in-out; }
        .success { color: var(--color-success); font-weight: 500; padding: 0.5em; animation: pulse 1s ease-in-out; }

        /* ===========================
           ESTILOS DE GRUPOS DE INPUTS Y BOTONES
           =========================== */
        #condiciones_simple_lista > div,
        #valores_permitidos_simple_lista > div,
        .branch-conditions > div,
        .branch-allowed-values > div,
        #default_allowed_values > div {
            display: flex;
            align-items: center;
            gap: 0.8em;
            margin-bottom: 0.8em;
            padding: 0.8em;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: var(--border-radius);
            transition: background-color var(--transition-speed);
            animation: fadeIn 0.3s ease-in-out;
        }
        #condiciones_simple_lista > div:hover,
        #valores_permitidos_simple_lista > div:hover,
        .branch-conditions > div:hover,
        .branch-allowed-values > div:hover,
        #default_allowed_values > div:hover {
            background-color: rgba(197, 212, 193, 0.3);
        }

        /* Botón de eliminar */
        button[onclick*="remove"] {
            background-color: var(--color-logout);
            padding: 0.5em 0.8em;
            font-size: 0.9em;
        }
        button[onclick*="remove"]:hover {
            background-color: var(--color-logout-hover);
        }

        /* Botón de volver */
        button[onclick*="window.location"] {
            background-color: var(--color-secondary);
            display: inline-flex;
            align-items: center;
            gap: 0.5em;
            margin-bottom: 1.5em;
        }
        button[onclick*="window.location"]::before {
            content: "←";
            font-size: 1.2em;
        }
        button[onclick*="window.location"]:hover {
            background-color: var(--color-primary);
        }

        /* Título estilizado */
        .title {
            font-size: 2.2em;
            text-align: center;
            margin-bottom: 1em;
            color: var(--color-secondary);
            position: relative;
            padding-bottom: 0.5em;
            animation: fadeInDown 0.8s ease-out;
        }
        .title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--color-primary);
            animation: expandWidth 1s ease-out forwards;
        }

        /* ===========================
           ANIMACIONES Y RESPONSIVE
           =========================== */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        @keyframes expandWidth { from { width: 0; } to { width: 100px; } }

        /* Adaptación a pantallas pequeñas */
        @media (max-width: 768px) {
            body { padding: 1em; }
            .form-section { padding: 1em; }
            input[type="text"], input[type="number"], select { width: 100%; }
            #condiciones_simple_lista > div,
            #valores_permitidos_simple_lista > div,
            .branch-conditions > div,
            .branch-allowed-values > div,
            #default_allowed_values > div {
                flex-direction: column;
                align-items: flex-start;
            }
            button { width: 100%; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <!-- Título de la página -->
    <h1 class="title">Modificar Regla de Dependencia</h1>
    <!-- Botón para volver a la lista de reglas -->
    <button onclick="window.location.href='visualizar_reglas.php'">Volver a la lista de reglas</button><br><br>

    <!-- ===========================
         FORMULARIO DE EDICIÓN DE REGLA
         =========================== -->
    <form id="form_regla" class="hidden" onsubmit="guardarCambios(event)">
        <!-- Campo oculto para el ID de la regla -->
        <input type="hidden" id="id" name="id" />

        <!-- Sección de datos generales de la regla -->
        <div class="form-section">
            <label for="nombre_regla">Nombre de la regla:</label>
            <input type="text" id="nombre_regla" name="nombre_regla" required />

            <label for="campo_destino">Campo destino:</label>
            <input type="text" id="campo_destino" name="campo_destino" required />

            <label>
                <input type="checkbox" id="es_activa" name="es_activa" />
                ¿Regla activa?
            </label>
        </div>

        <!-- Selección del tipo de regla (simple o ramificada) -->
        <div class="form-section">
            <label>Tipo de regla:</label>
            <label><input type="radio" name="tipo_regla" value="AND" checked onchange="mostrarTipoRegla()"> Simple (AND)</label>
            <label><input type="radio" name="tipo_regla" value="branched" onchange="mostrarTipoRegla()"> Avanzada (ramificada)</label>
        </div>

        <!-- Sección para condiciones simples (AND) -->
        <div id="condiciones_simple" class="form-section condiciones">
            <label>Condiciones (todas deben cumplirse):</label>
            <div id="condiciones_simple_lista"></div>
            <button type="button" onclick="agregarCondicionSimple()">Agregar condición</button>
        </div>

        <!-- Sección para valores permitidos simples -->
        <div id="valores_permitidos_simple" class="form-section valores-permitidos">
            <label>Valores permitidos:</label>
            <div id="valores_permitidos_simple_lista"></div>
            <button type="button" onclick="agregarValorPermitidoSimple()">Agregar valor permitido</button>
        </div>

        <!-- Sección para condiciones ramificadas (branched) -->
        <div id="condiciones_branched" class="form-section branches hidden">
            <label>Ramas de condiciones:</label>
            <div id="branches_lista"></div>
            <button type="button" onclick="agregarRama()">Agregar rama</button>
            <div style="margin-top:1em;">
                <label>Valores permitidos por defecto (si ninguna rama aplica):</label>
                <div id="default_allowed_values"></div>
                <button type="button" onclick="agregarValorPermitidoDefault()">Agregar valor por defecto</button>
            </div>
        </div>

        <!-- Acciones del formulario -->
        <div class="actions">
            <button type="submit">Guardar cambios</button>
            <span id="guardar_msg"></span>
        </div>
    </form>

    <!-- ===========================
         SCRIPTS DE INTERACCIÓN Y LÓGICA
         =========================== -->
    <script>
    /**
     * Al cargar la página, si hay un parámetro 'id' en la URL,
     * se busca la regla correspondiente y se llena el formulario.
     */
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const id = params.get('id');
        if (id) {
            fetch('../backend/modificar_reglas.php?id=' + encodeURIComponent(id))
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.rule) {
                        cargarReglaEnFormulario(data.rule);
                        document.getElementById('form_regla').classList.remove('hidden');
                    } else {
                        alert(data.message || 'No se pudo cargar la regla.');
                    }
                })
                .catch(() => {
                    alert('Error al cargar la regla.');
                });
        }
    });

    /**
     * Muestra u oculta las secciones del formulario según el tipo de regla seleccionado.
     */
    function mostrarTipoRegla() {
        const tipo = document.querySelector('input[name="tipo_regla"]:checked').value;
        document.getElementById('condiciones_simple').classList.toggle('hidden', tipo !== 'AND');
        document.getElementById('valores_permitidos_simple').classList.toggle('hidden', tipo !== 'AND');
        document.getElementById('condiciones_branched').classList.toggle('hidden', tipo !== 'branched');
    }

    /**
     * Carga los datos de una regla en el formulario para edición.
     * @param {Object} rule - Objeto con los datos de la regla.
     */
    function cargarReglaEnFormulario(rule) {
        // Cargar campos básicos
        document.getElementById('id').value = rule.id;
        document.getElementById('nombre_regla').value = rule.nombre_regla;
        document.getElementById('campo_destino').value = rule.campo_destino;
        document.getElementById('es_activa').checked = !!parseInt(rule.es_activa);

        // Determinar tipo de regla (simple o ramificada)
        let tipo = 'AND';
        if (rule.condiciones && rule.condiciones.type === 'branched_conditions') {
            tipo = 'branched';
        }
        document.querySelector('input[name="tipo_regla"][value="' + tipo + '"]').checked = true;
        mostrarTipoRegla();

        // Limpiar listas previas
        document.getElementById('condiciones_simple_lista').innerHTML = '';
        document.getElementById('valores_permitidos_simple_lista').innerHTML = '';
        document.getElementById('branches_lista').innerHTML = '';
        document.getElementById('default_allowed_values').innerHTML = '';

        // Cargar condiciones simples
        if (tipo === 'AND' && rule.condiciones && Array.isArray(rule.condiciones.conditions)) {
            rule.condiciones.conditions.forEach(cond => agregarCondicionSimple(cond.field, cond.value));
        }
        // Cargar valores permitidos simples
        if (tipo === 'AND' && Array.isArray(rule.valores_permitidos)) {
            rule.valores_permitidos.forEach(val => agregarValorPermitidoSimple(val));
        }
        // Cargar ramas (branched)
        if (tipo === 'branched' && rule.condiciones && Array.isArray(rule.condiciones.branches)) {
            rule.condiciones.branches.forEach(branch => agregarRama(branch));
            // Cargar valores por defecto
            if (Array.isArray(rule.condiciones.default_allow)) {
                rule.condiciones.default_allow.forEach(val => agregarValorPermitidoDefault(val));
            }
        }
    }

    // ===========================
    // FUNCIONES PARA AGREGAR/QUITAR CONDICIONES Y VALORES
    // ===========================

    /**
     * Agrega una fila de condición simple al formulario.
     */
    function agregarCondicionSimple(campo = '', valor = '') {
        const cont = document.getElementById('condiciones_simple_lista');
        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" placeholder="Campo" value="${campo}" class="cond-field" required />
            <input type="text" placeholder="Valor" value="${valor}" class="cond-value" required />
            <button type="button" onclick="this.parentNode.remove()">Eliminar</button>
        `;
        cont.appendChild(div);
    }

    /**
     * Agrega una fila de valor permitido simple.
     */
    function agregarValorPermitidoSimple(valor = '') {
        const cont = document.getElementById('valores_permitidos_simple_lista');
        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" placeholder="Valor permitido" value="${valor}" class="allowed-value" required />
            <button type="button" onclick="this.parentNode.remove()">Eliminar</button>
        `;
        cont.appendChild(div);
    }

    /**
     * Agrega una rama (branch) para reglas ramificadas.
     */
    function agregarRama(branch = null) {
        const cont = document.getElementById('branches_lista');
        const div = document.createElement('div');
        div.className = 'branch';
        div.innerHTML = `
            <strong>Rama</strong>
            <div class="branch-conditions"></div>
            <button type="button" onclick="agregarCondicionRama(this)">Agregar condición</button>
            <div class="branch-allowed-values"></div>
            <button type="button" onclick="agregarValorPermitidoRama(this)">Agregar valor permitido</button>
            <button type="button" onclick="this.parentNode.remove()">Eliminar rama</button>
        `;
        cont.appendChild(div);
        // Si se pasa una rama existente, cargar sus condiciones y valores
        if (branch && branch.if && Array.isArray(branch.if.conditions)) {
            branch.if.conditions.forEach(cond => agregarCondicionRama(div.querySelector('.branch-conditions'), cond.field, cond.value));
        }
        if (branch && Array.isArray(branch.then_allow)) {
            branch.then_allow.forEach(val => agregarValorPermitidoRama(div.querySelector('.branch-allowed-values'), val));
        }
    }

    /**
     * Agrega una condición a una rama específica.
     */
    function agregarCondicionRama(btnOrCont, campo = '', valor = '') {
        let cont;
        if (btnOrCont.classList && btnOrCont.classList.contains('branch-conditions')) {
            cont = btnOrCont;
        } else {
            cont = btnOrCont.parentNode.querySelector('.branch-conditions');
        }
        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" placeholder="Campo" value="${campo}" class="cond-field" required />
            <input type="text" placeholder="Valor" value="${valor}" class="cond-value" required />
            <button type="button" onclick="this.parentNode.remove()">Eliminar</button>
        `;
        cont.appendChild(div);
    }

    /**
     * Agrega un valor permitido a una rama específica.
     */
    function agregarValorPermitidoRama(btnOrCont, valor = '') {
        let cont;
        if (btnOrCont.classList && btnOrCont.classList.contains('branch-allowed-values')) {
            cont = btnOrCont;
        } else {
            cont = btnOrCont.parentNode.querySelector('.branch-allowed-values');
        }
        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" placeholder="Valor permitido" value="${valor}" class="allowed-value" required />
            <button type="button" onclick="this.parentNode.remove()">Eliminar</button>
        `;
        cont.appendChild(div);
    }

    /**
     * Agrega un valor permitido por defecto (para reglas ramificadas).
     */
    function agregarValorPermitidoDefault(valor = '') {
        const cont = document.getElementById('default_allowed_values');
        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" placeholder="Valor por defecto" value="${valor}" class="allowed-value" required />
            <button type="button" onclick="this.parentNode.remove()">Eliminar</button>
        `;
        cont.appendChild(div);
    }

    // ===========================
    // GUARDADO DE CAMBIOS
    // ===========================

    /**
     * Recolecta los datos del formulario y los envía al backend para actualizar la regla.
     */
    function guardarCambios(event) {
        event.preventDefault();
        const msg = document.getElementById('guardar_msg');
        msg.textContent = '';

        // Recolectar datos básicos
        const id = document.getElementById('id').value;
        const nombre_regla = document.getElementById('nombre_regla').value;
        const campo_destino = document.getElementById('campo_destino').value;
        const es_activa = document.getElementById('es_activa').checked ? 1 : 0;
        const tipo = document.querySelector('input[name="tipo_regla"]:checked').value;

        let condiciones = {};
        let valores_permitidos = [];

        // Recolectar condiciones y valores según el tipo de regla
        if (tipo === 'AND') {
            // Condiciones simples
            const conds = [];
            document.querySelectorAll('#condiciones_simple_lista > div').forEach(div => {
                const field = div.querySelector('.cond-field').value;
                const value = div.querySelector('.cond-value').value;
                if (field && value) conds.push({ operator: 'condition', field, value });
            });
            condiciones = { operator: 'AND', conditions: conds };
            // Valores permitidos simples
            document.querySelectorAll('#valores_permitidos_simple_lista .allowed-value').forEach(inp => {
                if (inp.value) valores_permitidos.push(inp.value);
            });
        } else if (tipo === 'branched') {
            // Ramas
            const branches = [];
            document.querySelectorAll('#branches_lista .branch').forEach(branchDiv => {
                // Condiciones de la rama
                const conds = [];
                branchDiv.querySelectorAll('.branch-conditions > div').forEach(div => {
                    const field = div.querySelector('.cond-field').value;
                    const value = div.querySelector('.cond-value').value;
                    if (field && value) conds.push({ operator: 'condition', field, value });
                });
                // Valores permitidos de la rama
                const then_allow = [];
                branchDiv.querySelectorAll('.branch-allowed-values .allowed-value').forEach(inp => {
                    if (inp.value) then_allow.push(inp.value);
                });
                branches.push({
                    if: { operator: 'AND', conditions: conds },
                    then_allow
                });
            });
            // Valores por defecto
            const default_allow = [];
            document.querySelectorAll('#default_allowed_values .allowed-value').forEach(inp => {
                if (inp.value) default_allow.push(inp.value);
            });
            condiciones = { type: 'branched_conditions', branches, default_allow };
        }

        // Validación mínima de campos obligatorios
        if (!nombre_regla || !campo_destino) {
            msg.textContent = 'Nombre y campo destino son obligatorios.';
            msg.className = 'error';
            return;
        }

        // Enviar los datos al backend usando AJAX (fetch)
        fetch('../backend/actualizar_regla.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id,
                nombre_regla,
                campo_destino,
                es_activa,
                condiciones,
                valores_permitidos
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                msg.textContent = '¡Regla actualizada correctamente!';
                msg.className = 'success';
            } else {
                msg.textContent = data.message || 'Error al actualizar.';
                msg.className = 'error';
            }
        })
        .catch(() => {
            msg.textContent = 'Error de red al guardar.';
            msg.className = 'error';
        });
    }
    </script>
</body>
</html>
