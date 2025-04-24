<?php
require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST)) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $categoria = intval($_POST['categoria']);
        $img = $_FILES['foto'];
        $name = $img['name'];
        $tmpname = $img['tmp_name'];
        $fecha = date("YmdHis");
        $foto = $fecha . ".jpg";
        $destino = "../assets/img/" . $foto;

        // Verificar si el producto ya existe
        $checkQuery = mysqli_query($conexion, "SELECT * FROM productos WHERE nombre = '$nombre'");
        if (mysqli_num_rows($checkQuery) > 0) {
            echo "<script>alert('El producto con ese nombre ya existe.');</script>";
        } else {
            // Insertar el nuevo producto
            $query = mysqli_query($conexion, "INSERT INTO productos(nombre, descripcion, imagen, id_categoria) VALUES ('$nombre', '$descripcion', '$foto', $categoria)");
            if ($query) {
                if (move_uploaded_file($tmpname, $destino)) {
                    header('Location: productos.php');
                } else {
                    echo "<script>alert('Error al mover la imagen.');</script>";
                }
            } else {
                echo "<script>alert('Error al insertar el producto.');</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author Sergio Quiroga">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
        .custom-header {
            background-color: #2fa74c;
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
        .img-thumbnail {
            width: 50px;
            height: 50px; /* Ajusta esto si necesitas una altura específica */
            object-fit: cover; /* Asegura que la imagen mantenga el aspecto recortado si es necesario */
        }
        .page-item.disabled .page-link {
            cursor: default;
            pointer-events: none;
        }
</style>
</head>
<body><br>
<!-- Btn de creacion de un nuevo registro  -->
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="#" class="btn btn-sm rounded-btn" style="background-color: #2fa74c; color: white; transition: background-color 0.3s, transform 0.3s;" id="abrirProducto" data-toggle="modal" data-target="#productos" onmouseover="this.style.backgroundColor='#2fa74c'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#2fa74c'; this.style.transform='scale(1)';">
            <i class="fa fa-plus-square" aria-hidden="true"></i>       
            <strong>NUEVO</strong>
        </a>
    </div>
<!-- Este es el formulario de las opciones de las categorias -->
<form method="GET" class="form-inline mb-3">
    <input type="text" name="search" class="form-control mr-2 rounded-input" placeholder="Buscar" value="<?php echo $search; ?>">
    <select name="categoria" class="form-control mr-2 rounded-input">
        <option value="">Todas las Categorías</option>
        <?php
        $categories = mysqli_query($conexion, "SELECT * FROM categorias");
        while ($category = mysqli_fetch_assoc($categories)) {
            $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $category['id']) ? 'selected' : '';
            echo "<option value='{$category['id']}' {$selected}>{$category['categoria']}</option>";
        }
        ?>
    </select>
    <select name="limit" class="form-control mr-2 rounded-input">
        <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
        <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
        <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
    </select>
    <button type="submit" class="btn" style="background-color: #1b5929; color: white; border-color: #1b5929; border-radius: 4px;"><i class="fa fa-filter" aria-hidden="true"></i>Filtrar</button>
</form>
<!-- Esta es la consilta de MySql para que me triga los datos relacionados con Activos - Herramientas -->
<?php
$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : '';
$categoryFilter = $categoria ? "AND p.id_categoria = $categoria" : "";

$query = mysqli_query($conexion, "SELECT p.*, c.categoria 
                                  FROM productos p 
                                  INNER JOIN categorias c ON c.id = p.id_categoria 
                                  WHERE p.nombre LIKE '%$search%' $categoryFilter 
                                  ORDER BY p.id DESC 
                                  LIMIT $limit OFFSET $offset");

$totalQuery = mysqli_query($conexion, "SELECT COUNT(*) AS total 
                                       FROM productos p 
                                       WHERE p.nombre LIKE '%$search%' $categoryFilter");

?>
<!-- Tabla para que muestre el contenido de los activows o herramientas -->
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover custom-table">
                <thead class="custom-header">
                    <tr>
                        <th style="width: 10%;">IMAGEN</th>
                        <th style="width: 25%;">NOMBRE</th>
                        <th style="width: 35%;">DESCRIPCIÓN</th>
                        <th style="width: 20%;">CATEGORÍA</th>
                        <th style="width: 10%;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $isWhiteRow = true; // Para alternar el color de las filas
                    while ($data = mysqli_fetch_assoc($query)) { 
                        $rowClass = $isWhiteRow ? '' : 'table-success'; // Cambiar el color de la fila
                        $isWhiteRow = !$isWhiteRow; // Alternar el color
                    ?>
                        <tr class="<?php echo $rowClass; ?> text-center"> <!-- Añadir text-center a la fila -->
                            <td style="width: 10%;"><img class="img-thumbnail" src="../assets/img/<?php echo $data['imagen']; ?>" alt="Imagen"></td>
                            <td style="width: 25%;"><?php echo $data['nombre']; ?></td>
                            <td style="width: 35%;"><?php echo $data['descripcion']; ?></td>
                            <td style="width: 20%;"><?php echo $data['categoria']; ?></td>
                            <td style="width: 10%;">
                                <div class="d-flex justify-content-center align-items-center">
                                <form method="post" action="eliminarPro.php?accion=pro&id=<?php echo $data['id']; ?>" class="d-inline eliminar" id="deleteForm_<?php echo $data['id']; ?>">
                                    <button type="button" class="btn btn-link" style="background: none; border: none; color: #dc143c; cursor: pointer;" title="Eliminar" onclick="showConfirmDeleteModal('<?php echo htmlspecialchars($data['nombre']); ?>', '<?php echo $data['id']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                    <!-- Btn de Editar un activo -->
                                    <a href="editar.php?id=<?php echo $data['id']; ?>" style="color: #ffdf00; margin-left: 10px;" title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div id="confirmDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmación de Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">¿Está seguro de que desea eliminar este elemento?</p>
                <p class="text-center font-weight-bold" id="deleteCategoryName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning text-white" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success text-white" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Botones para la navegación de la pagina -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Botón Anterior -->
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
        <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>&categoria=<?php echo $categoria; ?>">Anterior</a>
    </li>
        <?php
        // Limites
        $maxVisiblePages = 10;
        $halfVisible = floor($maxVisiblePages / 2);
        $startPage = max(1, $page - $halfVisible);
        $endPage = min($totalPages, $page + $halfVisible);

        // Ajustar los límites si hay muchas páginas
        if ($endPage - $startPage < $maxVisiblePages - 1) {
            if ($page < $halfVisible) {
                $endPage = min($totalPages, $maxVisiblePages);
            } else {
                $startPage = max(1, $totalPages - $maxVisiblePages + 1);
            }
        }

        // Mostrar primeros números de página
        if ($startPage > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=1&limit=' . $limit . '&search=' . urlencode($search) . '">1</a></li>';
            if ($startPage > 2) {
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
            }
        }

        // Mostrar números de página alrededor de la página actual
        for ($i = $startPage; $i <= $endPage; $i++) {
            echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                    <a class="page-link" href="?page=' . $i . '&limit=' . $limit . '&search=' . urlencode($search) . '&categoria=' . $categoria . '">' . $i . '</a>
                  </li>';
        }

        // Mostrar últimos números de página
        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&limit=' . $limit . '&search=' . urlencode($search) . '">' . $totalPages . '</a></li>';
        }
        ?>
        <!-- Botón Siguiente -->
        <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
        <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>&categoria=<?php echo $categoria; ?>">Siguiente</a>
        </li>
    </ul>
</nav>
<div id="productos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white"  style="box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);">
                <h5 class="modal-title" id="title"><strong>NUEVO REGISTRO</strong></h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Campo de Nombre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre"><strong>Nombre:</strong></label>
                                <input type="text" class="form-control rounded-input" id="nombre" name="nombre" placeholder="FILTRO DE ARENA" required 
                                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{4,23}" 
                                    minlength="4" 
                                    maxlength="23" 
                                    title="Solo se permiten letras y espacios, entre 4 y 23 caracteres">
                            </div>
                        </div>

                        <!-- Campo de Categoría -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria"><strong>Categorías:</strong></label>
                                <select class="form-control rounded-input" id="categoria" name="categoria" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    $categories = mysqli_query($conexion, "SELECT * FROM categorias");
                                    while ($category = mysqli_fetch_assoc($categories)) {
                                        echo "<option value='{$category['id']}'>{$category['categoria']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion"><strong>Descripción:</strong></label>
                        <textarea class="form-control rounded-input" id="descripcion" name="descripcion" rows="3" minlength="10" maxlength="250" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="foto"><strong>Imagen:</strong></label>
                        <input type="file" class="form-control-file rounded-input" id="foto" name="foto" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success text-success border-success" style="background-color: white; border-color: green; color: green;" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Funcionalidades que se llevan a cabo de esta pagina -->
<script>
    let deleteFormId = '';

    function showConfirmDeleteModal(categoryName, id) {
        // Muestra el nombre de la categoría en el modal
        document.getElementById('deleteCategoryName').innerText = categoryName;
        // Guarda el ID del formulario para la eliminación
        deleteFormId = id;
        // Muestra el modal
        $('#confirmDeleteModal').modal('show');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        // Redirige al formulario de eliminación
        document.getElementById('deleteForm_' + deleteFormId).submit();
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>