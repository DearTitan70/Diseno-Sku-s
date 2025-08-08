function cargarMaterialesEnSelect() {
    fetch('../api/obtener_materiales.php')
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.reproSelect').forEach(select => {
                select.innerHTML = '<option value="">Seleccione</option>';
                if (Array.isArray(data)) {
                    const materialesUnicos = new Set();

                    data.forEach(material => {
                        materialesUnicos.add(material.material);
                    });

                    materialesUnicos.forEach(nombreMaterial => {
                        const opt = document.createElement('option');
                        opt.value = nombreMaterial;
                        opt.textContent = nombreMaterial;
                        select.appendChild(opt);
                    });
                }
            });
        })
        .catch(err => {
            console.error('Error cargando material:', err);
        });
}
