document.addEventListener("DOMContentLoaded", function () {
    fetch("validar_sesion.php")
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                window.location.href = "../login.html";
            }
        })
        .catch(error => console.error("Error al validar sesión:", error));
});
