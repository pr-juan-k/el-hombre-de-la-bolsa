<?php
// 1. Definir Rutas
$ruta_base = '../ProDesc/';
$ruta_fotos = $ruta_base . 'fotop/';
$archivo_txt = $ruta_base . 'productos.txt';

// Crear carpetas si no existen
if (!file_exists($ruta_fotos)) {
    mkdir($ruta_fotos, 0755, true);
}

// 2. Validar que se recibió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Generar ID Único
    $id_unico = uniqid('PROD_', true);

    // 3. Procesar la Foto
    $foto_nombre_original = $_FILES['fotoProducto']['name'];
    $extension = pathinfo($foto_nombre_original, PATHINFO_EXTENSION);
    $nuevo_nombre_foto = $id_unico . "." . $extension; // Ejemplo: PROD_654...jpg
    $ruta_destino_foto = $ruta_fotos . $nuevo_nombre_foto;

    if (move_uploaded_file($_FILES['fotoProducto']['tmp_name'], $ruta_destino_foto)) {
        
        // 4. Recoger datos y Sanitizar
        $descripcion = str_replace(';', ',', $_POST['descripcionProducto']);
        $cantidad    = str_replace(';', ',', $_POST['cantidadProducto']);
        $p_unitario  = $_POST['precioUnitario'];
        $p_total     = $_POST['precioTotal'];
        $desc1       = $_POST['precioAmigo1'];
        $desc2       = $_POST['precioAmigo2'];
        $desc3       = $_POST['precioAmigo3'];
        $cat_mat     = $_POST['categoriaMaterial'];
        
        // Procesar checkboxes (convertir array a string separado por comas)
        $cat_cons    = isset($_POST['categoriaConsumidor']) ? implode(", ", $_POST['categoriaConsumidor']) : "Ninguna";

        // 5. Preparar línea para el TXT (Separada por ;)
        $linea = implode(';', [
            $id_unico,
            $nuevo_nombre_foto, // Guardamos el nombre de la foto para mostrarla después
            $descripcion,
            $cantidad,
            $p_unitario,
            $p_total,
            $desc1,
            $desc2,
            $desc3,
            $cat_mat,
            $cat_cons
        ]);

        // 6. Guardar en el archivo con bloqueo de seguridad
        if (file_put_contents($archivo_txt, $linea . PHP_EOL, FILE_APPEND | LOCK_EX)) {
            echo "<h3>Producto Cargado con éxito.</h3>";
            header("refresh:1; url=../UserAdmin/cargar.php"); // Cambia a tu página principal
        } else {
            echo "Error al escribir en el archivo de texto.";
        }

    } else {
        echo "Error al subir la imagen. Verifica permisos de carpeta.";
    }
} else {
    echo "Acceso no permitido.";
}
?>