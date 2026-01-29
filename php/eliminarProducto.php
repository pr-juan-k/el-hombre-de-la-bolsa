<?php
// 1. Obtener el ID desde la URL
$id_a_eliminar = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_a_eliminar) {
    die("ID de producto no proporcionado.");
}

// 2. Definir rutas (deben coincidir con las de tu archivo de carga)
$ruta_base = '../ProDesc/';
$ruta_fotos = $ruta_base . 'fotop/';
$archivo_txt = $ruta_base . 'productos.txt';

// 3. Verificar si el archivo existe
if (file_exists($archivo_txt)) {
    // Leer todas las líneas del archivo
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $nuevas_lineas = [];
    $encontrado = false;

    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        
        // El índice 0 es el ID Único (PROD_...)
        if ($datos[0] === $id_a_eliminar) {
            $encontrado = true;
            
            // Obtener el nombre de la foto (índice 1) y borrarla físicamente
            $nombre_foto = $datos[1];
            $ruta_completa_foto = $ruta_fotos . $nombre_foto;
            
            if (!empty($nombre_foto) && file_exists($ruta_completa_foto)) {
                unlink($ruta_completa_foto); // Borra la foto del servidor
            }
            
            // No agregamos esta línea al array $nuevas_lineas (así se elimina)
            continue; 
        }
        
        $nuevas_lineas[] = $linea;
    }

    if ($encontrado) {
                // 5. Redirigir de vuelta a la página principal con un mensaje (opcional)

        header("refresh:1;url= ../UserAdmin/cargar.php?mensaje=eliminado");

        // 4. Reescribir el archivo con las líneas restantes
        // Usamos LOCK_EX para evitar errores si alguien está cargando un producto al mismo tiempo
        $contenido_final = empty($nuevas_lineas) ? "" : implode(PHP_EOL, $nuevas_lineas) . PHP_EOL;
        file_put_contents($archivo_txt, $contenido_final, LOCK_EX);
        
        echo '<h3> -Producto Eliminado con éxito.</h3>';
        exit;
    } else {
        echo "El producto con ID $id_a_eliminar no se encontró.";
    }
} else {
    echo "El archivo de productos no existe.";
}
?>