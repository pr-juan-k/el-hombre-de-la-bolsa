<?php
// generar_csv.php

// 1. Capturar parámetros
$categoria = isset($_GET['cat']) ? strtolower(trim($_GET['cat'])) : 'general';
$nivel_descuento = isset($_GET['desc']) ? (int)$_GET['desc'] : 0;

// 2. Definir rutas
$archivo_txt = 'ProDesc/productos.txt';

// 3. Configurar cabeceras para la descarga del navegador
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $categoria . '.csv"');

// 4. Abrir la salida de PHP
$salida = fopen('php://output', 'w');

// Añadir el BOM UTF-8 para que Excel reconozca tildes y eñes correctamente
fprintf($salida, chr(0xEF).chr(0xBB).chr(0xBF));

// 5. Definir los títulos de las columnas del CSV
fputcsv($salida, ['Descripción', 'Cantidad', 'Precio Unitario', 'Precio Total'], ';');

// 6. Procesar el archivo productos.txt (misma lógica que usas en la vista)
if (file_exists($archivo_txt)) {
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $productos_para_csv = [];

    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        
        if (count($datos) >= 11) {
            $cat_consumidores = strtolower($datos[10]);

            // Filtrar por rubro
            if (empty($categoria) || strpos($cat_consumidores, $categoria) !== false) {
                
                // Aplicar lógica de descuento
                $precio_final = $datos[5]; 
                if ($nivel_descuento === 1) $precio_final = $datos[6];
                elseif ($nivel_descuento === 2) $precio_final = $datos[7];
                elseif ($nivel_descuento === 3) $precio_final = $datos[8];

                $productos_para_csv[] = [
                    'descripcion' => $datos[2],
                    'cantidad'    => $datos[3],
                    'p_unitario'  => $datos[4],
                    'p_total'     => $precio_final
                ];
            }
        }
    }

    // Ordenar alfabéticamente
    usort($productos_para_csv, function($a, $b) {
        return strcasecmp($a['descripcion'], $b['descripcion']);
    });

    // 7. Escribir las filas en el CSV
    foreach ($productos_para_csv as $prod) {
        fputcsv($salida, [
            $prod['descripcion'],
            $prod['cantidad'],
            "$" . number_format((float)$prod['p_unitario'], 2, ',', '.'),
            "$" . number_format((float)$prod['p_total'], 2, ',', '.')
        ], ';');
    }
}

fclose($salida);
exit;