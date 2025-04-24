<?php
require_once "../config/conexion.php";
//  Sentencia de Sql
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conexion, "DELETE FROM productos WHERE id='$id'");

    if ($query) {
        //  Redirige a la página de categorías con un contexto de éxito
        header('Location: productos.php?success=1');
        exit;
    } else {
        //  Redirige a la página de categorías con un contexto de error
        header('Location: productos.php?error=1');
        exit;
    }
}
?>