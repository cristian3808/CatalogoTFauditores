<?php
require_once "config/conexion.php";
session_start();

// Verificar si el acceso es válido
if (!isset($_SESSION['acceso_valido']) || $_SESSION['acceso_valido'] !== true) {
    echo "<script>alert('No tienes permiso para acceder a esta página.'); window.location.href = 'index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <meta name="author Sergio Quiroga">
    <link rel="icon" href="assets/estaticas/TF.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/index.css">
    <title>TF Catálogo</title>   
</head>
<body style="background-color: #E1EEE2;">
    <!-- Navegación -->
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg" style="background-color: #FFF;">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <a href="index.php">
                    <img src="assets/estaticas/TF.png" class="logo" alt="Logo" style="width: 200px; height: 90px;" />
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="?category=all" class="nav-link text-success fw-bold">TODO</a>
                        </li>
                        <?php
                        $query = mysqli_query($conexion, "SELECT * FROM categorias");
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                            <li class="nav-item">
                                <a href="?category=<?php echo $data['categoria']; ?>" class="nav-link text-success fw-bold"><?php echo $data['categoria']; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="d-flex align-items-center d-none d-lg-block">
                    <a href="admin/" class="btn btn-success fw-bold text-white zoom-btn">Iniciar sesión</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Contenido -->
    <section class="py-5">
        <div class="container">
            <form class="d-flex mb-4 ms-auto" method="GET">
                <div class="search-container">
                    <input class="form-control search-input" type="search" placeholder="Buscar" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    
                    <!-- Botón cuadrado de búsqueda -->
                    <button class="btn btn-success fw-bold search-btn" type="button" onclick="toggleSearch()" style="width: 50px; height: 50px; padding: 0; border-radius: 10px;">
                        <i class="bi bi-search"></i>
                    </button>
                    
                    <button class="btn btn-success fw-bold search-submit" type="submit">Buscar</button>
                </div>
            </form>

            <div class="row row-cols-1  row-cols-sm-3 row-cols-lg-5 gy-4 gx-4">
                <?php
                $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';
                $category = isset($_GET['category']) ? mysqli_real_escape_string($conexion, $_GET['category']) : '';

                $query = "SELECT p.*, c.categoria 
                          FROM productos p 
                          INNER JOIN categorias c ON c.id = p.id_categoria 
                          WHERE (p.nombre LIKE '%$search%' OR p.descripcion LIKE '%$search%')";

                if ($category != '' && $category != 'all') {
                    $query .= " AND c.categoria = '$category'";
                }

                $query = mysqli_query($conexion, $query);
                $result = mysqli_num_rows($query);

                if ($result > 0) {
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="assets/img/<?php echo $data['imagen']; ?>" class="card-img-top" alt="<?php echo $data['nombre']; ?>">
                                <div class="card-body">
                                    <h6 class="card-title text-center"><?php echo $data['nombre']; ?></h6>
                                    <p class="card-text">
                                        <?php
                                        $desc = $data['descripcion'];
                                        $short_desc = substr($desc, 0, 100);
                                        echo $short_desc;
                                        if (strlen($desc) > 100) { ?>
                                            <span id="dots-<?php echo $data['id']; ?>">...</span>
                                            <span id="more-<?php echo $data['id']; ?>" style="display: none;"><?php echo substr($desc, 100); ?></span>
                                            <button onclick="toggleDescription(<?php echo $data['id']; ?>)" class="btn btn-link p-0" style="color: #28a745;">Leer más</button>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else {
                    echo '<div class="col-12 text-center"><p>No existe ningun Material / Activo con ese nombre.</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <button onclick="scrollToTop()" class="btn btn-success position-fixed bottom-0 end-0 m-3 btn-scroll-top" style="z-index: 1000;">
        <i class="bi bi-arrow-up-circle"></i>
    </button>
<script src="/assets/js/catalogo.js"></script>
</body>
</html>
