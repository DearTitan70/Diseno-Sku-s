document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-marcar-error")) {
            const id = e.target.getAttribute("data-id");
            const nombre = e.target.getAttribute("data-nombre");
            document.getElementById("resultadoMarcarError").innerText = "";

            document.getElementById("motivoError").value = "";
            document.getElementById("idOriginal").value = id;
            document.getElementById("nombreOriginal").value = nombre;
            document.getElementById("marcarTodos").checked = false;

            document.getElementById("modalError").style.display = "flex";
        }
    });

    // Cerrar modal
    document.getElementById("cerrarModalError").onclick = function () {
        document.getElementById("modalError").style.display = "none";
        document.getElementById("resultadoMarcarError").innerText = "";
    };

    // Enviar formulario
    document.getElementById("formError").onsubmit = function (e) {
        e.preventDefault();
        const id = document.getElementById("idOriginal").value;
        const nombre = document.getElementById("nombreOriginal").value;
        const marcar_todos = document.getElementById("marcarTodos").checked;
        const motivo_error = document.getElementById("motivoError").value;

        fetch("../backend/marcar_error.php", {
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id: id,
                nombre: nombre,
                marcar_todos: marcar_todos,
                motivo_error: motivo_error
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("resultadoMarcarError").innerText = "Material marcado como error";
                setTimeout(() => {
                    document.getElementById("modalError").style.display = "none";
                    location.reload();
                }, 1200);
            } else {
                document.getElementById("resultadoMarcarError").innerText = "Error: " + (data.message || "No se pudo marcar como error.");
            }
        })
        .catch(err => {
            document.getElementById("resultadoMarcarError").innerText = "Error de red o servidor.";
        });
    };
});