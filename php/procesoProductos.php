<?php
// 1. Definir Rutas
$ruta_base = '../ProDesc/';
$ruta_fotos = $ruta_base . 'fotop/';
$archivo_txt = $ruta_base . 'productos.txt';

if (!file_exists($ruta_fotos)) {
    mkdir($ruta_fotos, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_unico = uniqid('PROD_', true);

    // 2. Procesar la Foto
    $foto_nombre_original = $_FILES['fotoProducto']['name'];
    $extension = pathinfo($foto_nombre_original, PATHINFO_EXTENSION);
    $nuevo_nombre_foto = $id_unico . "." . $extension; 
    $ruta_destino_foto = $ruta_fotos . $nuevo_nombre_foto;

    if (move_uploaded_file($_FILES['fotoProducto']['tmp_name'], $ruta_destino_foto)) {
        
        // 3. Recoger datos y Sanitizar (evitar que el punto y coma rompa el archivo TXT)
        $descripcion = str_replace(';', ',', $_POST['descripcionProducto']);
        $codigo      = str_replace(';', ',', $_POST['codigoProducto']);
        $cantidad    = str_replace(';', ',', $_POST['cantidadProducto']);
        $p_unitario  = (float)$_POST['precioUnitario'];
        
        // El Precio Total de tu formulario es el PRECIO BASE (Consumidor Final)
        $p_base = (float)$_POST['precioTotal'];

        // 4. Calcular las 6 Listas de Precios (Aplicando los descuentos)
        // Lógica: Precio Base * (1 - porcentaje/100)
        $p_consumidor_final = ($p_unitario * 1.36) * $cantidad;  
        
        $p_limpio = $cantidad * $p_unitario;             // 0% para calculo
        $p_revendedor       = $p_limpio * 1.15;        // 21% desc
        $p_rev_10           = $p_limpio * 1.12;        // 24% desc
        $p_rev_50           = $p_limpio * 1.10;        // 26% desc
        $p_rev_100          = $p_limpio * 1.09;        // 27% desc
        $p_distribuidor     = $p_limpio * 1.08;        // 28% desc

        $cat_mat  = $_POST['categoriaMaterial'];
        
        // Procesar checkboxes de consumidores
        $cat_cons = isset($_POST['categoriaConsumidor']) ? implode(", ", $_POST['categoriaConsumidor']) : "Ninguna";

        // 5. Preparar línea para el TXT (Ahora con 13 columnas de datos)
        // Es vital mantener este orden para que tu buscador y PDF funcionen
        $linea = implode(';', [
            $id_unico,           // 0
            $nuevo_nombre_foto,  // 1
            $descripcion,        // 2
            $cantidad,           // 3
            $p_unitario,         // 4
            $p_consumidor_final, // 5 (Base)
            $p_revendedor,       // 6
            $p_rev_10,           // 7
            $p_rev_50,           // 8
            $p_rev_100,          // 9
            $p_distribuidor,     // 10
            $cat_mat,            // 11
            $cat_cons,           // 12
            $codigo              // 13
        ]);

        // 6. Guardar con bloqueo de seguridad
        if (file_put_contents($archivo_txt, $linea . PHP_EOL, FILE_APPEND | LOCK_EX)) {
            echo "<script>
                window.location.href='../UserAdmin/cargar.php';
            </script>";
        } else {
            echo "Error al escribir en el archivo.";
        }

    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "Acceso denegado.";
}
?>