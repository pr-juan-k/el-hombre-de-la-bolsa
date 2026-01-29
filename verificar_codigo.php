<?php
header('Content-Type: application/json');

$codigo_ingresado = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
$archivo_usuarios = 'UserEspe/UserRegistrados.txt'; 

if (empty($codigo_ingresado)) {
    echo json_encode(['success' => false, 'message' => 'Código vacío']);
    exit;
}

// Mapeo de los "values" a los índices de columna en productos.txt
$mapeo_precios = [
    'Consumidor_final'             => 5,
    'r0'             => 6,
    'r10'                 => 7,
    'r50'                 => 8,
    'r100'                => 9,
    '33'           => 10
];

if (file_exists($archivo_usuarios)) {
    $lineas = file($archivo_usuarios, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        // Suponiendo que $datos[3] es el código/tipo que el usuario ingresa
        if ($datos[3] === $codigo_ingresado) {
            
            $tipo_cliente = $datos[3];
            $indice_columna = isset($mapeo_precios[$tipo_cliente]) ? $mapeo_precios[$tipo_cliente] : 5;

            echo json_encode([
                'success' => true,
                'nombre' => $datos[1], // Nombre del usuario
                'indice' => $indice_columna, // Devolvemos 5, 6, 7, 8, 9 o 10
                'tipo'   => str_replace('_', ' ', $tipo_cliente)
            ]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Código no válido']);