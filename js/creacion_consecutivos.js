// Solo mostrar el botón si el usuario es admin (puedes controlar esto desde PHP)
document.addEventListener('DOMContentLoaded', function() {
    // Agrega estilos CSS para el modal y componentes
    const styles = document.createElement('style');
    styles.textContent = `
        :root {
            --color-background: #F9F3E5;
            --color-text-dark: #000000;
            --color-primary: #879683;
            --color-secondary: #5A6B58;
            --color-highlight: #C5D4C1;
            --color-logout: #a0a0a0;
            --color-logout-hover: #8a8a8a;
            --shadow-soft: 0 8px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.12);
            --border-radius: 12px;
            --transition-standard: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .btn-consecutivos {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition-standard);
            box-shadow: var(--shadow-soft);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-consecutivos:hover {
            background-color: var(--color-secondary);
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }
        
        .btn-consecutivos svg {
            width: 18px;
            height: 18px;
        }
        
        #modal-asignar-consecutivos {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(3px);
        }
        
        #modal-asignar-consecutivos.visible {
            opacity: 1;
        }
        
        .modal-content {
            background: var(--color-background);
            max-width: 450px;
            width: 90%;
            margin: 100px auto;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-hover);
            position: relative;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.4s ease;
        }
        
        #modal-asignar-consecutivos.visible .modal-content {
            transform: translateY(0);
            opacity: 1;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--color-highlight);
        }
        
        .modal-header h2 {
            color: var(--color-secondary);
            margin: 0;
            font-size: 1.5rem;
        }
        
        .close-btn {
            background: transparent;
            border: none;
            color: var(--color-logout);
            font-size: 24px;
            cursor: pointer;
            transition: var(--transition-standard);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .close-btn:hover {
            color: var(--color-logout-hover);
            background-color: rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--color-text-dark);
            font-weight: 500;
        }
        
        .form-group input {
            width: calc(100% - 24px); /* Ajustado para dar más espacio */
            padding: 12px;
            border: 1px solid var(--color-highlight);
            border-radius: var(--border-radius);
            background-color: white;
            transition: var(--transition-standard);
            font-size: 16px;
            max-width: 300px; /* Limitar el ancho máximo */
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        
        .btn-cancel {
            background-color: transparent;
            color: var(--color-text-dark);
            border: 1px solid var(--color-logout);
            padding: 10px 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition-standard);
        }
        
        .btn-cancel:hover {
            background-color: var(--color-logout);
            color: white;
        }
        
        .btn-submit {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition-standard);
            font-weight: 500;
        }
        
        .btn-submit:hover {
            background-color: var(--color-secondary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }
        
        #asignar-consecutivos-mensaje {
            margin-top: 16px;
            padding: 12px;
            border-radius: var(--border-radius);
            font-weight: 500;
            display: none;
        }
        
        #asignar-consecutivos-mensaje.success {
            background-color: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
            border: 1px solid rgba(76, 175, 80, 0.3);
            display: block;
        }
        
        #asignar-consecutivos-mensaje.error {
            background-color: rgba(244, 67, 54, 0.1);
            color: #d32f2f;
            border: 1px solid rgba(244, 67, 54, 0.3);
            display: block;
        }
        
        #asignar-consecutivos-mensaje.loading {
            background-color: rgba(33, 150, 243, 0.1);
            color: #1976d2;
            border: 1px solid rgba(33, 150, 243, 0.3);
            display: block;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(33, 150, 243, 0.3);
            border-radius: 50%;
            border-top-color: #1976d2;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(styles);

    // Agrega el botón al DOM con icono
    const btn = document.createElement('button');
    btn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
            <path d="M12 11v6"></path>
            <path d="M9 14h6"></path>
        </svg>
        Asignación de consecutivos
    `;
    btn.className = 'btn-consecutivos';
    btn.onclick = mostrarModalAsignacion;
    document.querySelector('.header').appendChild(btn);

    // Modal HTML con estructura mejorada
    const modal = document.createElement('div');
    modal.id = 'modal-asignar-consecutivos';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Asignar consecutivos SAP</h2>
                <button type="button" class="close-btn" id="cerrar-modal-x">&times;</button>
            </div>
            <form id="form-asignar-consecutivos">
                <div class="form-group">
                    <label for="id_inicio">ID inicio</label>
                    <input type="number" id="id_inicio" name="id_inicio" required min="1">
                </div>
                <div class="form-group">
                    <label for="id_fin">ID fin</label>
                    <input type="number" id="id_fin" name="id_fin" required min="1">
                </div>
                <div class="form-group">
                    <label for="sap_inicial">Primer SAP</label>
                    <input type="number" id="sap_inicial" name="sap_inicial" required min="1">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" id="cerrar-modal">Cancelar</button>
                    <button type="submit" class="btn-submit">Asignar</button>
                </div>
            </form>
            <div id="asignar-consecutivos-mensaje"></div>
        </div>
    `;
    document.body.appendChild(modal);

    function mostrarModalAsignacion() {
        modal.style.display = 'block';
        // Pequeño retraso para permitir que la transición funcione
        setTimeout(() => {
            modal.classList.add('visible');
        }, 10);
        
        // Limpiar el formulario y mensajes
        document.getElementById('form-asignar-consecutivos').reset();
        const mensajeDiv = document.getElementById('asignar-consecutivos-mensaje');
        mensajeDiv.className = '';
        mensajeDiv.textContent = '';
        mensajeDiv.style.display = 'none';
    }

    function cerrarModal() {
        modal.classList.remove('visible');
        // Esperar a que termine la animación antes de ocultar
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    document.getElementById('cerrar-modal').onclick = cerrarModal;
    document.getElementById('cerrar-modal-x').onclick = cerrarModal;
    
    // Cerrar modal al hacer clic fuera del contenido
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            cerrarModal();
        }
    });

    document.getElementById('form-asignar-consecutivos').onsubmit = async function(e) {
        e.preventDefault();
        const form = e.target;
        const id_inicio = form.id_inicio.value;
        const id_fin = form.id_fin.value;
        const sap_inicial = form.sap_inicial.value;
        const mensajeDiv = document.getElementById('asignar-consecutivos-mensaje');
        
        // Mostrar mensaje de carga
        mensajeDiv.className = 'loading';
        mensajeDiv.innerHTML = '<div class="loading-spinner"></div> Procesando...';

        try {
            const resp = await fetch('../backend/asignar_consecutivos.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id_inicio, id_fin, sap_inicial})
            });
            const data = await resp.json();
            
            if (data.success) {
                mensajeDiv.className = 'success';
                mensajeDiv.innerHTML = '✓ ¡Consecutivos asignados correctamente!';
                setTimeout(() => { 
                    cerrarModal(); 
                    location.reload(); 
                }, 1500);
            } else if (data.existentes) {
                mensajeDiv.className = 'error';
                mensajeDiv.innerHTML = '✕ Error: Los siguientes SAP ya existen: ' + data.existentes.join(', ');
            } else {
                mensajeDiv.className = 'error';
                mensajeDiv.innerHTML = '✕ Error: ' + (data.error || 'Error desconocido');
            }
        } catch (err) {
            mensajeDiv.className = 'error';
            mensajeDiv.innerHTML = '✕ Error de red o servidor';
        }
    };
});