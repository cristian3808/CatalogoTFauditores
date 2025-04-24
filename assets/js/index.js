document.getElementById("togglePassword").addEventListener("click", function () {
    const passwordInput = document.getElementById("accessCode");
    const eyeIcon = document.getElementById("eye-icon");
    
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.setAttribute("stroke", "lime"); // Cambia el color al activarse
    } else {
        passwordInput.type = "password";
        eyeIcon.setAttribute("stroke", "currentColor"); // Vuelve al color original
    }
});