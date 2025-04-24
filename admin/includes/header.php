<?php session_start();
if (empty($_SESSION['id'])) {
    header('Location: ./');
} ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description">
    <meta name="author Sergio Quiroga">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="icon" href="../../assets/estaticas/TF.ico" type="image/x-icon">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <title>TF Admin</title>
    <style>
        .logo{
            width: 500px;
            height: 90px;
            padding: auto;
        }
        .bg-sidebar {
            background-color: #2fa74c !important; /* Color de la barra lateral */
        }
        .bg-topbar {
            background-color: #1b5929 !important; /* Color de la barra superior */
        }
        .text-white {
            color: white !important;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-sidebar sidebar sidebar-dark accordion" id="accordionSidebar"><br>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio.php">
                <!-- <div class="sidebar-brand-text mx-3 text-white">TF AUDITORES</div> -->
                <img src="../assets/estaticas/logo.png" alt="" class="logo">
            </a><br>
            <!-- Opciones del menu -->
                <li class="nav-item active">
                    <a class="nav-link text-white" href="inicio.php">
                        <i class="fas fa-fw fa-home"></i>
                        <span>INICIO</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="proyectos.php">
                        <i class="fas fa-briefcase"></i>
                        <span>PROYECTOS</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="categorias.php">
                        <i class="fas fa-tags"></i>
                        <span>CATEGORÍAS</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="productos.php">
                    <i class="fa fa-folder-open" aria-hidden="true"></i>
                        <span>ACTIVOS - MATERIALES</span></a>
                </li>
        </ul>
<!-- Contenido -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Contenido principal -->
    <div id="content">
    <nav class="navbar navbar-expand navbar-light bg-topbar">
        <!-- Barra de navegación de la barra superior -->
        <ul class="navbar-nav ml-auto">
            <!-- Inf del usuario -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white small"><?php echo $_SESSION['nombre']; ?></span>
                    <span class="avatar"><i class="fas fa-user"></i></span>
                </a>
                <!-- Inf del usuario -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../salir.php">
                        <i class="fa-solid fa-arrow-left-from-bracket"></i>
                        Salir
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="container-fluid">