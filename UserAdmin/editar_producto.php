<?php
$id_editar = $_GET['id'] ?? '';
$archivo_txt = '../ProDesc/productos.txt';
$producto = null;

if (file_exists($archivo_txt)) {
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES);
    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        if ($datos[0] === $id_editar) {
            $producto = $datos;
            break;
        }
    }
}

if (!$producto) { die("Producto no encontrado."); }

// NUEVOS ÍNDICES: 
// [11] Categoría Material, [12] Categoría Consumidor, [13] Código
$cats_guardadas = explode(", ", $producto[12]); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/cargar.css"> 
</head>
<body>
    <div class="form-container">
        <h2>Editar Producto: <?php echo htmlspecialchars($producto[2]); ?></h2>

        <form action="../php/procesoEditarProducto.php" method="POST" enctype="multipart/form-data" class="form-producto">
            <input type="hidden" name="idProducto" value="<?php echo $producto[0]; ?>">
            <input type="hidden" name="fotoActual" value="<?php echo $producto[1]; ?>">

            <div class="form-group">
                <label>Foto actual:</label><br>
                <img src="../ProDesc/fotop/<?php echo $producto[1]; ?>" width="100" style="border-radius:5px;"><br>
                <label for="fotoProducto">Cambiar Foto (Opcional):</label>
                <input type="file" name="fotoProducto" id="fotoProducto" accept="image/*">
            </div>

            <div class="form-group">
                <label>Código:</label>
                <input type="text" name="codigoProducto" value="<?php echo htmlspecialchars($producto[13] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Descripción:</label>
                <input type="text" name="descripcionProducto" value="<?php echo htmlspecialchars($producto[2]); ?>" required>
            </div>

            <div class="form-group">
                <label>Cantidad:</label>
                <input type="number" name="cantidadProducto" id="cantidadProducto" value="<?php echo htmlspecialchars($producto[3]); ?>" required>
            </div>

            <div class="form-group">
                <label>Costo:</label>
                <input type="number" name="precioUnitario" id="precioUnitario" step="0.01" value="<?php echo $producto[4]; ?>" required>
            </div>

            <div class="form-group">
                <label>Precio de venta (36%):</label>
                <input type="number" name="precioTotal" id="precioTotal" step="0.01" value="<?php echo $producto[5]; ?>" readonly required>
                <small>Este campo se calcula automáticamente.</small>
            </div>

           

            <div class="form-group">
                <label>Categoría Consumidor:</label>
                <div class="checkbox-group two-columns">
                    <?php 
                    $opciones = ["Supers", "Rotiseria", "Panaderia", "Carniceria", "Verduleria", "Resto-roti-bar", "Drugstore", "Supermercado-Mini-Servicio", "Farmacia", "Botique", "Estacion-Servicio", "Clinica-Consultorio", "Hoteles"];
                    foreach($opciones as $op): ?>
                        <label>
                            <input type="checkbox" name="categoriaConsumidor[]" value="<?php echo $op; ?>" 
                            <?php echo in_array($op, $cats_guardadas) ? 'checked' : ''; ?>> <?php echo $op; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Actualizar Producto</button>
                <a href="cargar.php" class="btn-close">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Seleccionamos los mismos IDs que ya tienes en tu HTML
                    const inputCant = document.getElementById('cantidadProducto');
                    const inputUnit = document.getElementById('precioUnitario');
                    const inputTotal = document.getElementById('precioTotal');

                    function calcular() {
                        // Obtenemos los valores o 0 si están vacíos
                        const c = parseFloat(inputCant.value) || 0;
                        const u = parseFloat(inputUnit.value) || 0;

                        // Aplicamos la misma lógica: (Cantidad * Costo) + 36%
                        const resultado = (c * u) * 1.36;

                        // Asignamos al campo de Precio Final con 2 decimales
                        inputTotal.value = resultado.toFixed(2);
                    }

                    // Escuchamos cambios en cantidad y costo
                    inputCant.addEventListener('input', calcular);
                    inputUnit.addEventListener('input', calcular);
                });
    </script>
</body>
</html>