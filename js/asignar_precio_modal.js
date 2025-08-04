document.addEventListener("DOMContentLoaded", function () {
    // Abrir modal al hacer click en el botón
    document.body.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-asignar-precio")) {
            const id = e.target.getAttribute("data-id");
            const nombre = e.target.getAttribute("data-nombre");
            const precio_compra = e.target.getAttribute("data-precio_compra") || 0;
            const costo = e.target.getAttribute("data-costo") || 0;
            const precio_venta = e.target.getAttribute("data-precio_venta") || 0;
            const orden_compra = e.target.getAttribute("data-orden_compra") || 0;

            document.getElementById("precioIdOriginal").value = id;
            document.getElementById("precioNombreOriginal").value = nombre;
            document.getElementById("precioCompra").value = precio_compra;
            document.getElementById("precioCosto").value = costo;
            document.getElementById("precioVenta").value = precio_venta;
            document.getElementById("aplicarATodos").checked = false;
            document.getElementById("resultadoAsignarPrecio").innerText = "";
            document.getElementById("ordenCompra").value = orden_compra;

            // Si alguno de los precios es distinto de 0, muestra advertencia
            let mensaje = "";
            if (parseFloat(precio_compra) > 0 || parseFloat(costo) > 0 || parseFloat(precio_venta) > 0 || parseFloat(orden_compra) > 0) {
                mensaje = "Este registro ya tiene precios asignados. Puedes modificarlos aquí.";
            }
            document.getElementById("mensajePrecioExistente").innerText = mensaje;

            document.getElementById("modalAsignarPrecio").style.display = "flex";
        }
    });

    // Cerrar modal
    document.getElementById("cerrarModalAsignarPrecio").onclick = function () {
        document.getElementById("modalAsignarPrecio").style.display = "none";
        document.getElementById("resultadoAsignarPrecio").innerText = "";
    };

    // Enviar formulario
    document.getElementById("formAsignarPrecio").onsubmit = function (e) {
        e.preventDefault();
        const id = document.getElementById("precioIdOriginal").value;
        const nombre = document.getElementById("precioNombreOriginal").value;
        const precio_compra = parseFloat(document.getElementById("precioCompra").value) || 0;
        const costo = parseFloat(document.getElementById("precioCosto").value) || 0;
        const precio_venta = parseFloat(document.getElementById("precioVenta").value) || 0;
        const aplicarATodos = document.getElementById("aplicarATodos").checked;
        const orden_compra = document.getElementById("ordenCompra").value;

        fetch("../backend/asignar_precio.php", {
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id: id,
                nombre: nombre,
                precio_compra: precio_compra,
                costo: costo,
                precio_venta: precio_venta,
                aplicar_a_todos: aplicarATodos,
                orden_compra: orden_compra
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("resultadoAsignarPrecio").innerText = "¡Precio(s) asignado(s) con éxito!";
                setTimeout(() => {
                    document.getElementById("modalAsignarPrecio").style.display = "none";
                    location.reload();
                }, 1200);
            } else {
                document.getElementById("resultadoAsignarPrecio").innerText = "Error: " + (data.message || "No se pudo asignar el precio.");
            }
        })
        .catch(err => {
            document.getElementById("resultadoAsignarPrecio").innerText = "Error de red o servidor.";
        });
    };
});