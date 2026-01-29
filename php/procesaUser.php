<?php
// 1. Validar campos
if (
    !empty($_POST['nombreUsuario']) && 
    !empty($_POST['apellidoUsuario']) && 
    !empty($_POST['precioCliente']) 
) {
    echo '<h3>Procesando...</h3>';

    $carpeta_txt = '../UserEspe/';
    if (!file_exists($carpeta_txt)) {
        mkdir($carpeta_txt, 0755, true); 
    }

    // --- ID ÚNICO MÁS SEGURO ---
    // Usamos more_entropy = true para evitar colisiones en procesos rápidos
    $usuario_id = uniqid('ID_', true);

    // --- LIMPIEZA DE DATOS ---
    // Eliminamos cualquier ";" que el usuario escriba para no dañar el formato TXT
    $nombre = str_replace(';', ',', $_POST['nombreUsuario']);
    $apellido = str_replace(';', ',', $_POST['apellidoUsuario']);
    $precio = str_replace(';', ',', $_POST['precioCliente']);

    $datos_producto = [
        $usuario_id,
        $nombre,
        $apellido,
        $precio,
    ];

    $linea = implode(';', $datos_producto);
    $nombre_archivo_txt = 'UserRegistrados.txt';
    
    // Intentar escribir con bloqueo de archivo para evitar errores si dos personas guardan al mismo tiempo
    $ruta_completa = $carpeta_txt . $nombre_archivo_txt;
    $exito = file_put_contents($ruta_completa, $linea . PHP_EOL, FILE_APPEND | LOCK_EX);

    if ($exito) {
        header('refresh:1;url=../UserAdmin/cargar.php');
        echo '<h3>+ Usuario Cargado con éxito.</h3>';
       
        exit();
    } else {
        echo '<h3>Error al escribir en el servidor.</h3>';
    }
} else {
    header('refresh:4;url=../UserAdmin/cargar.php');
    echo '<h3>¡ERROR: Faltan datos!</h3>';

    exit();
}