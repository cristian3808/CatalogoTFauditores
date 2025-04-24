<?php
require_once "../config/conexion.php";
if (isset($_POST)) {
    // Verifica si se ha enviado el campo 'nombre'
    if (!empty($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        
        // Comprobar si se está editando
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $query = mysqli_query($conexion, "UPDATE categorias SET categoria='$nombre' WHERE id='$id'");
        } else {
            $query = mysqli_query($conexion, "INSERT INTO categorias(categoria) VALUES ('$nombre')");
        }
        
        // Comprobar si la consulta fue exitosa
        if ($query) {
            header('Location: categorias.php');
            exit; // Asegúrate de salir después de redirigir
        } else {
            // Manejo de error
            echo "Error al guardar la categoría: " . mysqli_error($conexion);
        }
    } else {
        echo "";
    }
}
include("includes/header.php");
 
// Configuración de paginación
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Cantidad de registros por página
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Búsqueda
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($page - 1) * $limit;

// Consulta con paginación y búsqueda
$query = mysqli_query($conexion, "SELECT * FROM categorias WHERE categoria LIKE '%$search%' ORDER BY id DESC LIMIT $limit OFFSET $offset");
$totalQuery = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM categorias WHERE categoria LIKE '%$search%'");
$totalCount = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalCount / $limit);

$alertMessage = '';
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $alertMessage = '<div class="alert alert-success" role="alert">
                        <div class="d-flex gap-4">
                            <span><i class="fa-solid fa-circle-check icon-success"></i></span>
                            <div>La categoría ha sido eliminada correctamente.</div>
                        </div>
                    </div>';
} elseif (isset($_GET['error']) && $_GET['error'] == '1') {
    $alertMessage = '<div class="alert alert-danger" role="alert">
                        <div class="d-flex gap-4">
                            <span><i class="fa-solid fa-circle-xmark icon-error"></i></span>
                            <div>Hubo un error al eliminar la categoría.</div>
                        </div>
                    </div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            overflow: hidden;
        }
        
        .rounded-input {
            border-radius: 20px;
        }
        .rounded-btn {
            border-radius: 20px;
        }
        .pagination .page-item.active .page-link {
            background-color: #1B5929;
            color: white;
        }
        .pagination .page-link {
            border: 2px solid #0c5a46;
            color: #0c5a46;
        }
        .pagination .page-link:hover {
            background-color: #0c5a46;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #ffffff;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #d4edda;
        }
        .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
        }
        .modal-content {
            border-radius: 0.3rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            margin: auto;
        }

        .modal-body {
            padding: 2rem;
        }
    </style>
    <title>Categorías</title>
</head>
<body>
<br>
<div class="container">
<?php if ($alertMessage) echo $alertMessage; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <a href="#" class="btn btn-sm rounded-btn" style="background-color: #2fa74c; color: white; transition: background-color 0.3s, transform 0.3s;" data-toggle="modal" data-target="#categorias" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
        <i class="fa fa-plus-square" aria-hidden="true"></i>       
        <strong>NUEVO</strong>
    </a>
</div>

    <form method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2 rounded-input" placeholder="Buscar" value="<?php echo $search; ?>">
        <select name="limit" class="form-control mr-2 rounded-input">
            <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
            <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
            <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
            <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
        </select>
        <button type="submit" class="btn" style="background-color: #1B5929; color: white;"><i class="fa fa-filter" aria-hidden="true"></i>Filtrar </button>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover custom-table">
                <thead class="custom-header">
                    <tr>
                        <th class="d-none">Id</th>
                        <th >NOMBRE</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td class="d-none"><?php echo $data['id']; ?></td>
                            <td><?php echo $data['categoria']; ?></td>
                            <td class="text-center" style="display: flex; justify-content: center; align-items: center;">
                                <!-- Icono de eliminación -->
                                <button type="button" class="btn btn-link text-danger" title="Eliminar" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $data['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Icono de edición -->
                                <button type="button" class="btn btn-link text-warning" title="Editar" style="margin-left: 10px;" data-toggle="modal" data-target="#categorias" onclick="setEditData(<?php echo $data['id']; ?>, '<?php echo $data['categoria']; ?>')">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <div id="categorias" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white text-center">
                    <h5 class="modal-title" id="title"><strong>NUEVA CATEGORÍA</strong></h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="categoriaForm" action="" method="POST" autocomplete="off">
                        <input type="hidden" name="id" id="categoriaId">
                        <div class="form-group">
                            <label for="nombre"><strong>Categoría:</strong></label>
                            <input id="nombre" class="form-control rounded-input" type="text" name="nombre" placeholder="MATERIALES" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios (5-30 caracteres)" minlength="5" maxlength="30" required>
                        </div>
                        <button class="btn btn-success  rounded-btn" type="submit">GUARDAR</button>
                    </form>
                </div>
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
                    <p class="text-center">¿Está seguro de que desea eliminar esta categoría?</p>
                    <p class="text-center font-weight-bold" id="deleteCategoryName"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning text-white" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success text-white" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function setEditData(id, nombre) {
        document.getElementById('categoriaId').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('title').innerText = 'EDITAR CATEGORÍA';
    }

    function clearForm() {
        document.getElementById('categoriaId').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('title').innerText = 'Nueva Categoria';
    }

$(document).ready(function() {
    var deleteId = null;

    // Mostrar modal de confirmación y almacenar el ID
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que disparó el modal
        deleteId = button.data('id'); // Extraer el ID de los datos del botón
        var categoryName = button.closest('tr').find('td').eq(1).text(); // Obtener el nombre de la categoría
        $('#deleteCategoryName').text(categoryName); // Mostrar el nombre en el modal
    });

    // Confirmar la eliminación
    $('#confirmDeleteBtn').on('click', function() {
        if (deleteId) {
            window.location.href = 'eliminar.php?accion=cli&id=' + deleteId;
        }
    });
});
</script>
</body>
</html>