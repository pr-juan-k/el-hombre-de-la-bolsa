<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El hombre de la bolsa - Productos</title>
    <link rel="stylesheet" href="css/producto.css">

    <meta property="og:title" content="Producto -El Hombre de la Bolsa">
    <meta property="og:description" content="Somos una importante empresa de descartables: vasos plásticos, bolsas, bandejas, cajas y una amplia variedad de productos para tu negocio. Calidad, precio y compromiso con cada cliente.">
    <meta property="og:image" content="fotos/logo1.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tudominio.com">
    <link rel="icon" type="image/png" href="fotos/logo1-transparente.png">

</head>
<body>

    <!-- Header -->
    <header class="header">
        <a href="Index.php" id="backBtn" class="back-button">
            <svg class="back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 id="categoryTitle" class="header-title">Productos</h1>
        <div class="header-spacer"></div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Download Catalog Button -->
        <div class="catalog-section">
            <button id="downloadCatalog" class="download-button">
                <svg class="download-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar Catálogo
            </button>
        </div>
        <?php
            // Capturamos los datos de la URL
            // Si no viene 'user', lo dejamos vacío (null)
            $nombre_cliente = isset($_GET['user']) ? $_GET['user'] : null;
            $nivel_descuento = isset($_GET['desc']) ? (int)$_GET['desc'] : 0; 
        ?>

        <?php if ($nombre_cliente): ?>
            <h2 class="table-title">Bienvenido: <?php echo htmlspecialchars($nombre_cliente); ?></h2>
        <?php endif; ?>
        <!-- Search Box -->
        

        <?php
            // 1. Capturar datos de la URL
            $categoria_seleccionada = isset($_GET['cat']) ? strtolower(trim($_GET['cat'])) : '';
            $nombre_cliente = isset($_GET['user']) ? $_GET['user'] : 'Cliente';
            $nivel_descuento = isset($_GET['desc']) ? (int)$_GET['desc'] : 5; 
            if ($nivel_descuento < 5) { $nivel_descuento = 5; }

            // 2. Rutas
            $ruta_base = 'ProDesc/';
            $ruta_fotos = $ruta_base . 'fotoP/';
            $archivo_txt = $ruta_base . 'productos.txt';

            $productos_filtrados = [];

            // 3. Procesar el archivo
            if (file_exists($archivo_txt)) {
                $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
                foreach ($lineas as $linea) {
                    $datos = explode(';', $linea);
                    
                    // Verificamos que la línea tenga las columnas necesarias (al menos hasta la 13)
                    if (count($datos) >= 13) {
                        
                        // 1. Obtenemos los rubros del producto actual (están en la posición 12)
                        // Ejemplo: "Supers, Carniceria, Verduleria"
                        $rubros_del_producto = strtolower(trim($datos[12])); 
            
                        // 2. Obtenemos lo que el usuario quiere ver (ejemplo: "supers")
                        $categoria_buscada = strtolower(trim($categoria_seleccionada));
            
                        // 3. VALIDACIÓN CRÍTICA: 
                        // Verificamos si este producto específico pertenece al rubro elegido.
                        // Si la búsqueda está vacía (mostrar todo) o si el rubro está en la lista:
                        if (empty($categoria_buscada) || strpos($rubros_del_producto, $categoria_buscada) !== false) {
                            
                            // Si entra aquí, es porque el producto SÍ pertenece al rubro.
                            // Ahora elegimos el precio correcto según el descuento aplicado.
                            $precio_final = (isset($datos[$nivel_descuento]) && trim($datos[$nivel_descuento]) !== '') 
                                            ? $datos[$nivel_descuento] 
                                            : $datos[5];
            
                            // Agregamos este producto a la lista que se va a mostrar
                            $productos_filtrados[] = [
                                'foto'        => $datos[1],
                                'descripcion' => $datos[2],
                                'codigo'      => $datos[13] ?? 'N/A',
                                'cantidad'    => $datos[3],
                                'p_unitario'  => $datos[4],
                                'p_total'     => $precio_final
                            ];
                        }
                        // Si no coincide, el bucle salta al siguiente producto sin hacer nada.
                    }
                }
            }

                // 4. Ordenar alfabéticamente por descripción
                usort($productos_filtrados, function($a, $b) {
                    return strcasecmp($a['descripcion'], $b['descripcion']);
                });
            
            ?>



        <div class="products-container">
        <div class="table-header">
           <!-- <h2 class="welcome-title">Bienvenido: <?php echo htmlspecialchars($nombre_cliente); ?></h2> -->
            <h3 id="tableTitle" class="table-title">
                Rubro: <?php echo htmlspecialchars(ucfirst($categoria_seleccionada)); ?>
            </h3>
        </div>
        <div class="scroll-indicator d-mobile-only">
            <span><i class="arrow-left">←</i> Desliza para ver más <i class="arrow-right">→</i></span>
        </div>

        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Descripción</th>
                        <th>Codigo</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th> </tr>
                </thead>
                <tbody id="productsTableBody">
                    <?php if (!empty($productos_filtrados)): ?>
                        <?php foreach ($productos_filtrados as $prod): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $ruta_fotos . $prod['foto']; ?>" 
                                         alt="<?php echo $prod['descripcion']; ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($prod['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
                                <td>$<?php $p_unitario_mio =  $prod['p_total'] / $prod['cantidad'];  echo number_format((float)$p_unitario_mio, 2, ',', '.'); ?></td>
                                
                                <td style="font-weight: bold; color: <?php echo $nivel_descuento > 0 ? '#059669' : 'inherit'; ?>;">
                                    $<?php echo number_format((float)$prod['p_total'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No se encontraron productos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </main>

    <!-- Footer / Pie de página -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                
                <!-- Columna 1: Sobre nosotros -->
                <div class="footer-column">
                    <h4>El hombre de la bolsa</h4>
                    <p>Tu tienda de confianza desde 1995. Ofrecemos productos de calidad para toda las Empresas y Negocios.</p>
                </div>

                <!-- Columna 2: Enlaces rápidos -->
                

                <!-- Columna 3: Contacto -->
                <div id="contactos" class="footer-column">
                    <h4>Información</h4>
                    <ul>
                        <li>📍 Av. Roca 275, Tucuman</li>
                        <li>📍 Fleming 155, Termas de Rio Hondo</li>
                        <li>📍 Hipolito Irigoyen 455, Santiago Del Estero</li>
                        <li>📍 San luis 960</li>
                        <li>📞 Tucuman +54 9 381-2443-333</li>
                        <li>📞 Santiago +54 9 385-6189-825</li>
                        <li>🕐 Lun - Sáb: 8:00 - 21:00</li>
                    </ul>
                </div>

                <!-- Columna 4: Redes sociales -->
                <div class="footer-column">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/share/1CyLoVgoGn/" class="social-link facebook" aria-label="Facebook">f</a>
                        <a href="https://www.instagram.com/elhombredelabolsa._/" class="social-link instagram" aria-label="Instagram">📷</a>
                        <a href="https://www.tiktok.com/@elhombredelabolsa26?is_from_webapp=1&sender_device=pc" class="social-link tiktok" aria-label="TikTok">🎵</a>
                    </div>
                    <ul>
                        <li><a href="www.MAPSANET.com" >www.MAPSANET.com</a></li>
                        
                    </ul>
                </div>
            </div>


            

            <!-- Copyright -->
            <div class="footer-bottom">
                <p>&copy; 2026 El hombre de la bolsa. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    

    <script src="js/producto.js"></script>
</body>
</html>
