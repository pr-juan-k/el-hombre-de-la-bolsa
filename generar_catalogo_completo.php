<?php
require_once 'dompdf/autoload.inc.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Capturar el nivel de descuento (índice 6, 7, 8 o 9)
$nivel_desc_url = isset($_GET['desc']) ? (int)$_GET['desc'] : 5;
$indice_precio = ($nivel_desc_url >= 5 && $nivel_desc_url <= 10) ? $nivel_desc_url : 5;

// 2. Procesar datos del archivo TXT
$archivo_txt = 'ProDesc/productos.txt';
$productos_por_rubro = [];

if (file_exists($archivo_txt)) {
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        
        if (count($datos) >= 13) {
            // Limpiar y separar los rubros (ej: "Supers, Rotiseria" -> ["Supers", "Rotiseria"])
            $rubros_string = trim($datos[12]);
            $lista_rubros = array_map('trim', explode(',', $rubros_string));

            // Precio dinámico
            $precio_final = (isset($datos[$indice_precio]) && trim($datos[$indice_precio]) !== '') 
                            ? (float)$datos[$indice_precio] 
                            : (float)$datos[5];

            $item = [
                'descripcion' => $datos[2],
                'codigo'      => $datos[13] ?? 'N/A',
                'cantidad'    => $datos[3],
                'p_unitario'  => ($precio_final / (float)$datos[3]),
                'p_total'     => $precio_final
            ];

            // Organizar en el array multidimensional
            foreach ($lista_rubros as $rubro) {
                if (empty($rubro)) $rubro = "Sin Categoría";
                $productos_por_rubro[$rubro][] = $item;
            }
        }
    }

    // Ordenar los rubros alfabéticamente
    ksort($productos_por_rubro);

    // Ordenar productos dentro de cada rubro
    foreach ($productos_por_rubro as $key => $val) {
        usort($productos_por_rubro[$key], function($a, $b) {
            return strcasecmp($a['descripcion'], $b['descripcion']);
        });
    }
}

// 3. Diseño HTML profesional
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.5cm 1cm; }
        body { font-family: "Helvetica", sans-serif; color: #333; font-size: 10px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #764ba2; padding-bottom: 10px; }
        .header h1 { color: #764ba2; margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; font-size: 12px; }
        
        .rubro-header { 
            background-color: #f3f4f6; 
            padding: 8px 12px; 
            margin-top: 25px; 
            border-left: 5px solid #667eea;
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            page-break-after: avoid;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th { background-color: #667eea; color: white; padding: 7px; text-align: left; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #eee; word-wrap: break-word; }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .col-desc { width: 40%; }
        .col-cod { width: 15%; }
        .col-cant { width: 10%; text-align: center; }
        .col-precio { width: 17%; text-align: right; }
        .col-total { width: 18%; text-align: right; font-weight: bold; color: #d97706; }
        
        .footer { position: fixed; bottom: -30px; left: 0; width: 100%; text-align: right; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>Catálogo General de Productos</h1>
        <p>EL HOMBRE DE LA BOLSA | Lista de Precios Revendedor</p>
        <p>Fecha de emisión: ' . date('d/m/Y H:i') . '</p>
    </div>';

foreach ($productos_por_rubro as $rubro => $productos) {
    $html .= '<div class="rubro-header">' . htmlspecialchars(strtoupper($rubro)) . '</div>';
    $html .= '<table>
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
    
    foreach ($productos as $prod) {
        $html .= '
            <tr>
                <td>' . htmlspecialchars($prod['descripcion']) . '</td>
                <td>' . htmlspecialchars($prod['codigo']) . '</td>
                <td style="text-align:center;">' . $prod['cantidad'] . '</td>
                <td style="text-align:right;">$' . number_format($prod['p_unitario'], 2, ',', '.') . '</td>
                <td style="text-align:right;">$' . number_format($prod['p_total'], 2, ',', '.') . '</td>
            </tr>';
    }
    
    $html .= '</tbody></table>';
}

$html .= '
    <div class="footer">
        Página <span class="page-number"></span> | Generado por El Hombre de la Bolsa
    </div>
</body>
</html>';

// 4. Configuración y Renderizado
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 5. Descarga
$dompdf->stream("Catalogo_Completo_Revendedor.pdf", ["Attachment" => true]);
exit;