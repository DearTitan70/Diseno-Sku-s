
/**
 * Mejoras para visualizar_cargas.php:
 * - Headers solo al expandir un grupo
 * - Agrupación jerárquica: primero por tipo, luego por fecha
 * - Filtros de rango de fechas, ID y usuario
 * - Exportación de todos los registros filtrados
 * - Exportación sin columnas ID, usuario, fecha_creacion ni Acciones
 */
function formatCurrency(value) {
    if (value === null || value === undefined || value === "") return "";
    let num = Number(value);
    if (isNaN(num)) return value;
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(num);
}

document.addEventListener("DOMContentLoaded", function () {
    // Elementos
    const tbody = document.querySelector("#tabla-visualizar-cargas");
    const filterDateFrom = document.getElementById("filterDateFrom");
    const filterDateTo = document.getElementById("filterDateTo");
    // NUEVOS FILTROS
    const filterName = document.getElementById("filterName");
    const filterUsuario = document.getElementById("filterUsuario");
    const exportBtn = document.getElementById("exportarXLSX");
    let allData = [];

    // Define los headers de la tabla (ajusta los nombres si es necesario)
    const headers = [
        "TIPO DE PRODUCTO", "LINEA DEL PRODUCTO", "ID", "SAP", "YEAR", "MES", "OCASION_DE_USO", "NOMBRE", "MODULO", "TEMPORADA", "CAPSULA", "CLIMA", "TIENDA",
        "CLASIFICACION", "CLUSTER", "PROVEEDOR", "CATEGORIAS", "SUBCATEGORIAS", "DISENO", "DESCRIPCION", "MANGA",
        "TIPO_MANGA", "PUNO", "CAPOTA", "ESCOTE", "LARGO", "CUELLO", "TIRO", "BOTA", "CINTURA", "SILUETA", "CIERRE",
        "GALGA", "TIPO_GALGA", "COLOR_FDS", "NOM_COLOR", "GAMA", "PRINT", "TALLAS", "TIPO_TEJIDO", "TIPO_DE_FIBRA",
        "BASE_TEXTIL", "DETALLES", "SUB_DETALLES", "GRUPO", "INSTRUCCION_DE_LAVADO_1", "INSTRUCCION_DE_LAVADO_2",
        "INSTRUCCION_DE_LAVADO_3", "INSTRUCCION_DE_LAVADO_4", "INSTRUCCION_DE_LAVADO_5", "INSTRUCCION_BLANQUEADO_1",
        "INSTRUCCION_BLANQUEADO_2", "INSTRUCCION_BLANQUEADO_3", "INSTRUCCION_BLANQUEADO_4", "INSTRUCCION_BLANQUEADO_5",
        "INSTRUCCION_SECADO_1", "INSTRUCCION_SECADO_2", "INSTRUCCION_SECADO_3", "INSTRUCCION_SECADO_4",
        "INSTRUCCION_SECADO_5", "INSTRUCCION_PLANCHADO_1", "INSTRUCCION_PLANCHADO_2", "INSTRUCCION_PLANCHADO_3",
        "INSTRUCCION_PLANCHADO_4", "INSTRUCCION_PLANCHADO_5", "INSTRUCC_CUIDADO_TEXTIL_PROF_1",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_2", "INSTRUCC_CUIDADO_TEXTIL_PROF_3", "INSTRUCC_CUIDADO_TEXTIL_PROF_4",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_5", "COMPOSICION_1", "%_COMP_1", "COMPOSICION_2", "%_COMP_2", "COMPOSICION_3",
        "%_COMP_3", "COMPOSICION_4", "%_COMP_4", "TOT_COMP", "FORRO", "COMP_FORRO_1", "%_FORRO_1", "COMP_FORRO_2",
        "%_FORRO_2", "TOT_FORRO", "RELLENO", "COMP_RELLENO_1", "%_RELLENO_1", "COMP_RELLENO_2", "%_RELLENO_2",
        "TOT_RELLENO", "XX", "USUARIO", "FECHA DE CREACION", "PRECIO DE COMPRA", "COSTO", "PRECIO DE VENTA","Acciones"
    ];

    // Indices de las columnas a excluir en la exportación
    const excludeHeaders = ["ID", "usuario", "fecha_creacion", "Acciones", "TIPO", "PRECIO DE COMPRA", "COSTO", "PRECIO DE VENTA"];
    const exportHeaders = headers.filter(h => !excludeHeaders.includes(h));

    // Mapeo de headers a campos de registro 
    const headerToField = {
        "TIPO": "tipo",
        "LINEA": "LINEA",
        "ID": "id",
        "SAP": "SAP",
        "YEAR": "YEAR",
        "MES": "MES",
        "OCASION_DE_USO": "OCASION_DE_USO",
        "NOMBRE": "NOMBRE",
        "MODULO": "MODULO",
        "TEMPORADA": "TEMPORADA",
        "CAPSULA": "CAPSULA",
        "CLIMA": "CLIMA",
        "TIENDA": "TIENDA",
        "CLASIFICACION": "CLASIFICACION",
        "CLUSTER": "CLUSTER",
        "PROVEEDOR": "PROVEEDOR",
        "CATEGORIAS": "CATEGORIAS",
        "SUBCATEGORIAS": "SUBCATEGORIAS",
        "DISENO": "DISENO",
        "DESCRIPCION": "DESCRIPCION",
        "MANGA": "MANGA",
        "TIPO_MANGA": "TIPO_MANGA",
        "PUNO": "PUNO",
        "CAPOTA": "CAPOTA",
        "ESCOTE": "ESCOTE",
        "LARGO": "LARGO",
        "CUELLO": "CUELLO",
        "TIRO": "TIRO",
        "BOTA": "BOTA",
        "CINTURA": "CINTURA",
        "SILUETA": "SILUETA",
        "CIERRE": "CIERRE",
        "GALGA": "GALGA",
        "TIPO_GALGA": "TIPO_GALGA",
        "COLOR_FDS": "COLOR_FDS",
        "NOM_COLOR": "NOM_COLOR",
        "GAMA": "GAMA",
        "PRINT": "PRINT",
        "TALLAS": "TALLAS",
        "TIPO_TEJIDO": "TIPO_TEJIDO",
        "TIPO_DE_FIBRA": "TIPO_DE_FIBRA",
        "BASE_TEXTIL": "BASE_TEXTIL",
        "DETALLES": "DETALLES",
        "SUB_DETALLES": "SUB_DETALLES",
        "GRUPO": "GRUPO",
        "INSTRUCCION_DE_LAVADO_1": "INSTRUCCION_DE_LAVADO_1",
        "INSTRUCCION_DE_LAVADO_2": "INSTRUCCION_DE_LAVADO_2",
        "INSTRUCCION_DE_LAVADO_3": "INSTRUCCION_DE_LAVADO_3",
        "INSTRUCCION_DE_LAVADO_4": "INSTRUCCION_DE_LAVADO_4",
        "INSTRUCCION_DE_LAVADO_5": "INSTRUCCION_DE_LAVADO_5",
        "INSTRUCCION_BLANQUEADO_1": "INSTRUCCION_BLANQUEADO_1",
        "INSTRUCCION_BLANQUEADO_2": "INSTRUCCION_BLANQUEADO_2",
        "INSTRUCCION_BLANQUEADO_3": "INSTRUCCION_BLANQUEADO_3",
        "INSTRUCCION_BLANQUEADO_4": "INSTRUCCION_BLANQUEADO_4",
        "INSTRUCCION_BLANQUEADO_5": "INSTRUCCION_BLANQUEADO_5",
        "INSTRUCCION_SECADO_1": "INSTRUCCION_SECADO_1",
        "INSTRUCCION_SECADO_2": "INSTRUCCION_SECADO_2",
        "INSTRUCCION_SECADO_3": "INSTRUCCION_SECADO_3",
        "INSTRUCCION_SECADO_4": "INSTRUCCION_SECADO_4",
        "INSTRUCCION_SECADO_5": "INSTRUCCION_SECADO_5",
        "INSTRUCCION_PLANCHADO_1": "INSTRUCCION_PLANCHADO_1",
        "INSTRUCCION_PLANCHADO_2": "INSTRUCCION_PLANCHADO_2",
        "INSTRUCCION_PLANCHADO_3": "INSTRUCCION_PLANCHADO_3",
        "INSTRUCCION_PLANCHADO_4": "INSTRUCCION_PLANCHADO_4",
        "INSTRUCCION_PLANCHADO_5": "INSTRUCCION_PLANCHADO_5",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_1": "INSTRUCC_CUIDADO_TEXTIL_PROF_1",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_2": "INSTRUCC_CUIDADO_TEXTIL_PROF_2",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_3": "INSTRUCC_CUIDADO_TEXTIL_PROF_3",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_4": "INSTRUCC_CUIDADO_TEXTIL_PROF_4",
        "INSTRUCC_CUIDADO_TEXTIL_PROF_5": "INSTRUCC_CUIDADO_TEXTIL_PROF_5",
        "COMPOSICION_1": "COMPOSICION_1",
        "%_COMP_1": "%_COMP_1",
        "COMPOSICION_2": "COMPOSICION_2",
        "%_COMP_2": "%_COMP_2",
        "COMPOSICION_3": "COMPOSICION_3",
        "%_COMP_3": "%_COMP_3",
        "COMPOSICION_4": "COMPOSICION_4",
        "%_COMP_4": "%_COMP_4",
        "TOT_COMP": "TOT_COMP",
        "FORRO": "FORRO",
        "COMP_FORRO_1": "COMP_FORRO_1",
        "%_FORRO_1": "%_FORRO_1",
        "COMP_FORRO_2": "COMP_FORRO_2",
        "%_FORRO_2": "%_FORRO_2",
        "TOT_FORRO": "TOT_FORRO",
        "RELLENO": "RELLENO",
        "COMP_RELLENO_1": "COMP_RELLENO_1",
        "%_RELLENO_1": "%_RELLENO_1",
        "COMP_RELLENO_2": "COMP_RELLENO_2",
        "%_RELLENO_2": "%_RELLENO_2",
        "TOT_RELLENO": "TOT_RELLENO",
        "XX": "XX",
        "PRECIO DE COMPRA": "precio_compra",
        "COSTO": "costo",
        "PRECIO DE VENTA": "precio_venta",
        // "usuario", "fecha_creacion", "Acciones" no se exportan
    };

    // Fetch y render inicial
    fetch("../api/obtener_historico.php")
        .then(response => response.json())
        .then(data => {
            allData = data;
            renderGroupedTable();
        })
        .catch(error => console.error("Error cargando empleados:", error));

    // Agrupa por tipo y luego por fecha
    function groupByTypeAndDate(data) {
        const groups = {};
        data.forEach(registro => {
            const tipo = registro.tipo || "Sin tipo";
            const fecha = registro.fecha_creacion.split(" ")[0];
            if (!groups[tipo]) groups[tipo] = {};
            if (!groups[tipo][fecha]) groups[tipo][fecha] = [];
            groups[tipo][fecha].push(registro);
        });
        return groups;
    }

    // NUEVA función de filtrado combinada
    function filterData(data) {
        const from = filterDateFrom.value;
        const to = filterDateTo.value;
        const NameValue = filterName && filterName.value ? filterName.value.trim() : "";
        const usuarioValue = filterUsuario && filterUsuario.value ? filterUsuario.value.trim().toLowerCase() : "";

        return data.filter(registro => {
            // Filtro por fecha
            const fecha = registro.fecha_creacion.split(" ")[0];
            if (from && fecha < from) return false;
            if (to && fecha > to) return false;
            // Filtro por ID (exacto o parcial)
            if (NameValue.toUpperCase() && !registro.NOMBRE.toString().includes(NameValue)) return false;
            // Filtro por usuario (parcial, insensible a mayúsculas)
            if (usuarioValue && (!registro.usuario || !registro.usuario.toLowerCase().includes(usuarioValue))) return false;
            return true;
        });
    }

    function createHeaderRow() {
        const headerRow = document.createElement("tr");
        headerRow.className = "dynamic-header";
        headers.forEach(header => {
            const th = document.createElement("th");
            th.textContent = header;
            headerRow.appendChild(th);
        });
        return headerRow;
    }

    function renderGroupedTable() {
        tbody.innerHTML = "";
        const filtered = filterData(allData);
        const groups = groupByTypeAndDate(filtered);

        if (Object.keys(groups).length === 0) {
            tbody.innerHTML = `<tr><td colspan="${headers.length}" class="no-results">No hay registros en el rango seleccionado</td></tr>`;
            return;
        }

        // Ordena tipos alfabéticamente
        Object.entries(groups).sort((a, b) => a[0].localeCompare(b[0])).forEach(([tipo, fechasObj]) => {
            // Fila de encabezado de grupo tipo
            const tipoRow = document.createElement("tr");
            tipoRow.className = "group-header tipo-header";
            tipoRow.innerHTML = `<td colspan="${headers.length}" style="background:#e0e0e0;font-weight:bold;cursor:pointer;">
                Tipo: ${tipo} <span style="float:right;">[+]</span>
            </td>`;
            tbody.appendChild(tipoRow);

            // Para manejar la expansión/colapso de fechas dentro del tipo
            let fechaRows = [];

            // Ordena fechas descendente
            Object.entries(fechasObj).sort((a, b) => b[0].localeCompare(a[0])).forEach(([fecha, registros]) => {
                // Fila de encabezado de grupo fecha
                const fechaRow = document.createElement("tr");
                fechaRow.className = "group-header fecha-header";
                fechaRow.innerHTML = `<td colspan="${headers.length}" style="background:white;font-weight:bold;cursor:pointer;">
                    Cargas del ${fecha} (${registros.length} registros) <span style="float:right;">[+]</span>
                </td>`;
                fechaRow.style.display = "none";
                tbody.appendChild(fechaRow);

                // Crea las filas de registros (inicialmente ocultas)
                const rows = [];
                registros.forEach(registro => {
                    const row = document.createElement("tr");
                    row.className = "grouped-row";
                    row.style.display = "none";
                    row.innerHTML = `
                        <td>${registro.tipo}</td>
                        <td>${registro.LINEA}</td>
                        <td>${registro.id}</td>
                        <td>${registro.SAP}</td>
                        <td>${registro.YEAR}</td>
                        <td>${registro.MES}</td>
                        <td>${registro.OCASION_DE_USO}</td>
                        <td>${registro.NOMBRE}</td>
                        <td>${registro.MODULO}</td>
                        <td>${registro.TEMPORADA}</td>
                        <td>${registro.CAPSULA}</td>
                        <td>${registro.CLIMA}</td>
                        <td>${registro.TIENDA}</td>
                        <td>${registro.CLASIFICACION}</td>
                        <td>${registro.CLUSTER}</td>
                        <td>${registro.PROVEEDOR}</td>
                        <td>${registro.CATEGORIAS}</td>
                        <td>${registro.SUBCATEGORIAS}</td>
                        <td>${registro.DISENO}</td>
                        <td>${registro.DESCRIPCION}</td>
                        <td>${registro.MANGA}</td>
                        <td>${registro.TIPO_MANGA}</td>
                        <td>${registro.PUNO}</td>
                        <td>${registro.CAPOTA}</td>
                        <td>${registro.ESCOTE}</td>
                        <td>${registro.LARGO}</td>
                        <td>${registro.CUELLO}</td>
                        <td>${registro.TIRO}</td>
                        <td>${registro.BOTA}</td>
                        <td>${registro.CINTURA}</td>
                        <td>${registro.SILUETA}</td>
                        <td>${registro.CIERRE}</td>
                        <td>${registro.GALGA}</td>
                        <td>${registro.TIPO_GALGA}</td>
                        <td>${registro.COLOR_FDS}</td>
                        <td>${registro.NOM_COLOR}</td>
                        <td>${registro.GAMA}</td>
                        <td>${registro.PRINT}</td>
                        <td>${registro.TALLAS}</td>
                        <td>${registro.TIPO_TEJIDO}</td>
                        <td>${registro.TIPO_DE_FIBRA}</td>
                        <td>${registro.BASE_TEXTIL}</td>
                        <td>${registro.DETALLES}</td>
                        <td>${registro.SUB_DETALLES}</td>
                        <td>${registro.GRUPO}</td>
                        <td>${registro.INSTRUCCION_DE_LAVADO_1}</td>
                        <td>${registro.INSTRUCCION_DE_LAVADO_2}</td>
                        <td>${registro.INSTRUCCION_DE_LAVADO_3}</td>
                        <td>${registro.INSTRUCCION_DE_LAVADO_4}</td>
                        <td>${registro.INSTRUCCION_DE_LAVADO_5}</td>
                        <td>${registro.INSTRUCCION_BLANQUEADO_1}</td>
                        <td>${registro.INSTRUCCION_BLANQUEADO_2}</td>
                        <td>${registro.INSTRUCCION_BLANQUEADO_3}</td>
                        <td>${registro.INSTRUCCION_BLANQUEADO_4}</td>
                        <td>${registro.INSTRUCCION_BLANQUEADO_5}</td>
                        <td>${registro.INSTRUCCION_SECADO_1}</td>
                        <td>${registro.INSTRUCCION_SECADO_2}</td>
                        <td>${registro.INSTRUCCION_SECADO_3}</td>
                        <td>${registro.INSTRUCCION_SECADO_4}</td>
                        <td>${registro.INSTRUCCION_SECADO_5}</td>
                        <td>${registro.INSTRUCCION_PLANCHADO_1}</td>
                        <td>${registro.INSTRUCCION_PLANCHADO_2}</td>
                        <td>${registro.INSTRUCCION_PLANCHADO_3}</td>
                        <td>${registro.INSTRUCCION_PLANCHADO_4}</td>
                        <td>${registro.INSTRUCCION_PLANCHADO_5}</td>
                        <td>${registro.INSTRUCC_CUIDADO_TEXTIL_PROF_1}</td>
                        <td>${registro.INSTRUCC_CUIDADO_TEXTIL_PROF_2}</td>
                        <td>${registro.INSTRUCC_CUIDADO_TEXTIL_PROF_3}</td>
                        <td>${registro.INSTRUCC_CUIDADO_TEXTIL_PROF_4}</td>
                        <td>${registro.INSTRUCC_CUIDADO_TEXTIL_PROF_5}</td>
                        <td>${registro.COMPOSICION_1}</td>
                        <td>${registro["%_COMP_1"]}</td>
                        <td>${registro.COMPOSICION_2}</td>
                        <td>${registro["%_COMP_2"]}</td>
                        <td>${registro.COMPOSICION_3}</td>
                        <td>${registro["%_COMP_3"]}</td>
                        <td>${registro.COMPOSICION_4}</td>
                        <td>${registro["%_COMP_4"]}</td>
                        <td>${registro.TOT_COMP}</td>
                        <td>${registro.FORRO}</td>
                        <td>${registro.COMP_FORRO_1}</td>
                        <td>${registro["%_FORRO_1"]}</td>
                        <td>${registro.COMP_FORRO_2}</td>
                        <td>${registro["%_FORRO_2"]}</td>
                        <td>${registro.TOT_FORRO}</td>
                        <td>${registro.RELLENO}</td>
                        <td>${registro.COMP_RELLENO_1}</td>
                        <td>${registro["%_RELLENO_1"]}</td>
                        <td>${registro.COMP_RELLENO_2}</td>
                        <td>${registro["%_RELLENO_2"]}</td>
                        <td>${registro.TOT_RELLENO}</td>
                        <td>${registro.XX}</td>
                        <td>${registro.usuario}</td>
                        <td>${registro.fecha_creacion}</td>
                        <td>${formatCurrency(registro.precio_compra)}</td>
                        <td>${formatCurrency(registro.costo)}</td>
                        <td>${formatCurrency(registro.precio_venta)}</td>
                        <td>
                            <a href="modificar_carga.php?id=${registro.id}" class="btn btn-modificar" title="Modificar este registro">
                                <i class="fas fa-edit"></i> Modificar
                            </a>
                        </td>
                    `;
                    row.style.display = "none";
                    rows.push(row);
                });

                // Evento para expandir/colapsar fecha
                fechaRow.addEventListener("click", function () {
                    const isCollapsed = fechaRow.getAttribute("data-collapsed") !== "false";
                    fechaRow.setAttribute("data-collapsed", !isCollapsed);
                    fechaRow.querySelector("span").textContent = isCollapsed ? "[-]" : "[+]";

                    // Busca si ya existe el header dinámico
                    let next = fechaRow.nextSibling;
                    let headerRow = null;
                    if (next && next.classList && next.classList.contains("dynamic-header")) {
                        headerRow = next;
                    }

                    if (isCollapsed) {
                        // Expandir: insertar header y mostrar filas
                        if (!headerRow) {
                            headerRow = createHeaderRow();
                            tbody.insertBefore(headerRow, fechaRow.nextSibling);
                        }
                        rows.forEach((row, idx) => {
                            row.style.display = "";
                            // Inserta la fila si no está ya en el DOM
                            if (row.parentNode !== tbody) {
                                tbody.insertBefore(row, headerRow.nextSibling ? headerRow.nextSibling : headerRow.nextSibling);
                            }
                        });
                    } else {
                        // Colapsar: ocultar filas y quitar header
                        rows.forEach(row => row.style.display = "none");
                        if (headerRow) {
                            tbody.removeChild(headerRow);
                        }
                    }
                });

                // Inserta las filas (pero ocultas)
                rows.forEach(row => tbody.appendChild(row));
                fechaRows.push(fechaRow);
            });

            // Evento para expandir/colapsar tipo
            tipoRow.addEventListener("click", function () {
                const isCollapsed = tipoRow.getAttribute("data-collapsed") !== "false";
                tipoRow.setAttribute("data-collapsed", !isCollapsed);
                tipoRow.querySelector("span").textContent = isCollapsed ? "[-]" : "[+]";
                fechaRows.forEach(fechaRow => {
                    fechaRow.style.display = isCollapsed ? "" : "none";
                    // Al colapsar tipo, también colapsa todos los subgrupos de fecha
                    if (!isCollapsed) {
                        fechaRow.setAttribute("data-collapsed", "true");
                        fechaRow.querySelector("span").textContent = "[+]";
                        // Oculta filas y header dinámico si están abiertos
                        let next = fechaRow.nextSibling;
                        if (next && next.classList && next.classList.contains("dynamic-header")) {
                            tbody.removeChild(next);
                        }
                        // Oculta filas de registros
                        let temp = fechaRow.nextSibling;
                        while (temp && temp.classList && temp.classList.contains("grouped-row")) {
                            temp.style.display = "none";
                            temp = temp.nextSibling;
                        }
                    }
                });
            });
        });
    }

    // EVENTOS para los filtros
    filterDateFrom.addEventListener("change", renderGroupedTable);
    filterDateTo.addEventListener("change", renderGroupedTable);
    if (filterName) filterName.addEventListener("input", renderGroupedTable);
    if (filterUsuario) filterUsuario.addEventListener("input", renderGroupedTable);

    // Exportar registros filtrados (sin ID, usuario, fecha_creacion ni Acciones)
    exportBtn.addEventListener("click", function () {
        const filtered = filterData(allData);
        if (filtered.length === 0) {
            alert("No hay registros para exportar en el rango seleccionado.");
            return;
        }
        // Crea una tabla temporal solo con los registros filtrados
        var tempTable = document.createElement("table");
        // Crea el thead dinámicamente solo con los headers exportables
        var thead = document.createElement("thead");
        var headerRow = document.createElement("tr");
        exportHeaders.forEach(header => {
            var th = document.createElement("th");
            th.textContent = header;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        tempTable.appendChild(thead);

        // Agrega las filas
        var tempTbody = document.createElement("tbody");
        filtered.forEach(registro => {
            var row = document.createElement("tr");
            let rowHtml = "";
            exportHeaders.forEach(header => {
                // Para campos como %_COMP_1, etc., acceder con corchetes
                let field = headerToField[header];
                let value = registro[field] !== undefined ? registro[field] : "";
                if (["precio_compra", "costo", "precio_venta"].includes(field)) {
                    value = formatCurrency(value);
                }
                rowHtml += `<td>${value}</td>`;
            });
            row.innerHTML = rowHtml;
            tempTbody.appendChild(row);
        });
        tempTable.appendChild(tempTbody);

        // Exporta con SheetJS
        var wb = XLSX.utils.table_to_book(tempTable, {sheet:"Historico Cargas"});
        XLSX.writeFile(wb, "historico_cargas.xlsx");
    });
});
