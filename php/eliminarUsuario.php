<?php

// 1. Configuración de archivos
$carpeta_txt = '../UserEspe/';
$nombre_archivo_txt = 'UserRegistrados.txt';
$ruta_archivo = $carpeta_txt . $nombre_archivo_txt;

// 2. Verificar que el ID haya sido recibido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_a_eliminar = trim($_GET['id']);
    $lineas_restantes = [];
    $usuario_eliminado = false;

    // 3. Verificar si el archivo existe y es legible
    if (file_exists($ruta_archivo)) {
        // Leer el archivo completo
        $contenido = file($ruta_archivo);

        // Si el archivo se leyó correctamente
        if ($contenido !== false) {
            foreach ($contenido as $linea) {
                $linea_limpia = trim($linea);
                if (empty($linea_limpia)) continue; // Ignora líneas vacías
            
                $datos_usuario = explode(';', $linea_limpia);
            
                // Comprobamos SOLO que el ID coincida (índice 0)
                if (isset($datos_usuario[0]) && $datos_usuario[0] === $id_a_eliminar) {
                    $usuario_eliminado = true;
                    continue; // Saltamos esta línea (la eliminamos)
                }
                
                // Si no es el ID, mantenemos la línea original con su salto de línea
                $lineas_restantes[] = $linea;
            }

            // 4. Si encontramos y eliminamos el usuario, reescribimos el archivo
            if ($usuario_eliminado) {
                // Preparamos el contenido para reescribir: juntamos el array en una sola cadena
                $nuevo_contenido = implode('', $lineas_restantes);
                
                // Escribimos (sobrescribimos) el archivo con las líneas restantes
                // FILE_APPEND no se usa aquí, queremos SOBREESCRIBIR
                if (file_put_contents($ruta_archivo, $nuevo_contenido) !== false) {
                    // Éxito en la eliminación y reescritura.
                    $mensaje = 'Usuario con ID ' . $id_a_eliminar . ' eliminado con éxito.';
                } else {
                    $mensaje = 'Error al reescribir el archivo de usuarios.';
                }
            } else {
                $mensaje = 'Error: No se encontró el usuario con ID ' . $id_a_eliminar . '.';
            }
        } else {
            $mensaje = 'Error: No se pudo leer el archivo de usuarios.';
        }
    } else {
        $mensaje = 'Error: El archivo de usuarios no existe en la ruta especificada.';
    }
} else {
    $mensaje = 'Error: ID de usuario no especificado.';
}

// 5. Redireccionar al usuario a cargar.php con un mensaje de estado
// Puedes usar sesiones, pero para simplificar, usaremos un parámetro GET simple.
header('refresh:1;url=../UserAdmin/cargar.php?mensaje=' . urlencode($mensaje));
echo '<h3> -Usuario Eliminado con éxito.</h3>';
exit();

?>