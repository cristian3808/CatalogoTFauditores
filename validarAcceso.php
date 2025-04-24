<?php
require_once "config/conexion.php";
session_start(); // Activar sesión para guardar el estado de acceso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = mysqli_real_escape_string($conexion, $_POST['accessCode']);

    // Consulta para verificar el código de acceso
    $query = "SELECT * FROM proyectos WHERE codigo_acceso = '$codigo' AND codigo_acceso_activo = 1";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['acceso_valido'] = true; // Guardar en sesión que el acceso es válido
        header('Location: catalogo.php'); // Redirigir al catálogo
        exit();
    } else {
        echo "<script>alert('Código incorrecto o inactivo. Intenta nuevamente.'); window.location.href = 'index.php';</script>";
        exit();
    }
}
?>
