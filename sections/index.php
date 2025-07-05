
<?php
// Inicia la sesión para poder acceder a las variables de sesión
session_start();

// Verifica si el usuario ha iniciado sesión
$isLoggedIn = isset($_SESSION['user_id']);

// Inicializa variables para el nombre y rol del usuario
$userName = '';
if ($isLoggedIn) {
    // Obtiene el nombre y el rol del usuario desde la sesión, con valores por defecto
    $userName = $_SESSION['nombre'] ?? 'Usuario';
    $userRoleName = $_SESSION['user_role_name'] ?? 'Invitado';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Sistema de creacion SKU's</title>
    <style>
        /* 
            Definición de la paleta de colores y variables CSS para mantener la consistencia 
            y facilitar cambios de diseño en el futuro
        */
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

        /* 
            Estilos generales para el body, incluyendo fuente, fondo y alineación 
            para centrar el contenido principal vertical y horizontalmente
        */
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background-attachment: fixed;
        }

        /* 
            Contenedor principal del menú, con fondo blanco, bordes redondeados y sombra 
            para dar un efecto de tarjeta flotante
        */
        .menu_container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            max-width: 700px;
            width: 90%;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out forwards;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* 
            Efecto decorativo en la parte superior del contenedor 
            usando un gradiente de color
        */
        .menu_container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-highlight));
        }

        /* 
            Contenedor para el nombre y mensajes de bienvenida 
        */
        .name_container {
            margin-bottom: 30px;
            position: relative;
        }

        /* 
            Título principal con color destacado y subrayado decorativo
        */
        .name_container h1 {
            color: var(--color-primary);
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 2.5em;
            font-weight: 600;
            letter-spacing: -0.5px;
            position: relative;
            display: inline-block;
        }

        .name_container h1::after {
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

        /* 
            Mensajes secundarios debajo del título principal
        */
        .name_container p {
            color: var(--color-text-dark);
            margin-bottom: 8px;
            font-size: 1.1em;
            opacity: 0.85;
        }

        .name_container p:last-of-type {
            font-weight: 500;
            margin-top: 20px;
            color: var(--color-secondary);
        }

        /* 
            Contenedor de las opciones del menú, usando grid para responsividad
        */
        .options_container {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        /* 
            Estilos para los enlaces de opciones, con animación al pasar el mouse
        */
        .options_container a {
            text-decoration: none;
            display: block;
            width: 100%;
            transform: translateY(0);
            transition: var(--transition-standard);
        }

        .options_container a:hover {
            transform: translateY(-5px);
        }

        /* 
            Estilos para los botones de opción del menú
        */
        .option {
            display: inline-block;
            padding: 14px 24px;
            font-size: 1.05em;
            font-weight: 500;
            color: #ffffff;
            background-color: var(--color-primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition-standard);
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        /* 
            Efecto de brillo al pasar el mouse sobre los botones
        */
        .option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .option:hover {
            background-color: var(--color-secondary);
            box-shadow: var(--shadow-hover);
        }

        .option:hover::before {
            left: 100%;
        }

        .option:active {
            transform: scale(0.98);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* 
            Estilo específico para el botón de cerrar sesión 
        */
        .option[style*="background-color: gray;"] {
            background-color: var(--color-logout) !important;
            color: #ffffff;
        }

        .option[style*="background-color: gray;"]:hover {
            background-color: var(--color-logout-hover) !important;
        }

        /* 
            Estilo para el enlace "Iniciar Sesión" cuando el usuario no está logueado 
        */
        .menu_container > a.option {
            display: inline-block;
            margin-top: 25px;
            padding: 14px 40px;
            font-weight: 500;
            letter-spacing: 0.5px;
            animation: pulseButton 2s infinite;
        }

        /* 
            Animaciones para la entrada de los elementos y el botón de inicio de sesión
        */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseButton {
            0% {
                box-shadow: 0 0 0 0 rgba(135, 150, 131, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(135, 150, 131, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(135, 150, 131, 0);
            }
        }

        .name_container h1 {
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        .name_container p {
            animation: fadeIn 0.8s ease-out 0.5s both;
        }

        .options_container {
            animation: fadeIn 0.8s ease-out 0.7s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 
            Ajustes responsivos para pantallas pequeñas
        */
        @media (max-width: 768px) {
            .menu_container {
                padding: 30px 20px;
                width: 95%;
            }

            .name_container h1 {
                font-size: 2em;
            }

            .options_container {
                grid-template-columns: 1fr;
            }
        }

        /* 
            Fondo sutil con patrón cuadriculado para dar textura visual
        */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(201, 214, 197, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201, 214, 197, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            z-index: -1;
        }
        
        img {
            height: 150px;
        }
    </style>
</head>
<body>
    <div class="img-container">
        
    </div>

    <div class="menu_container">

    <?php if ($isLoggedIn): ?>
        <!-- 
            Si el usuario ha iniciado sesión, muestra su nombre, rol y las opciones disponibles 
            según su rol (admin/editor)
        -->
        <div class="name_container">
    <h1>Bienvenido <?php echo htmlspecialchars($userName); ?></h1>
    <p>
        Bienvenido al Sistema de Creación de SKU’s.<br>
        Aquí podrás gestionar usuarios, cargar productos manualmente, administrar reglas y visualizar el historial de cambios de manera sencilla y segura.<br>
        Utiliza el menú para acceder a las diferentes funciones según tu perfil.
    </p>
    <p style="background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; padding: 12px 18px; color: #856404; margin: 18px 0; font-size: 1em;">
        <strong>¡Atención!</strong> Por favor, asegúrate de revisar cuidadosamente la información que ingresas en la plataforma. Es fundamental concentrarse y evitar distracciones al realizar cargas o modificaciones, ya que cualquier error podría afectar la integridad de los datos. Si tienes dudas, consulta la documentación o solicita ayuda antes de continuar.
    </p>
    <p>Has iniciado sesión como: <?php echo htmlspecialchars($userRoleName); ?></p>
    <p>Selecciona una de las siguientes opciones:</p>
</div>

        <div class="options_container">

            <?php $userRole = $userRoleName;?>

            <?php if ($userRole === 'admin' || $userRole === 'editor'): ?>
                <!-- Opción disponible para administradores y editores -->
                <a href="carga_manual.php">
                    <button class="option">Carga Manual</button>
                </a>
                <a href="visualizar_reglas.php">
                    <button class="option">Gestion de reglas</button>
                </a>
            <?php endif;?>

            <!-- 
                Opción disponible para todos los usuarios logueados 
                (admin, editor, otros roles)
            -->
            <a href="visualizar_cargas.php">
                <button class="option">Visualizar Cargas</button>
            </a>
            <!-- Botón para cambiar contraseña -->
            <a href="cambiar_contrasena.php">
                <button class="option">Cambiar contraseña</button>
            </a>
            <!-- Botón para cerrar sesión -->
            <a href="../backend/logout.php">
                <button class="option" style="background-color: rgb(250, 89, 89);">Cerrar Sesión</button>
            </a>
            <a href="visualizar_bitacoras.php">
                <button class="option">Historico de cambios</button>
            </a>

        </div>

    <?php else: ?>
        <!-- 
            Si el usuario NO ha iniciado sesión, muestra mensaje de bienvenida 
            y opción para iniciar sesión
        -->
        <div class="name_container">
            <h1>Sistema de creacion de SKU's</h1>
        </div>
        <p>Por favor, inicia sesión para acceder a las opciones.</p>
        <a href="login.php" class="option">Iniciar Sesión</a>

    <?php endif; ?>

    </div>

</body>
</html>
