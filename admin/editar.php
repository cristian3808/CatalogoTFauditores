<?php
require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['nombre']) && !empty($_POST['descripcion']) && !empty($_POST['categoria']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];

        // Si se sube una nueva imagen
        if (!empty($_FILES['foto']['name'])) {
            $img = $_FILES['foto'];
            $tmpname = $img['tmp_name'];
            $fecha = date("YmdHis");
            $foto = $fecha . ".jpg";
            $destino = "../assets/img/" . $foto;

            if (move_uploaded_file($tmpname, $destino)) {
                $query = mysqli_query($conexion, "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', imagen='$foto', id_categoria=$categoria WHERE id=$id");
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            // Si no se sube una nueva imagen, solo actualiza los otros campos
            $query = mysqli_query($conexion, "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', id_categoria=$categoria WHERE id=$id");
        }

        if ($query) {
            header('Location: productos.php');
            exit;
        } else {
            echo "Error al actualizar el producto: " . mysqli_error($conexion);
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Cargar el producto a editar
$id = intval($_GET['id']);
$productQuery = mysqli_query($conexion, "SELECT * FROM productos WHERE id=$id");
$product = mysqli_fetch_assoc($productQuery);

include("includes/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Activo</title>
    <style>
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 1em;
            width: 500px; /* Ajusta este valor para cambiar el ancho */
        }
        body, html {
            height: 100%;
        }
        .full-height {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-custom {
            background-color: #2fa74c; /* Color de fondo */
            border-color: #2fa74c; /* Color del borde */
            color: white; /* Color del texto */
        }

            .btn-custom:hover {
                background-color: #20cd4a; /* Mantener el mismo color de fondo */
                border-color: #2fa74c; /* Mantener el mismo color de borde */
                color: white; /* Mantener el texto blanco */
        }

            .btn-white {
                background-color: white; /* Color de fondo */
                border: 1px solid #0c5a46; /* Color del borde */
                color: #0c5a46; /* Color del texto */
        }

            .btn-white:hover {
                background-color: #fff; /* Cambia el fondo al color deseado */
                border: 1px solid #0c5a46; /* Mantener el color del borde */
                color: white; /* Cambiar el texto a blanco */
        }

    </style>
</head>
<body>
<div class="container full-height">
    <div class="form-container">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <h5>EDITAR </h5><hr>

            <!-- Fila para Nombre y Categoría -->
            <div class="row">
<!-- Campo de Nombre -->
<div class="col-md-6">
    <div class="form-group">
        <label for="nombre"><strong>Nombre:</strong></label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($product['nombre']); ?>"  pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,22}" minlength="3" maxlength="22" title="Solo se permiten letras y espacios, entre 3 y 22 caracteres" required>
    </div>
</div>


                <!-- Campo de Categoría -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="categoria"><strong>Categorías:</strong></label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <option value="">Seleccionar</option>
                            <?php
                            $categories = mysqli_query($conexion, "SELECT * FROM categorias");
                            while ($category = mysqli_fetch_assoc($categories)) {
                                $selected = $category['id'] == $product['id_categoria'] ? 'selected' : '';
                                echo "<option value='{$category['id']}' $selected>{$category['categoria']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion"><strong>Descripción:</strong></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($product['descripcion']); ?></textarea>
            </div>

            <div class="alert alert-warning" role="alert">
                <div class="d-flex gap-4">
                    <span><i class="fa-solid fa-circle-exclamation icon-warning"></i></span>
                    <div class="d-flex flex-column gap-2">
                        <p class="mb-0"><strong>Imagen (dejar vacío si no desea cambiarla)</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="foto"><strong>Imagen</strong></label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div>

            <button type="submit" class="btn btn-custom">Guardar cambios</button>
            <a href="productos.php" class="btn btn-white text-success">Cancelar</a>
        </form>
    </div>
</div>

</body>
</html>