<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - El hombre de la bolsa</title>

    <meta property="og:title" content="Administracion /El hombre de la bolsa">
    <meta property="og:description" content="Panel solo para administradores.">
    <meta property="og:image" content="../fotos/logo1.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tudominio.com">
    <link rel="icon" type="image/png" href="../fotos/logo1-transparente.png">


    <link rel="stylesheet" href="../css/cargar.css">
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <button class="back-button" onclick="window.location.href='../index.php'">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
        </button>
        <h1 class="admin-title">Panel de Administración</h1>
        
    </header>

    <main class="admin-main">
        <!-- Botones de gestión de usuarios -->
        <h2>Clientes</h2>
        <div class="user-management">
            <button id="btnAltaUsuario" class="btn-user">+ Dar de Alta Un Cliente</button>
            <button id="btnVerUsuarios" class="btn-user">Ver Clientes</button>
        </div>

        <!-- Formulario para dar de alta usuario (oculto por defecto) -->
        <div id="formUsuarioContainer" class="form-container hidden">
            <h2>Asignar una lista a un Cliente</h2>
            <form action="../php/procesaUser.php" method="post" id="formUsuario" class="form-usuario">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre:</label>
                    <input name="nombreUsuario" type="text" id="nombreUsuario" required>
                </div>
                <div class="form-group">
                    <label for="apellidoUsuario">Apellido:</label>
                    <input name="apellidoUsuario" type="text" id="apellidoUsuario" required>
                </div>
                <div class="form-group">
                    <select name="precioCliente" id="precioCliente">
                        <option>Seleccione una lista de PRECIOS para el Cliente</option>
                        <option value="Consumidor_final">Consumidor Final</option>
                        <option value="r0">Revendedor</option>
                        <option value="r10">Rev_10</option>
                        <option value="r50">Rev_50</option>
                        <option value="r100">Rev_100</option>
                        <option value="33">Distribuidor</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Guardar Usuario</button>
                    <button type="button" class="btn-close" onclick="cerrarFormUsuario()">Cerrar</button>
                </div>
            </form>
        </div>

        <!-- Tabla de usuarios (oculta por defecto) -->
        <div id="tablaUsuariosContainer" class="tabla-container hidden">
            <h2>Usuarios Especiales Registrados</h2>
            <p>Codigos: Revendedor:(r0) Rev_10:(r10) Rev_50:(r50) Rev_100:(r100) Distribuidor:(33)</p>
            <div class="table-responsive">
                <table id="tablaUsuarios" class="usuarios-table">
                    
            <?php
                $ruta_archivo = '../UserEspe/UserRegistrados.txt';


                if (file_exists($ruta_archivo)) {
                    echo '<table id="tablaUsuarios" class="usuarios-table">';
                    echo '<thead ">
                            <tr>
                                
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Lista Asignada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>';
                    echo '<tbody>';

                    // Leemos el archivo línea por línea
                    $lineas = file($ruta_archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                    foreach ($lineas as $num_linea => $contenido_linea) {
                        // Dividimos la línea por el punto y coma
                        $datos = explode(';', $contenido_linea);

                        // Validamos que la línea tenga los 4 elementos esperados
                        if (count($datos) >= 4) {
                            $id       = htmlspecialchars($datos[0]);
                            $nombre   = htmlspecialchars($datos[1]);
                            $apellido = htmlspecialchars($datos[2]);
                            $lista   = htmlspecialchars($datos[3]);

                            echo "<tr>";
                            
                            echo "<td>$nombre</td>";
                            echo "<td>$apellido</td>";
                            echo "<td>$lista</td>";
                            echo "<td>
                                    <a href='../php/eliminarUsuario.php?id=$id' onclick='return confirm(\"¿Estás seguro de eliminar este registro?\")' style='color: red; text-decoration: none; font-weight: bold;'>
                                        Eliminar
                                    </a>
                                </td>";
                            echo "</tr>";
                        }
                    }

                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p>No hay registros almacenados actualmente.</p>';
                }
            ?>

                </table>
                <div id="noUsuarios" class="no-data hidden">
                    No hay usuarios registrados
                </div>
            </div>
            <button class="btn-close" onclick="cerrarTablaUsuarios()">Cerrar</button>
        </div>

        <h2>Productos</h2>
        <!-- Botones productos -->
        <div class="user-management">
            <button id="btnCargarProducto" class="btn-user"> + Cargar Producto</button>
            <button id="btnVerProducto" class="btn-user">Ver Productos</button>
        </div>
        <!--  Formulario para cargar productos -->
        <div id="formularioProducto" class="form-container hidden" >
            <h2>Cargar Nuevo Producto</h2>

            <form action="../php/procesoProductos.php" method="POST" enctype="multipart/form-data" id="formProducto" class="form-producto">
                <div class="form-group">
                    <label for="fotoProducto">Foto del Producto:</label>
                    <input type="file" name="fotoProducto" id="fotoProducto" accept="image/*" required>                   
                </div>
                
                <div class="form-group">
                    <label for="descripcionProducto">Descripción:</label>
                    <input type="text" name="descripcionProducto" required>                
                </div>
                <div class="form-group">
                    <label for="codigoProducto">Codigo:</label>
                    <input type="text" name="codigoProducto" required>                
                </div>
                
                <div class="form-group">
                    <label for="cantidadProducto">Cantidad:</label>
                    <input type="text" name="cantidadProducto" id="cantidadProducto" required> 
                </div>
                
                <div class="form-group">
                    <label for="precioUnitario">Costo:</label>
                    <input type="number" name="precioUnitario" id="precioUnitario" step="0.01" required>  
                </div>
                
                <div class="form-group">
                    <label for="precioTotal">Precio Final: (36%)</label>
                    <input type="number" name="precioTotal" id="precioTotal" step="0.01" readonly required> 
                </div>
                
                
                
                <div class="form-group">
                <label>Seleccione Consumidores:</label>

                <div class="checkbox-group two-columns">

                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Supers"> Supers</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Rotiseria"> Rotisería</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Panaderia"> Panadería</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Carniceria"> Carnicería</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Verduleria"> Verdulería</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Resto-roti-bar"> Restaurante / Rotisería / Bar</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Drugstore"> Drugstore</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Supermercado-Mini-Servicio"> Supermercado y Mini Servicio</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Farmacia"> Farmacia</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Botique"> Botique</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Estacion-Servicio"> Estación de Servicios</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Clinica-Consultorio"> Clínica y Consultorios</label>
                            <label><input type="checkbox" name="categoriaConsumidor[]" value="Hoteles"> Hoteles</label>

                </div>
                </div>
                        
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Guardar Producto</button>
                            <button type="reset" class="btn-reset">Limpiar tabla</button>
                            <button type="button" class="btn-close" onclick="cerrarFormUsuarioProducto()">Cerrar Producto </button>

                        </div>
            </form>
                    
                    <div id="mensajeExito" class="mensaje-exito hidden">
                        ¡Producto guardado exitosamente!
                    </div>
                </div>

        
        
        
                <!-- Tabla de Productos Cargados -->
                    <?php
                    $archivo_txt = '../ProDesc/productos.txt';
                    $ruta_fotos = '../ProDesc/fotop/';
                    $productos = [];

                    if (file_exists($archivo_txt)) {
                        $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($lineas as $linea) {
                            $datos = explode(';', $linea);
                            // Verificamos que tenga los datos mínimos (ahora son 14 columnas)
                            if (count($datos) >= 11) {
                                $productos[] = $datos;
                            }
                        }
                    }
                    ?>



<div id="tablaProductosContainer1" class="tabla-container">
    <h2>Listado de Productos</h2>

    <div style="margin-bottom: 15px; background: #f3f4f6; padding: 10px; border-radius: 8px;">
        <input type="text" id="inputBusquedaAdmin" placeholder="🔍 Buscar por nombre o código..." 
               style="width: 100%; padding: 12px; border: 2px solid #764ba2; border-radius: 6px; outline: none;">
    </div>

    <div class="table-responsive">
        <table class="usuarios-table" id="tablaAdminProductos">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Código</th> 
                    <th>Descripción</th>
                     <th>Unidades</th>
                    <th>Costo</th>
                    <th>Precio de venta 36%</th>
                    <th>P. TOTAL VENTA</th>
                    <th>Revendedor / R10</th>
                    <th>R50 / R100</th>
                    <th>Distribuidor</th>
                    <th>Rubros</th> <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="bodyAdminProductos">
                <?php if (empty($productos)): ?>
                    <tr><td colspan="11" style="text-align:center;">No hay productos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                    <tr class="fila-producto-admin" 
                        data-nombre="<?php echo strtolower(htmlspecialchars($p[2])); ?>" 
                        data-codigo="<?php echo strtolower(htmlspecialchars($p[13] ?? '')); ?>"
                        style="display: none;"> <td data-label="Foto">
                            <img src="<?php echo $ruta_fotos . $p[1]; ?>" width="55" style="border-radius:8px; object-fit:cover;">
                        </td>

                        <td data-label="Código" style="font-weight:bold; color:#764ba2;">
                            <?php echo htmlspecialchars($p[13] ?? 'S/C'); ?>
                        </td>

                        <td data-label="Descripción">
                            <?php echo htmlspecialchars($p[2]); ?>
                        </td>

                        <td data-label="Stock" style="text-align:center;"><?php echo htmlspecialchars($p[3]); ?></td>

                        <td data-label="P. Unit">$<?php echo number_format((float)$p[4], 2, ',', '.'); ?></td>

                        <td data-label="P. Unit">$<?php $p_mas_iva_final = $p[4] * 1.36; echo number_format((float)$p_mas_iva_final, 2, ',', '.'); ?></td>

                        <td data-label="P. FINAL" style="background: #fdf2f2; font-weight: bold; color: #b91c1c;">
                            $<?php echo number_format((float)$p[5], 2, ',', '.'); ?>
                        </td>

                        <td data-label="Rev / R10" style="font-size: 0.85em;">
                            <div><strong>Rev:</strong> $<?php echo number_format((float)$p[6], 2, ',', '.'); ?></div>
                            <div><strong>R10:</strong> $<?php echo number_format((float)$p[7], 2, ',', '.'); ?></div>
                        </td>

                        <td data-label="R50 / R100" style="font-size: 0.85em;">
                            <div><strong>R50:</strong> $<?php echo number_format((float)$p[8], 2, ',', '.'); ?></div>
                            <div><strong>R100:</strong> $<?php echo number_format((float)$p[9], 2, ',', '.'); ?></div>
                        </td>

                        <td data-label="Distribuidor" style="font-weight: bold; color: #1e40af;">
                            $<?php echo number_format((float)$p[10], 2, ',', '.'); ?>
                        </td>

                        <td data-label="Rubros" style="font-size: 0.8em; color: #666;">
                            <?php echo htmlspecialchars($p[12] ?? 'Sin Rubro'); ?>
                        </td>

                        <td data-label="Acciones" class="acciones-cell">
                            <div style="display:flex; flex-direction:column; gap:5px;">
                                <a href="editar_producto.php?id=<?php echo $p[0]; ?>" class="btn-edit" style="padding: 4px 8px; font-size: 11px;">Editar</a>
                                <a href="../php/eliminarProducto.php?id=<?php echo $p[0]; ?>" class="btn-delete" style="padding: 4px 8px; font-size: 11px;" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align: center; margin: 20px 0;">
        <button id="btnVerMasAdmin" style="background: #764ba2; color: white; border: none; padding: 12px 30px; border-radius: 25px; cursor: pointer; font-weight: bold; display: none;">Ver más productos (+40)</button>
    </div>

    <button class="btn-close" onclick="cerrarT()">Cerrar</button>
</div>
        
    </main>

    <script src="../js/cargar.js"></script>
</body>
</html>
