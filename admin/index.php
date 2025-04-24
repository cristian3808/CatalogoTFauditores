<?php
// Inicia la sesión
session_start();

// Verifica si la sesión ya está activa, si es así redirige al usuario a 'productos.php'
if (!empty($_SESSION['active'])) {
    header('location: productos.php');
} else {
    // Verifica si se ha enviado un formulario POST
    if (!empty($_POST)) {
        $alert = ''; // Variable para mostrar alertas

        // Verifica si los campos de usuario y clave están vacíos
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            // Alerta de error si los campos están vacíos
            $alert = '<div class="fixed top-4 right-4 bg-red-700 text-white py-2 px-4 rounded-lg shadow-lg flex items-center space-x-4" role="alert">
                        <span class="font-medium">Ingrese usuario y contraseña</span>
                      </div>';
        } else {
            // Incluye la conexión a la base de datos
            require_once "../config/conexion.php";

            // Protege contra inyecciones SQL con 'mysqli_real_escape_string'
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
            // Encripta la clave usando md5
            $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));

            // Realiza la consulta a la base de datos para verificar si el usuario y la clave son correctos
            $query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$user' AND clave = '$clave'");
            
            // Cierra la conexión a la base de datos
            mysqli_close($conexion);

            // Verifica si hubo coincidencias en la base de datos
            $resultado = mysqli_num_rows($query);

            // Si hay coincidencias, inicia sesión y almacena datos del usuario
            if ($resultado > 0) {
                $dato = mysqli_fetch_array($query); // Obtiene los datos del usuario
                $_SESSION['active'] = true; // Activa la sesión
                $_SESSION['id'] = $dato['id']; // Guarda el ID del usuario
                $_SESSION['nombre'] = $dato['nombre']; // Guarda el nombre del usuario
                $_SESSION['user'] = $dato['usuario']; // Guarda el nombre de usuario

                // Redirige a la página de inicio después de iniciar sesión correctamente
                header('Location: inicio.php');
            } else {
                // Muestra una alerta de error si la contraseña es incorrecta
                $alert = '<div id="alert" class="fixed top-4 right-4 bg-red-700 text-white py-2 px-4 rounded-lg shadow-lg flex items-center space-x-4" role="alert">
                            <span class="font-medium">Contraseña incorrecta!</span>
                          </div>';
                session_destroy(); // Destruye la sesión si las credenciales son incorrectas
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Iniciar Sesión</title>
    <meta name="author" content="Sergio Quiroga">
    <link rel="shortcut icon" href="../assets/img/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../assets/estaticas/TF.ico" type="image/x-icon">
</head>
<body class="bg-gradient-to-r min-h-screen flex items-center justify-center" style="background-color: #E1EEE2;">
    <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg flex">
        <!-- Formulario de inicio de sesión -->
        <div class="w-1/2 p-8">
            <div class="text-center mb-4">
                <!-- Muestra la alerta si existe -->
                <?php echo (isset($alert)) ? $alert : ''; ?>
            </div>
            <!-- Formulario con método POST -->
            <form method="POST" action="" autocomplete="off">
                <!-- Campo de usuario -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="usuario">Usuario</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500" id="usuario" name="usuario" maxlength="15" required>
                </div>
                <!-- Campo de contraseña -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="clave">Contraseña</label>
                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500" id="clave" name="clave" maxlength="12" required>
                </div>
                <!-- Botón de enviar -->
                <button type="submit" class="w-full bg-green-600 hover:bg-lime-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transform transition hover:scale-105">
                    Ingresar
                </button>
            </form>
            <hr class="my-2">
            <a href="../index.php" class="flex items-center justify-center text-green-600 hover:text-lime-500 text-s hover:scale-105">
                <i class="fa fa-home mr-2"></i> Regresar al inicio
            </a>
        </div>
        <!-- Carrusel de imágenes -->
        <div class="relative w-1/2 hidden lg:block overflow-hidden">
            <div class="flex transition-transform duration-500" id="carousel">
                <!-- Imagen 1 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index.webp" alt="Imagen 1" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 2 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index2.jpg" alt="Imagen 2" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 3 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index3.jpg" alt="Imagen 3" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 4 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index4.jpg" alt="Imagen 1" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 5 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index5.jpg" alt="Imagen 1" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 6 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index6.jpg" alt="Imagen 1" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 7 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index7.jpg" alt="Imagen 1" class="object-cover w-full h-full rounded-r-lg">
                </div>
                <!-- Imagen 8 -->
                <div class="w-full flex-shrink-0">
                    <img src="../assets/estaticas/index8.jpg" alt="Imagen 1" class="object-cover w-full h-full rounded-r-lg">
                </div>             
            </div>
        </div>
    </div>
<script>
// Índice actual del carrusel
let currentIndex = 0;
const slides = document.querySelectorAll('#carousel > div'); // Todas las diapositivas
const totalSlides = slides.length;

// Función para mover las diapositivas
function moveSlide(direction) {
    currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
    document.getElementById('carousel').style.transform = `translateX(-${currentIndex * 100}%)`;
}

// Movimiento automático del carrusel cada 4 segundos
setInterval(() => {
    moveSlide(1); 
}, 4000);

// Desvanece la alerta después de 5 segundos
setTimeout(function() {
    document.getElementById('alert').style.opacity = 0;
    setTimeout(function() {
        document.getElementById('alert').style.display = 'none';
    }, 600); // Espera después de cambiar la opacidad para una transición suave
}, 5000); // 5 segundos
</script>
</body>
</html>
