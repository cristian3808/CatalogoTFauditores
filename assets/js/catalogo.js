// Función para alternar la visibilidad de la barra de búsqueda
function toggleSearch() {
    const input = document.querySelector('.search-input');
    const button = document.querySelector('.search-btn');
    input.classList.toggle('active');
    button.classList.toggle('active');
}

// Función para mostrar/ocultar la descripción
function toggleDescription(id) {
    const dots = document.getElementById(`dots-${id}`);
    const more = document.getElementById(`more-${id}`);
    const btn = event.target;

    if (dots.style.display === "none") {
        dots.style.display = "inline";
        btn.innerText = "Leer más";
        more.style.display = "none";
    } else {
        dots.style.display = "none";
        btn.innerText = "Leer menos";
        more.style.display = "inline";
    }
}

// Función para volver al principio de la página
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

let timeout;

// Función para reiniciar el temporizador de inactividad
function resetInactivityTimer() {
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        window.location.href = 'index.php'; // Redirigir a index.php después de 2 minutos
    }, 120000); // 2 minutos (120,000 ms)
}

// Escuchar eventos de actividad en la página
window.onload = resetInactivityTimer;
window.onmousemove = resetInactivityTimer;
window.onkeypress = resetInactivityTimer;
window.onscroll = resetInactivityTimer;
