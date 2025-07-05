
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        /* ===========================
           Variables de color y estilos globales
           =========================== */
        :root {
            --color-background: #F9F3E5; /* Fondo claro y elegante */
            --color-text-dark: #000000; /* Texto oscuro principal */
            --color-primary: #879683; /* Verde/Gris principal, para elementos interactivos */
            --color-secondary: #5A6B58; /* Un tono más oscuro del primario, para hover/activos */
            --color-highlight: #C5D4C1; /* Un tono más claro, para bordes o detalles */
            --color-logout: #a0a0a0; /* Gris para el botón de cerrar sesión */
            --color-logout-hover: #8a8a8a; /* Gris más oscuro para hover de cerrar sesión */
            --color-error: #e74c3c; /* Color para mensajes de error */
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition-standard: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* ===========================
           Animaciones para transiciones suaves
           =========================== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(135, 150, 131, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(135, 150, 131, 0); }
            100% { box-shadow: 0 0 0 0 rgba(135, 150, 131, 0); }
        }

        /* ===========================
           Estilos generales del body
           =========================== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-background);
            background-image: linear-gradient(135deg, var(--color-background) 0%, #ffffff 100%);
            color: var(--color-text-dark);
            margin: 0;
            padding: 0;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Fondo decorativo con gradiente radial */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, var(--color-highlight) 0%, transparent 70%);
            opacity: 0.4;
            z-index: -1;
        }

        /* ===========================
           Contenedor principal del login
           =========================== */
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--box-shadow);
            max-width: 420px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
            position: relative;
            overflow: hidden;
            transform: translateZ(0); /* Mejora el rendimiento de animaciones */
        }

        /* Barra decorativa superior en el contenedor */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-highlight), var(--color-secondary));
            z-index: 1;
        }

        /* ===========================
           Título del formulario
           =========================== */
        .login-container h2 {
            color: var(--color-primary);
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 2.2em;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            display: inline-block;
            animation: slideIn 0.5s ease-out 0.3s both;
        }

        /* Línea decorativa bajo el título */
        .login-container h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--color-highlight);
            border-radius: 3px;
        }

        /* ===========================
           Mensaje de error (si aplica)
           =========================== */
        .error-message {
            color: var(--color-error);
            margin-bottom: 20px;
            font-weight: 500;
            padding: 10px 15px;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 5px;
            border-left: 4px solid var(--color-error);
            text-align: left;
            animation: fadeIn 0.3s ease-out;
        }

        /* ===========================
           Grupos de campos del formulario
           =========================== */
        .form-group {
            margin-bottom: 25px;
            text-align: left;
            position: relative;
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        /* Animaciones escalonadas para los campos */
        .form-group:nth-child(1) {
            animation-delay: 0.4s;
        }
        .form-group:nth-child(2) {
            animation-delay: 0.6s;
        }

        /* Etiquetas de los campos */
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--color-text-dark);
            font-size: 0.95em;
            transition: var(--transition-standard);
            transform-origin: left;
        }

        /* Campos de entrada de texto y contraseña */
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--color-highlight);
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: var(--transition-standard);
            background-color: rgba(255, 255, 255, 0.9);
        }

        /* Efecto al enfocar los campos */
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(135, 150, 131, 0.2);
            outline: none;
            background-color: #ffffff;
        }

        /* Cambia el color de la etiqueta al enfocar el campo */
        .form-group input[type="text"]:focus + label,
        .form-group input[type="password"]:focus + label {
            color: var(--color-primary);
        }

        /* ===========================
           Botón de envío del formulario
           =========================== */
        button[type="submit"] {
            display: block;
            padding: 14px 25px;
            font-size: 1.1em;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition-standard);
            margin: 30px auto 10px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.5s ease-out 0.8s both;
        }

        /* Efecto de brillo al pasar el mouse */
        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition-standard);
        }

        /* Efecto hover en el botón */
        button[type="submit"]:hover {
            background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary) 100%);
            transform: translateY(-3px);
            box-shadow: 0 7px 14px rgba(90, 107, 88, 0.2);
        }

        /* Efecto de animación al pasar el mouse */
        button[type="submit"]:hover::before {
            left: 100%;
            transition: 0.7s;
        }

        /* Efecto al presionar el botón */
        button[type="submit"]:active {
            transform: translateY(0);
            box-shadow: 0 3px 8px rgba(90, 107, 88, 0.3);
        }

        /* Efecto de pulso al enfocar el botón */
        button[type="submit"]:focus {
            animation: pulse 1.5s infinite;
        }

        /* ===========================
           Adaptabilidad para pantallas pequeñas
           =========================== */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 0 15px;
            }
            .login-container h2 {
                font-size: 1.8em;
            }
            button[type="submit"] {
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Contenedor principal del formulario de inicio de sesión -->
    <div class="login-container">
        <!-- Título del formulario -->
        <h2>Iniciar Sesión</h2>
        <!-- Formulario de login que envía los datos a login_process.php -->
        <form action="../backend/login_process.php" method="post">
            <!-- Campo para el nombre de usuario -->
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <!-- Campo para la contraseña -->
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <!-- Botón para enviar el formulario -->
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
