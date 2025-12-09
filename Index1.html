

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
        <div class="user-management">
            <button id="btnAltaUsuario" class="btn-user">Dar de Alta Usuario</button>
            <button id="btnVerUsuarios" class="btn-user">Ver Usuarios</button>
        </div>

        <!-- Formulario para dar de alta usuario (oculto por defecto) -->
        <div id="formUsuarioContainer" class="form-container hidden">
            <h2>Registrar Usuario Especial</h2>
            <form action="../php/procesa.php" method="post" id="formUsuario" class="form-usuario">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre:</label>
                    <input name="nombreUsuario" type="text" id="nombreUsuario" required>
                </div>
                <div class="form-group">
                    <label for="apellidoUsuario">Apellido:</label>
                    <input name="apellidoUsuario" type="text" id="apellidoUsuario" required>
                </div>
                <div class="form-group">
                    <label for="codigoUsuario">Código Especial:</label>
                    <input name="codigoUsuario" type="text" id="codigoUsuario" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Guardar Usuario</button>
                    <button type="button" class="btn-cancel" onclick="cerrarFormUsuario()">Cancelar</button>
                </div>
            </form>
        </div>
        <?php 
            $carpeta_txt = '../UserEspe'; // ¡Añadida la barra diagonal al final!
            $nombre_archivo_txt = 'UserRegistrados.txt';
            // Ahora el resultado es: "UserEspe/UserRegistrados.txt"
            $ruta_archivo = $carpeta_txt . '/'.$nombre_archivo_txt; 

            $contador_fila = 1; // DEBE empezar en 1 para que el N° sea correcto
            $usuarios_encontrados = false;
        ?>

        <!-- Tabla de usuarios (oculta por defecto) -->
        <div id="tablaUsuariosContainer" class="tabla-container hidden">
            <h2>Usuarios Especiales Registrados</h2>
            <div class="table-responsive">
                <table id="tablaUsuarios" class="usuarios-table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Código Especial</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuariosBody">
                    <?php
                // 1. Verificar si el archivo existe
                if (file_exists($ruta_archivo)) {
                    // 2. Leer el contenido del archivo línea por línea
                    $contenido = file($ruta_archivo);
                    if ($contenido) {
                        $usuarios_encontrados = true;
                        // 3. Iterar sobre cada línea (usuario)
                        foreach ($contenido as $linea) {
                            // Limpiamos la línea de espacios en blanco/saltos de línea
                            $linea = trim($linea);
                            
                            // 4. Dividir la línea usando el delimitador ';'
                            // El orden de los datos debe ser: ID;nombre;apellido;codigo
                            $datos_usuario = explode(';', $linea);

                            // Verificamos que tengamos la cantidad esperada de campos (4)
                            if (count($datos_usuario) === 4) {
                                // Desempaquetamos los datos en variables con nombres claros
                                list($id_unico, $nombre, $apellido, $codigo) = $datos_usuario;
                                
                                // 5. Generar la fila de la tabla
                                echo '<tr>';
                                echo '<td>' . $contador_fila . '</td>'; // N° de fila
                                echo '<td>' . htmlspecialchars($nombre) . '</td>';
                                echo '<td>' . htmlspecialchars($apellido) . '</td>';
                                echo '<td>' . htmlspecialchars($codigo) . '</td>';
                                echo '<td><a class="btn-delete" href="../php/eliminar.php?id=' . htmlspecialchars($id_unico) . '" class="btn-eliminar" onclick="return confirm(\'¿Está seguro de eliminar a ' . htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido) . '?\')">Eliminar</a></td>';
                                echo '</tr>';

                                $contador_fila++;
                            }
                        }
                    }
                }

                // 6. Si no se encontraron usuarios, se genera una fila de "No hay datos" 
                if (!$usuarios_encontrados) {
                    // Nota: Si usas el div `noUsuarios` (como en tu HTML original), puedes no generar esta fila 
                    // y solo mostrar el div. Dejo esta opción por si quieres manejarlo dentro de la tabla.
                    echo '<tr><td colspan="6">No hay usuarios registrados.</td></tr>';
                    
                }
                ?>
                        <!-- Se llenará dinámicamente -->
                    </tbody>
                </table>
            
            </div>
            <button class="btn-close" onclick="cerrarTablaUsuarios()">Cerrar</button>
        </div>

        <!-- Formulario para cargar productos -->
        <div class="form-container">
            <h2>Cargar Nuevo Producto</h2>
            <form id="formProducto" class="form-producto">
                <div class="form-group">
                    <label for="fotoProducto">Foto del Producto:</label>
                    <input type="file" id="fotoProducto" accept="image/*" required>
                    <div id="previewFoto" class="preview-foto"></div>
                </div>
                
                <div class="form-group">
                    <label for="descripcionProducto">Descripción:</label>
                    <input type="text" id="descripcionProducto" placeholder="Ej: Bolsa de plástico transparente" required>
                </div>
                
                <div class="form-group">
                    <label for="cantidadProducto">Cantidad:</label>
                    <input type="text" id="cantidadProducto" placeholder="Ej: 100 unidades" required>
                </div>
                
                <div class="form-group">
                    <label for="precioUnitario">Precio Unitario:</label>
                    <input type="number" id="precioUnitario" step="0.01" placeholder="0.00" required>
                </div>
                
                <div class="form-group">
                    <label for="precioTotal">Precio Total:</label>
                    <input type="number" id="precioTotal" step="0.01" placeholder="0.00" required>
                </div>
                
                <div class="form-group">
                    <label for="precioAmigo">Precio con Descuento Amigo:</label>
                    <input type="number" id="precioAmigo" step="0.01" placeholder="0.00" required>
                </div>
                
                <div class="form-group">
                    <label for="categoriaProducto">Categoría:</label>
                    <select id="categoriaProducto" required>
                        <option value="">Seleccionar categoría</option>
                        <option value="Plasticos">Plásticos</option>
                        <option value="Cartones">Cartones</option>
                        <option value="Bolsas">Bolsas</option>
                    </select>
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
    </main>

    <script src="../js/cargar.js"></script>
</body>
</html>
