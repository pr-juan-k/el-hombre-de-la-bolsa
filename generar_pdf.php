<?php
// generar_pdf.php

require_once 'dompdf/autoload.inc.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Capturar parámetros
$categoria = isset($_GET['cat']) ? strtolower(trim($_GET['cat'])) : '';
// El nivel de descuento ahora es el índice de columna (5, 6, 7, etc.)
$nivel_desc_url = isset($_GET['desc']) ? (int)$_GET['desc'] : 5;
$indice_precio = ($nivel_desc_url >= 5) ? $nivel_desc_url : 5;

// 2. Procesar datos del archivo TXT
$archivo_txt = 'ProDesc/productos.txt';
$productos_filtrados = [];

if (file_exists($archivo_txt)) {
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        
        // Verificamos que la línea tenga las columnas necesarias (al menos 13 para rubros y código)
        if (count($datos) >= 13) {
            // AJUSTE: Los rubros están en la columna 12
            $cat_consumidores = strtolower(trim($datos[12]));
            $busqueda = strtolower(trim($categoria));

            // Filtrar producto por rubro
            if (empty($busqueda) || strpos($cat_consumidores, $busqueda) !== false) {
                
                // Lógica de precio dinámica según el índice recibido
                $precio_final = (isset($datos[$indice_precio]) && trim($datos[$indice_precio]) !== '') 
                                ? $datos[$indice_precio] 
                                : $datos[5];

                $productos_filtrados[] = [
                    'descripcion' => $datos[2],
                    'codigo'      => $datos[13] ?? 'N/A', // Capturamos código
                    'cantidad'    => $datos[3],
                    'p_unitario'  => ($precio_final/$datos[3]),
                    'p_total'     => $precio_final
                ];
            }
        }
    }

    // Ordenar alfabéticamente
    usort($productos_filtrados, function($a, $b) {
        return strcasecmp($a['descripcion'], $b['descripcion']);
    });
}

// 3. Crear el diseño HTML completo (Eliminada columna Consumidor)
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: "Helvetica", sans-serif; color: #333; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #667eea; margin-bottom: 20px; padding-bottom: 10px; }
        .header h1 { color: #764ba2; margin: 0; font-size: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #667eea; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background-color: #fcfcfc; }
        
        .col-desc { width: 45%; }
        .col-cod { width: 15%; }
        .col-cant { width: 10%; text-align: center; }
        .col-precio { width: 15%; text-align: right; }
        .col-total { width: 15%; text-align: right; font-weight: bold; color: #d97706; }
        
        .footer { position: fixed; bottom: -20px; left: 0; width: 100%; text-align: center; font-size: 9px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CATÁLOGO EL HOMBRE DE LA BOLSA</h1>
        <p>Rubro: ' . htmlspecialchars(ucfirst($categoria)) . ' | Fecha: ' . date('d/m/Y') . '</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-desc">Descripción</th>
                <th class="col-cod">Código</th>
                <th class="col-cant">Cant.</th>
                <th class="col-precio">P. Unit</th>
                <th class="col-total">P. Total</th>
            </tr>
        </thead>
        <tbody>';

if (empty($productos_filtrados)) {
    $html .= '<tr><td colspan="5" style="text-align:center;">No se encontraron productos en este rubro.</td></tr>';
} else {
    foreach ($productos_filtrados as $prod) {
        $html .= '
            <tr>
                <td>' . htmlspecialchars($prod['descripcion']) . '</td>
                <td>' . htmlspecialchars($prod['codigo']) . '</td>
                <td style="text-align:center;">' . $prod['cantidad'] . '</td>
                <td style="text-align:right;">$' . number_format((float)$prod['p_unitario'], 2, ',', '.') . '</td>
                <td style="text-align:right;">$' . number_format((float)$prod['p_total'], 2, ',', '.') . '</td>
            </tr>';
    }
}

$html .= '
        </tbody>
    </table>
    <div class="footer">Generado automáticamente por EL HOMBRE DE LA BOLSA - ' . date('H:i:s') . '</div>
</body>
</html>';

// 4. Configuración de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 5. Salida del archivo
$nombre_archivo = "Catalogo_" . ($categoria ?: 'General') . ".pdf";
$dompdf->stream($nombre_archivo, ["Attachment" => true]);
exit;