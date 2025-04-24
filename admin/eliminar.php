<?php
require_once "../config/conexion.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conexion, "DELETE FROM categorias WHERE id='$id'");

    if ($query) {
        //  Redirige a la página de categorías con un contexto de éxito
        header('Location: categorias.php?success=1');
        exit;
    } else {
        // Redirige a la página de categorías con un conexto de error 
        header('Location: categorias.php?error=1');
        exit;
    }
} else {
    // Redirige en caso de que no se pase un ID
    header('Location: categorias.php');
    exit;
}
?>