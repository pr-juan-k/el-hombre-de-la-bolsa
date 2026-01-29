<?php
// buscar_productos.php
$query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

// IMPORTANTE: Aquí aceptamos tanto 'indice' como 'desc' por si acaso
$indice_precio = isset($_GET['indice']) ? (int)$_GET['indice'] : (isset($_GET['desc']) ? (int)$_GET['desc'] : 5);

// Si el índice es menor a 5 (por error), usamos 5
if ($indice_precio < 5) { $indice_precio = 5; }

$ruta_base = 'ProDesc/';
$ruta_fotos = $ruta_base . 'fotop/';
$archivo_txt = $ruta_base . 'productos.txt';

if (empty($query)) exit;

$resultados = [];

if (file_exists($archivo_txt)) {
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        
        if (isset($datos[2])) { // Verificar que al menos existe la descripción
            $descripcion = strtolower($datos[2]);
            // Obtenemos el código de la posición 13. Si no existe, queda vacío.
            $codigo_producto = isset($datos[13]) ? strtolower(trim($datos[13])) : '';
    
            // --- LA CLAVE ESTÁ AQUÍ ---
            // Comprobamos si la búsqueda ($query) está en la descripción O en el código
            if (strpos($descripcion, $query) !== false || ($codigo_producto !== '' && strpos($codigo_producto, $query) !== false)) {
                
                // Determinamos el precio según el índice/descuento enviado
                $precio_final = (isset($datos[$indice_precio]) && $datos[$indice_precio] !== '') 
                                ? $datos[$indice_precio] 
                                : $datos[5];
    
                $resultados[] = [
                    'foto'        => $datos[1] ?? '',
                    'descripcion' => $datos[2],
                    'codigo'      => $datos[13] ?? 'N/A',
                    'cantidad'    => $datos[3] ?? '0',
                    'p_unitario'  => $datos[4] ?? '0',
                    'p_total'     => $precio_final
                ];
            }
        }
    }
}

// Generar HTML de la tabla
if (!empty($resultados)) {
    foreach ($resultados as $prod) {
        $estilo_precio = ($indice_precio > 5) ? "color: #059669; font-weight: bold;" : "";
        echo "<tr>";
        // Agregamos data-label a cada celda
        echo "<td data-label='Foto'><img src='{$ruta_fotos}{$prod['foto']}' width='50' style='object-fit: cover; border-radius: 4px;'></td>";
        echo "<td data-label='Descripción'>" . htmlspecialchars($prod['descripcion']) . "</td>";
        echo "<td data-label='Codigo'>" . htmlspecialchars($prod['codigo']) . "</td>";
        echo "<td data-label='Cantidad'>" . htmlspecialchars($prod['cantidad']) . "</td>";
        echo "<td data-label='Precio Unitario'>$" . number_format((float)$prod['p_unitario'], 2, ',', '.') . "</td>";
        echo "<td data-label='Total' style='{$estilo_precio}'>$" . number_format((float)$prod['p_total'], 2, ',', '.') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' style='text-align: center;'>Producto no Existente</td></tr>";
}