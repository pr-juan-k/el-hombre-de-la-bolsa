<?php
// session_start();

// 1. Validar que todos los campos de texto y las TRES fotos se hayan subido correctamente y sin errores.
if (
    !empty($_POST['nombreUsuario']) && //  Validar el campo Marca!
    !empty($_POST['apellidoUsuario']) && 
    !empty($_POST['codigoUsuario']) 
) {
    echo '<h3>Se están procesando los datos </h3>';

    // Crear la carpeta 'portadas' si no existe
    $carpeta_portadas = '../UserEspe/';
    if (!file_exists($carpeta_portadas)) {
        mkdir($carpeta_portadas, 0777, true); 
    }


    // 2. Verificar que LAS TRES se movieron con éxito

        // --- Generar un ID único para el producto ---
        $producto_id = uniqid();

        // Preparamos los datos para guardar en el archivo de texto
        // Importante: El ID ahora es el primer elemento
        $datos_producto = [
            $producto_id,
            $_POST['nombreUsuario'], // ¡NUEVO: Campo Marca!
            $_POST['apellidoUsuario'], // ¡NUEVO: Campo Modelo!
            $_POST['codigoUsuario'], // ¡NUEVO: Campo Año!
        ];

        $linea = implode(';', $datos_producto);

        $carpeta_txt = '../UserEspe/';
        if (!file_exists($carpeta_txt)) {
            mkdir($carpeta_txt, 0777, true);
        }
        //cambio nombre de archivo txt a guardar
        $nombre_archivo_txt = 'UserRegistrados.txt';
        $archi = fopen($carpeta_txt . $nombre_archivo_txt, 'a');

        if ($archi) {
            fputs($archi, $linea . PHP_EOL);
            fclose($archi);
            echo '<h3>+ Producto Cargado TX.</h3>';
            header('refresh:3;url=../UserAdmin/cargar.php');
            exit();
        } else {
            echo '<h3>Error al abrir el archivo para guardar los datos.</h3>';
        }

   
} else {
    echo '<h3>¡ERROR: Faltan datos o no ingreso correctamente !</h3>';
    // Para depuración, puedes descomentar estas líneas:
    // echo '<pre>';
    // print_r($_POST);
    // print_r($_FILES);
    // echo '</pre>';
    header('refresh:4;url=../UserAdmin/cargar.php');
    exit();
}
?>