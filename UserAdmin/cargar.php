<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Productos - El hombre de la bolsa</title>
    <link rel="stylesheet" href="../css/cargar.css">
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <button class="back-button" onclick="window.location.href='../index.html'">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
        </button>
        <h1 class="admin-title">Panel de Administración</h1>
        
    </header>

    <main class="admin-main">
        <!-- Botones de gestión de usuarios -->
        <h2>Usuarios</h2>
        <div class="user-management">
            <button id="btnAltaUsuario" class="btn-user">+ Dar de Alta Usuario</button>
            <button id="btnVerUsuarios" class="btn-user">Ver Usuarios</button>
        </div>

        <!-- Formulario para dar de alta usuario (oculto por defecto) -->
        <div id="formUsuarioContainer" class="form-container hidden">
            <h2>Registrar Usuario Especial</h2>
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
                        <option>Seleccione Descuento para el Cliente</option>
                        <option value="10%-Descuento">10%-Descuento</option>
                        <option value="20%-Descuento">20%-Descuento</option>
                        <option value="30%-Descuento">30%-Descuento</option>
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
            <p>Codigos: 10%-(cod73xs) 20%-(cod29fh) 30%-(cod95gt) </p>
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
                                <th>Precio/Dato</th>
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
                            $precio   = htmlspecialchars($datos[3]);

                            echo "<tr>";
                            
                            echo "<td>$nombre</td>";
                            echo "<td>$apellido</td>";
                            echo "<td>$precio</td>";
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
            <button type="button" class="btn-close" onclick="cerrarFormUsuarioProducto()">Cerrar Producto </button>

            <form action="../php/procesoProductos.php" method="POST" enctype="multipart/form-data" id="formProducto" class="form-producto">
                <div class="form-group">
                    <label for="fotoProducto">Foto del Producto:</label>
                    <input type="file" name="fotoProducto" id="fotoProducto" accept="image/*" required>                   
                     <div id="previewFoto" class="preview-foto"></div>
                </div>
                
                <div class="form-group">
                    <label for="descripcionProducto">Descripción:</label>
                    <input type="text" name="descripcionProducto" required>                </div>
                
                <div class="form-group">
                    <label for="cantidadProducto">Cantidad:</label>
                    <input type="text" name="cantidadProducto" required>                </div>
                
                <div class="form-group">
                    <label for="precioUnitario">Precio Unitario:</label>
                    <input type="number" name="precioUnitario" step="0.01" required>                </div>
                
                <div class="form-group">
                    <label for="precioTotal">Precio Total:</label>
                    <input type="number" name="precioTotal" step="0.01" required>                </div>
                
                <div class="form-group">
                    <label for="precioAmigo">Precio Descuento 1:</label>
                    <input type="number" name="precioAmigo1" step="0.01" required>                </div>
                <div class="form-group">
                    <label for="precioAmigo">Precio Descuento 2:</label>
                    <input type="number" name="precioAmigo2" step="0.01" required>                </div>
                <div class="form-group">
                    <label for="precioAmigo">Precio Descuento 3:</label>
                    <input type="number" name="precioAmigo3" step="0.01" required>                </div>
                
                <div class="form-group">
                    <label for="categoriaMaterial">Categoría Material:</label>
                    <select name="categoriaMaterial" required>                        <option value="">Seleccionar categoría</option>
                        <option value="Plasticos">Plásticos</option>
                        <option value="Cartones">Cartones</option>
                        <option value="Bolsas">Bolsas</option>
                    </select>
                </div>
                <div class="form-group">
    <label>Categoría Consumidor:</label>

    <div class="checkbox-group two-columns">

                <label><input type="checkbox" name="categoriaConsumidor[]" value="supers"> Supers</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="rotiseria"> Rotisería</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="panaderia"> Panadería</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="carniceria"> Carnicería</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="verduleria"> Verdulería</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="resto-roti-bar"> Restaurante / Rotisería / Bar</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="drugstore"> Drugstore</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="super-mini"> Supermercado y Mini Servicio</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="farmacia"> Farmacia</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="botique"> Botique</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="estacion-servicio"> Estación de Servicios</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="clinica-consultorio"> Clínica y Consultorios</label>
                <label><input type="checkbox" name="categoriaConsumidor[]" value="hoteles"> Hoteles</label>

            </div>
        </div>
                
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Guardar Producto</button>
                    <button type="reset" class="btn-reset">Limpiar Formulario</button>
                </div>
            </form>
            
            <div id="mensajeExito" class="mensaje-exito hidden">
                ¡Producto guardado exitosamente!
            </div>
        </div>
        <!-- Tabla de Productos Cargados -->
        <div id="tablaProductosContainer1" class="tabla-container hidden">
            <h2>Productos Cargados</h2>
        
            <div class="familia-seccion">
                <h3>1- Familia</h3>
                <div class="table-responsive">
                    <table id="tablaUsuariosF1" class="usuarios-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Código Especial</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuariosBodyF1">
                            </tbody>
                    </table>
                    <div id="noUsuariosF1" class="no-data hidden">No hay usuarios en Familia 1</div>
                </div>
            </div>
        
            <hr>
        
            <div class="familia-seccion">
                <h3>2- Familia</h3>
                <div class="table-responsive">
                    <table id="tablaUsuariosF2" class="usuarios-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Código Especial</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuariosBodyF2">
                            </tbody>
                    </table>
                    <div id="noUsuariosF2" class="no-data hidden">No hay usuarios en Familia 2</div>
                </div>
            </div>
        
            <hr>
        
            <div class="familia-seccion">
                <h3>3- Familia</h3>
                <div class="table-responsive">
                    <table id="tablaUsuariosBodyF3_table" class="usuarios-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Código Especial</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuariosBodyF3">
                            </tbody>
                    </table>
                    <div id="noUsuariosF3" class="no-data hidden">No hay usuarios en Familia 3</div>
                </div>
            </div>
        
            <button class="btn-close" onclick="cerrarT()">Cerrar</button>
        </div>
    </main>

    <script src="../js/cargar.js"></script>
</body>
</html>
