/**
 * Sticky Table Header Global (dentro de .table-container)
 * El header flotante se ajusta dinámicamente a las columnas visibles y sus anchos,
 * y se sincroniza con la lógica de ocultar columnas por categoría.
 * Además, copia los estilos computados de cada th para que el header fijo
 * luzca exactamente igual que el original.
 */

document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('skuTable');
    const tableContainer = document.querySelector('.table-container');
    if (!table || !tableContainer) return;

    let floatingHeader = null;

    function copyComputedStyles(sourceElem, targetElem) {
        const computed = window.getComputedStyle(sourceElem);
        [
            'backgroundColor', 'color', 'fontWeight', 'fontSize', 'fontFamily',
            'border', 'borderTop', 'borderRight', 'borderBottom', 'borderLeft',
            'padding', 'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft',
            'textAlign', 'verticalAlign', 'boxShadow', 'letterSpacing', 'textTransform',
            'minWidth', 'maxWidth', 'whiteSpace', 'position', 'zIndex', 'boxSizing'
        ].forEach(prop => {
            targetElem.style[prop] = computed[prop];
        });
    }

    function createFloatingHeader() {
        if (floatingHeader) floatingHeader.remove();

        const thead = table.querySelector('thead');
        floatingHeader = document.createElement('table');
        floatingHeader.className = table.className + ' sticky-table-header-global';
        floatingHeader.style.position = 'absolute';
        floatingHeader.style.top = '0';
        floatingHeader.style.left = '0';
        floatingHeader.style.zIndex = '1000';
        floatingHeader.style.background = 'white';
        floatingHeader.style.pointerEvents = 'none';
        floatingHeader.style.width = table.offsetWidth + 'px';
        floatingHeader.style.tableLayout = 'fixed';
        floatingHeader.appendChild(thead.cloneNode(true));
        tableContainer.appendChild(floatingHeader);

        syncFloatingHeaderColumns();
    }

    function syncFloatingHeaderColumns() {
        if (!floatingHeader) return;
        const origThs = table.querySelectorAll('thead th');
        const floatThs = floatingHeader.querySelectorAll('thead th');
        // Usar los td de la primera fila para obtener el ancho real de las columnas
        const firstRowTds = table.querySelector('tbody tr') ? table.querySelector('tbody tr').children : null;

        origThs.forEach((th, i) => {
            // Sincroniza display (visible/oculto)
            floatThs[i].style.display = th.style.display;

            // Si hay al menos una fila en tbody, usa el ancho del td correspondiente
            let width;
            if (firstRowTds && firstRowTds[i]) {
                const rect = firstRowTds[i].getBoundingClientRect();
                width = rect.width;
            } else {
                // Si no hay filas, usa el ancho del th
                const rect = th.getBoundingClientRect();
                width = rect.width;
            }
            floatThs[i].style.width = width + 'px';
            floatThs[i].style.minWidth = width + 'px';
            floatThs[i].style.maxWidth = width + 'px';

            // Copia los estilos computados
            copyComputedStyles(th, floatThs[i]);
            // Asegura box-sizing igual
            floatThs[i].style.boxSizing = 'border-box';
        });
        // Ajusta el ancho total de la tabla flotante
        floatingHeader.style.width = table.offsetWidth + 'px';
    }

    function updateFloatingHeaderPosition() {
        if (!floatingHeader) return;
        const rect = tableContainer.getBoundingClientRect();
        const headerHeight = floatingHeader.offsetHeight || 40;
        if (rect.bottom < headerHeight || rect.top > window.innerHeight) {
            floatingHeader.style.display = 'none';
            return;
        }
        let offset = 0;
        if (rect.top < 0) {
            offset = -rect.top;
            if (offset + headerHeight > rect.height) {
                offset = rect.height - headerHeight;
            }
        }
        floatingHeader.style.display = '';
        floatingHeader.style.transform = `translateY(${offset}px)`;
        floatingHeader.style.width = table.offsetWidth + 'px';
        floatingHeader.style.left = tableContainer.scrollLeft + 'px';
    }

    function observeColumnVisibilityChanges() {
        const observer = new MutationObserver(() => {
            syncFloatingHeaderColumns();
        });
        observer.observe(table.querySelector('thead'), { attributes: true, subtree: true, attributeFilter: ['style'] });
        new ResizeObserver(() => {
            syncFloatingHeaderColumns();
        }).observe(table);
    }

    window.addEventListener('scroll', function () {
        updateFloatingHeaderPosition();
    });

    tableContainer.addEventListener('scroll', function () {
        if (floatingHeader) {
            floatingHeader.scrollLeft = tableContainer.scrollLeft;
            floatingHeader.style.left = tableContainer.scrollLeft + 'px';
        }
    });

    window.addEventListener('resize', function () {
        if (floatingHeader) {
            syncFloatingHeaderColumns();
            updateFloatingHeaderPosition();
        }
    });

    createFloatingHeader();
    updateFloatingHeaderPosition();
    observeColumnVisibilityChanges();

    window.actualizarColumnasPorCategoria = (function (originalFn) {
        return function () {
            originalFn.apply(this, arguments);
            syncFloatingHeaderColumns();
        };
    })(window.actualizarColumnasPorCategoria || function () {});
});