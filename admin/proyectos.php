<?php
require_once "../config/conexion.php";
include("includes/header.php");

// Procesar el formulario de nuevo proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['proyecto'])) {
        $proyecto = $_POST['proyecto'];
        $codigo_acceso = isset($_POST['codigo_acceso']) ? $_POST['codigo_acceso'] : null;
        $codigo_acceso_activo = isset($_POST['codigo_acceso_activo']) ? 1 : 0;

        if (isset($_POST['edit-id'])) {
            $id = $_POST['edit-id'];
            $query = "UPDATE proyectos SET proyecto = ?, codigo_acceso = ?, codigo_acceso_activo = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, 'ssii', $proyecto, $codigo_acceso, $codigo_acceso_activo, $id);
        } else {
            $query = "INSERT INTO proyectos (proyecto, codigo_acceso, codigo_acceso_activo) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, 'ssi', $proyecto, $codigo_acceso, $codigo_acceso_activo);
        }

        if ($stmt) {
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                echo 'success';
            } else {
                echo 'Error al ejecutar la consulta: ' . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo 'Error al preparar la consulta: ' . mysqli_error($conexion);
        }
        exit;
    }
}

// Consulta para obtener los proyectos
$query = "SELECT * FROM proyectos";
$result = mysqli_query($conexion, $query);
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
            overflow: hidden;
        }
        .rounded-btn {
            border-radius: 20px;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #ffffff;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #d4edda;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
        /* Estilizar el contenedor del checkbox */
        .form-check {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        /* Estilizar el checkbox */
        .form-check-input {
            accent-color: #28a745; /* Color verde para el checkbox */
            width: 13px;
            height: 15px;
            margin-right: 10px;
            cursor: pointer;
        }

        /* Estilizar la etiqueta */
        .form-check-label {
            font-size: 16px;
            color: #333;
            cursor: pointer;
        }

        /* Agregar un estilo adicional al pasar el ratón sobre la etiqueta */
        .form-check-label:hover {
            color: #28a745;
            text-decoration: underline;
        }

            /* Redondear las esquinas de la tabla */
        .custom-table {
            border-radius: 10px;
            overflow: hidden;
        }

        /* Cambiar el color de fondo del thead a verde claro */
        thead {
            background-color: #b0e57c; /* Verde claro */
        }

        /* Si necesitas un borde en las esquinas de las celdas */
        .table-bordered td, .table-bordered th {
            border: 1px solid #ddd;
        }

        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
    <title>Proyectos</title>
</head>
<body>
<br>
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="#" class="btn btn-sm rounded-btn" style="background-color: #2fa74c; color: white;" data-toggle="modal" data-target="#nuevoProyectoModal">
            <i class="fa fa-plus-square" aria-hidden="true"></i>      
            <strong>NOMBRE PROYECTO</strong>
        </a>
    </div>
    <!-- Modal para agregar nuevo proyecto -->
    <div class="modal fade" id="nuevoProyectoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoProyectoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoProyectoModalLabel">Nuevo Proyecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="nuevoProyectoForm" method="POST">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="proyecto"><strong>Nombre del Proyecto:</strong></label>
                            <input type="text" class="form-control" id="proyecto" name="proyecto" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios (5-30 caracteres)" minlength="5" maxlength="30" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="codigo_acceso"><strong>Código de Acceso:</strong></label>
                            <input type="text" class="form-control" id="codigo_acceso" name="codigo_acceso" pattern="^(?=.*[A-Za-z])(?=.*\d)(?!.*(?:12345678|codigo1)).{6,8}$" title="El código debe tener entre 6 y 8 caracteres, incluir al menos una letra y un número, y no debe ser secuencias simples como '12345678' o 'codigo1'." minlength="6" maxlength="8" required>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <div class="d-flex gap-4">
                            <span><i class="fa-solid fa-circle-exclamation icon-info"></i></span>
                            <div class="d-flex flex-column gap-2">
                                <p class="mb-0"><strong>Estado del proyecto. Activa el checkbox!</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="codigo_acceso_activo" name="codigo_acceso_activo" checked>
                        <label class="form-check-label" for="codigo_acceso_activo">Activo o Inactivo.</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="color: white;">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar proyecto -->
<div class="modal fade" id="editarProyectoModal" tabindex="-1" role="dialog" aria-labelledby="editarProyectoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProyectoModalLabel">Editar Proyecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editarProyectoForm">
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="edit-id"> <!-- Campo oculto para el ID -->

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-proyecto"><strong>Nombre del Proyecto</strong></label>
                            <input type="text" class="form-control" id="edit-proyecto" name="proyecto" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios (5-30 caracteres)" minlength="5" maxlength="30" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-codigo_acceso"><strong>Código de Acceso</strong></label>
                            <input type="text" class="form-control" id="edit-codigo_acceso" name="codigo_acceso" pattern="^(?=.*[A-Za-z])(?=.*\d)(?!.*(?:12345678|codigo1)).{6,8}$" title="El código debe tener entre 6 y 8 caracteres, incluir al menos una letra y un número, y no debe ser secuencias simples como '12345678' o 'codigo1'." minlength="6" maxlength="8" required>
                        </div>
                    </div>
                    
                        <div class="alert alert-info" role="alert">
                            <div class="d-flex gap-4">
                                <span><i class="fa-solid fa-circle-exclamation icon-info"></i></span>
                                <div class="d-flex flex-column gap-2">
                                    <p class="mb-0"><strong>Activa el estado del proyecto con el checkbox!</strong></p>
                                </div>
                            </div>
                        </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="edit-codigo_acceso_activo" name="codigo_acceso_activo">
                        <label class="form-check-label" for="edit-codigo_acceso_activo">Inactivo o Activo</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="color: white;">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-lg custom-table">
            <thead style="background-color: #16A34A;" class="text-white">
                <tr>
                    <th>PROYECTO</th>
                    <th>CÓDIGO DE ACCESO</th>
                    <th>ESTADO DEL CÓDIGO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
                <tbody>
                    <?php while ($data = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-dark hover:bg-[#E1EEE2]">
                            <td class="py-1"><?php echo htmlspecialchars($data['proyecto']); ?></td>
                            <td class="py-1"><?php echo htmlspecialchars($data['codigo_acceso']); ?></td>
                            <td class="py-1"><?php echo $data['codigo_acceso_activo'] ? 'Activo' : 'Inactivo'; ?></td>
                            <td class="text-center py-1">
                                <button type="button" class="btn btn-link text-warning" title="Editar" data-toggle="modal" data-target="#editarProyectoModal"
                                    data-id="<?php echo $data['id']; ?>"
                                    data-proyecto="<?php echo htmlspecialchars($data['proyecto']); ?>"
                                    data-codigo_acceso="<?php echo htmlspecialchars($data['codigo_acceso']); ?>"
                                    data-codigo_acceso_activo="<?php echo $data['codigo_acceso_activo'] ? 'checked' : ''; ?>">
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
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Cuando se abre el modal de edición
        $('#editarProyectoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var proyecto = button.data('proyecto');
            var codigo_acceso = button.data('codigo_acceso');
            var codigo_acceso_activo = button.data('codigo_acceso_activo') === 'checked';

            // Llenar los campos del modal con los datos actuales del proyecto
            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#edit-proyecto').val(proyecto);
            modal.find('#edit-codigo_acceso').val(codigo_acceso);
            modal.find('#edit-codigo_acceso_activo').prop('checked', codigo_acceso_activo);
        });

        // Envío del formulario de nuevo proyecto
        $('#nuevoProyectoForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post('proyectos.php', formData, function() {
                location.reload();  // Recargar la página
            });
        });

        // Envío del formulario de edición de proyecto
        $('#editarProyectoForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post('proyectos.php', formData, function() {
                location.reload();  // Recargar la página
            });
        });
    });
</script>
</body>
</html>