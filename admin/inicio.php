<?php
require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST)) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $img = $_FILES['foto'];
        $name = $img['name'];
        $tmpname = $img['tmp_name'];
        $fecha = date("YmdHis");
        $foto = $fecha . ".jpg";
        $destino = "../assets/img/" . $foto;
        $query = mysqli_query($conexion, "INSERT INTO productos(nombre, descripcion, imagen, id_categoria) VALUES ('$nombre', '$descripcion', '$foto', $categoria)");
        if ($query) {
            if (move_uploaded_file($tmpname, $destino)) {
                header('Location: productos.php');
            }
        }
    }
}

// Configuración de paginación
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Cantidad de registros por página
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Búsqueda
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($page - 1) * $limit;

// Consulta con paginación y búsqueda
$query = mysqli_query($conexion, "SELECT p.*, c.categoria FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria WHERE p.nombre LIKE '%$search%' ORDER BY p.id DESC LIMIT $limit OFFSET $offset");
$totalQuery = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM productos WHERE nombre LIKE '%$search%'");
$totalCount = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalCount / $limit);

include("includes/header.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author Sergio Quiroga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .custom-header {
            background-color: #0c5a46;
            color: white;
            text-align: center;
        }
        .custom-table {
            border-radius: 10px;
            overflow: hidden; /* Para que los bordes redondeados se vean bien */
        }
        .rounded-input {
            border-radius: 20px; /* Bordes redondeados */
        }
        .rounded-btn {
            border-radius: 20px; /* Bordes redondeados para los botones */
        }
        .pagination .page-item.active .page-link {
            background-color: #0c5a46; /* Fondo verde cuando está activo */
            color: white; /* Texto blanco */
        }
        .pagination .page-link {
            border: 2px solid #0c5a46; /* Bordes verdes */
            color: #0c5a46; /* Texto verde */
        }
        .pagination .page-link:hover {
            background-color: #0c5a46; /* Fondo verde al pasar el mouse */
            color: white; /* Texto blanco al pasar el mouse */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #ffffff; /* Fondo blanco para filas impares */
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #d4edda; /* Fondo verde claro para filas pares */
        }
        th {
            white-space: nowrap; /* Evitar que el texto se divida en varias líneas */
            overflow: hidden; /* Ocultar el texto que se desborda */
            text-overflow: ellipsis; /* Añadir "..." al final si el texto es demasiado largo */
        }
        td {
            max-width: 150px; /* Ajusta el ancho máximo según sea necesario */
            overflow: hidden; /* Ocultar el texto que se desborda */
            text-overflow: ellipsis; /* Añadir "..." al final si el texto es demasiado largo */
            white-space: nowrap; /* Evitar que el texto se divida en varias líneas */
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body><br>
<!-- Este es la seecion de las 3 tarjetas y el texto que lleva debajo de ellas un pequeño parrafo de que trata la empresa -->
<div class="container">
    <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
        <!-- Primer bloque -->
        <div class="col">
            <div class="lc-block card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" lc-helper="background" style="background-image: url('../assets/estaticas/inicioo.webp'); background-size: cover;">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow">
                    <div class="lc-block pt-5 mt-5 mb-4">
                        <div editable="rich">
                            <h2 class="display-6 lh-1 fw-bold">TF</h2>
                            <p>Especializada en el área de hidrocarburos, dedicada a ofrecer servicios de auditoría y asesoría de alta calidad. Con un equipo experto y una profunda comprensión del sector, TF Auditores se compromete a garantizar el cumplimiento normativo y la optimización de procesos en la industria energética.</p>
                        </div>
                    </div>
                    <ul class="lc-block d-flex list-unstyled mt-auto ms-auto">
                        <a class="btn btn-link btn-sm text-white" href="proyectos.php" role="button">PROYECTOS</a>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Segundo bloque -->
        <div class="col">
            <div class="lc-block card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" lc-helper="background" style="background:url('../assets/estaticas/iniActivo.webp') center / cover no-repeat;">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow">
                    <div class="lc-block pt-5 mt-5 mb-4">
                        <div editable="rich">
                            <h2 class="display-6 lh-1 fw-bold">TF ACTIVOS</h2>
                            <p>Se enfoca en la gestión y auditoría de activos en el sector de hidrocarburos, asegurando que los recursos de la empresa sean utilizados de manera eficiente y conforme a las regulaciones vigentes. Su experiencia en el manejo de activos críticos garantiza la integridad operativa y maximiza el valor a lo largo del ciclo de vida de los proyectos energéticos.</p>
                        </div>
                    </div>
                    <ul class="lc-block d-flex list-unstyled mt-auto ms-auto">
                        <a class="btn btn-link btn-sm text-white" href="productos.php" role="button">ACTIVOS</a>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Tercer bloque -->
        <div class="col">
            <div class="lc-block card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" lc-helper="background" style="background:url('../assets/estaticas/categorias.webp') center / cover no-repeat;">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow">
                    <div class="lc-block pt-5 mt-5 mb-4">
                        <div editable="rich">
                            <h2 class="display-6 lh-1 fw-bold">TF CATEGORÍAS</h2>
                            <p>Cuenta con una gama de herramientas especializadas que facilitan la gestión eficiente de estos activos, desde software de auditoría y monitoreo hasta tecnologías de mantenimiento predictivo, garantizando un control integral y proactivo de los recursos.</p>
                        </div>
                    </div>
                    <ul class="lc-block d-flex list-unstyled mt-auto ms-auto">
                        <a class="btn btn-link btn-sm text-white" href="categorias.php" role="button">CATEGORÍAS</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <p>TF Auditores se posiciona como una empresa líder en el sector de hidrocarburos, destacándose por su compromiso con la excelencia en la auditoría y gestión de activos. La empresa entiende que los activos son el corazón de cualquier operación energética, por lo que se enfoca en asegurar su rendimiento óptimo a lo largo de su ciclo de vida.
    En cuanto a los activos físicos, TF Auditores se especializa en la evaluación y supervisión de infraestructura, maquinaria y equipos fundamentales para las operaciones.</p>
</div>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>