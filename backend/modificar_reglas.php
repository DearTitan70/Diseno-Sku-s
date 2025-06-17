
<?php
// --- Establece el tipo de contenido de la respuesta como JSON ---
header('Content-Type: application/json');

// --- INCLUYE LA CONFIGURACIÓN DE CONEXIÓN A LA BASE DE DATOS ---
// Este archivo debe definir las variables $host, $db, $user, $pass
include '../conexion.php';

// --- CONFIGURA EL CHARSET Y OPCIONES DE PDO ---
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los resultados como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa sentencias preparadas nativas
];

// --- VALIDACIÓN DEL PARÁMETRO DE ENTRADA ---
// Verifica que se haya recibido el parámetro 'id' y que sea numérico
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Falta el parámetro rule_id o no es válido.'
    ]);
    exit;
}

// --- CONVIERTE EL PARÁMETRO 'id' A ENTERO ---
$id = intval($_GET['id']);

try {
    // --- CREA UNA NUEVA CONEXIÓN PDO ---
    $pdo = new PDO($dsn, $user, $pass, $options);

    // --- PREPARA Y EJECUTA LA CONSULTA PARA OBTENER LA REGLA ---
    // Cambia 'reglas_dependencia' por el nombre real de tu tabla si es necesario
    $stmt = $pdo->prepare("SELECT * FROM reglas_dependencia WHERE id = ?");
    $stmt->execute([$id]);
    $regla = $stmt->fetch();

    // --- VERIFICA SI SE ENCONTRÓ LA REGLA ---
    if (!$regla) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró la regla solicitada.'
        ]);
        exit;
    }

    // --- DECODIFICA LOS CAMPOS JSON DE LA REGLA ---
    // 'condiciones' y 'valores_permitidos' se almacenan como JSON en la base de datos
    $condiciones = [];
    if (!empty($regla['condiciones'])) {
        $condiciones = json_decode($regla['condiciones'], true);
    }
    $valores_permitidos = [];
    if (!empty($regla['valores_permitidos'])) {
        $valores_permitidos = json_decode($regla['valores_permitidos'], true);
    }

    // --- ARMA LA RESPUESTA EN FORMATO JSON ---
    $respuesta = [
        'success' => true,
        'rule' => [
            'id'                => $regla['id'],
            'nombre_regla'      => $regla['nombre_regla'],
            'campo_destino'     => $regla['campo_destino'],
            'es_activa'         => $regla['es_activa'],
            'condiciones'       => $condiciones,
            'valores_permitidos'=> $valores_permitidos
        ]
    ];

    // --- ENVÍA LA RESPUESTA AL CLIENTE ---
    echo json_encode($respuesta);

} catch (PDOException $e) {
    // --- MANEJO DE ERRORES DE BASE DE DATOS ---
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
    exit;
}
?>
