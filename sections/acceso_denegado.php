
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <link rel="icon" type="image/x-icon" href="../img/FDS_Favicon.png">
    <style>
        :root {
            --color-background: #F9F3E5;
            --color-text-dark: #000000;
            --color-primary: #879683;
            --color-secondary: #5A6B58;
            --color-highlight: #C5D4C1;
            --color-logout: #a0a0a0;
            --color-logout-hover: #8a8a8a;
            --color-error: #e74c3c;
            --color-table-header: #879683;
            --color-table-border: #C5D4C1;
            --color-row-even: #f2f2f2;
            --color-delete-button: #c0392b;
            --color-delete-button-hover: #e74c3c;
            --color-white: #ffffff;
            --color-shadow: rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--color-background);
            color: var(--color-text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(var(--color-highlight) 1px, transparent 1px),
                radial-gradient(var(--color-highlight) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
            opacity: 0.3;
            z-index: -1;
        }

        .container {
            background-color: var(--color-white);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px var(--color-shadow);
            padding: 3rem;
            width: 90%;
            max-width: 500px;
            text-align: center;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards;
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background-color: var(--color-highlight);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: pulse 2s infinite;
        }

        .icon svg {
            width: 40px;
            height: 40px;
            fill: var(--color-secondary);
        }

        h1 {
            color: var(--color-error);
            margin-bottom: 1rem;
            font-size: 2rem;
            position: relative;
            display: inline-block;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--color-highlight);
            border-radius: 3px;
        }

        p {
            margin-bottom: 2rem;
            line-height: 1.6;
            color: var(--color-text-dark);
            opacity: 0;
            animation: slideUp 0.6s ease-out 0.4s forwards;
        }

        .btn {
            display: inline-block;
            background-color: var(--color-primary);
            color: var(--color-white);
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition-speed);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: slideUp 0.6s ease-out 0.6s forwards;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: all 0.6s;
        }

        .btn:hover {
            background-color: var(--color-secondary);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:active {
            transform: translateY(-1px);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(197, 212, 193, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(197, 212, 193, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(197, 212, 193, 0);
            }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>
    <div class="container">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
        </div>
        <h1>Acceso Denegado</h1>
        <p>Lo sentimos, no tienes los permisos necesarios para acceder a esta página. Por favor, contacta con el administrador si crees que esto es un error.</p>
        <a href="index.php" class="btn">Volver al menú principal</a>
    </div>

    <script>
        // Pequeña animación adicional para el botón
        document.querySelector('.btn').addEventListener('mouseover', function() {
            this.style.transition = 'all 0.3s';
        });
        
        // Efecto de rebote suave al cargar la página
        window.addEventListener('load', function() {
            const container = document.querySelector('.container');
            container.style.animation = 'none';
            container.offsetHeight; // Forzar reflow
            container.style.animation = 'fadeIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';
        });
    </script>
</body>
</html>
