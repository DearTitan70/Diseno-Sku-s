<?php
// auth.php

/**
 * Valida si el usuario ha iniciado sesión y si tiene el rol requerido.
 * Si no ha iniciado sesión, redirige a login.php.
 * Si no tiene el rol requerido, redirige a una página de acceso denegado o muestra error.
 *
 * @param array|int|null $rolesPermitidos Un solo rol o array de roles permitidos (1=admin, 2=editor, 3=visualización)
 * @param string $redirect Si no cumple, a dónde redirigir (por defecto: login.php)
 */
function require_login_and_role($rolesPermitidos = null, $redirect = "../sections/login.php") {
    session_start();

    // Validar sesión iniciada
    if (!isset($_SESSION['user_id'])) {
        header("Location: $redirect");
        exit;
    }

    // Si no se requiere validar rol, salir
    if ($rolesPermitidos === null) {
        return;
    }

    // Obtener el rol del usuario desde la sesión
    // Se asume que el rol está guardado como 'user_role' (numérico: 1, 2, 3)
    $userRole = $_SESSION['user_role_id'] ?? null;

    // Permitir un solo rol o varios
    if (!is_array($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    if (!in_array($userRole, $rolesPermitidos)) {
        // Puedes redirigir a una página de acceso denegado o mostrar mensaje
        header("Location: acceso_denegado.php");
        exit;
    }
}
?>