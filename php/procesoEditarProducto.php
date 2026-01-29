<?php
$ruta_base = '../ProDesc/';
$ruta_fotos = $ruta_base . 'fotop/';
$archivo_txt = $ruta_base . 'productos.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_editar = $_POST['idProducto'];
    $foto_final = $_POST['fotoActual'];

    // 1. Manejar nueva foto
    if (!empty($_FILES['fotoProducto']['name'])) {
        $extension = pathinfo($_FILES['fotoProducto']['name'], PATHINFO_EXTENSION);
        $nuevo_nombre_foto = $id_editar . "." . $extension;
        if (move_uploaded_file($_FILES['fotoProducto']['tmp_name'], $ruta_fotos . $nuevo_nombre_foto)) {
            $foto_final = $nuevo_nombre_foto;
        }
    }

    // 2. Recoger datos
    $descripcion = str_replace(';', ',', $_POST['descripcionProducto']);
    $codigo      = str_replace(';', ',', $_POST['codigoProducto']);
    $cantidad    = $_POST['cantidadProducto'];
    $p_unitario  = (float)$_POST['precioUnitario'];
    $p_base      = (float)$_POST['precioTotal']; // Precio Consumidor Final

    // 3. Recalcular las 6 Listas de Precios
    $p_consumidor_final = $p_base ;
    $p_limpio = $cantidad * $p_unitario;             // 0% desc
    $p_revendedor       = $p_limpio * 1.15;        // 21% desc
    $p_rev_10           = $p_limpio * 1.12;        // 24% desc
    $p_rev_50           = $p_limpio * 1.10;        // 26% desc
    $p_rev_100          = $p_limpio * 1.09;        // 27% desc
    $p_distribuidor     = $p_limpio * 1.08;  

    $cat_mat  = $_POST['categoriaMaterial'];
    $cat_cons = isset($_POST['categoriaConsumidor']) ? implode(", ", $_POST['categoriaConsumidor']) : "Ninguna";

    // 4. Actualizar el archivo
    $lineas = file($archivo_txt, FILE_IGNORE_NEW_LINES);
    $nuevas_lineas = [];

    foreach ($lineas as $linea) {
        $datos = explode(';', $linea);
        if ($datos[0] === $id_editar) {
            // Reconstruimos la línea con las 14 columnas en orden exacto
            $nuevas_lineas[] = implode(';', [
                $id_editar,           // 0
                $foto_final,          // 1
                $descripcion,         // 2
                $cantidad,            // 3
                $p_unitario,          // 4
                $p_consumidor_final,  // 5
                $p_revendedor,        // 6
                $p_rev_10,            // 7
                $p_rev_50,            // 8
                $p_rev_100,           // 9
                $p_distribuidor,      // 10
                $cat_mat,             // 11
                $cat_cons,            // 12
                $codigo               // 13
            ]);
        } else {
            $nuevas_lineas[] = $linea;
        }
    }

    // 5. Guardar
    if (file_put_contents($archivo_txt, implode(PHP_EOL, $nuevas_lineas) . PHP_EOL, LOCK_EX)) {
        echo "<script>
            window.location.href='../UserAdmin/cargar.php';
        </script>";
    } else {
        echo "Error al guardar los cambios.";
    }
}
?>