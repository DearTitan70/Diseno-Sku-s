document.addEventListener('DOMContentLoaded', function () {
    const tableContainer = document.querySelector('.table-container');
    const topScrollbarContainer = document.getElementById('top-scrollbar-container');
    const topScrollbarTrack = topScrollbarContainer.querySelector('.custom-scrollbar-track');
    const topScrollbarThumb = document.getElementById('top-scrollbar-thumb');

    function updateThumb() {
        const scrollWidth = tableContainer.scrollWidth;
        const clientWidth = tableContainer.clientWidth;
        const scrollLeft = tableContainer.scrollLeft;
        const thumbWidth = Math.max(clientWidth * clientWidth / scrollWidth, 40); // min width
        const maxScrollLeft = scrollWidth - clientWidth;
        const left = maxScrollLeft > 0 ? (scrollLeft / maxScrollLeft) * (clientWidth - thumbWidth) : 0;
        topScrollbarThumb.style.width = thumbWidth + 'px';
        topScrollbarThumb.style.transform = `translateX(${left}px)`;
    }

    // Sincroniza el scroll de la tabla con la barra superior
    tableContainer.addEventListener('scroll', function () {
        updateThumb();
    });

    // Permite arrastrar el thumb para hacer scroll en la tabla
    let isDragging = false;
    let dragStartX = 0;
    let thumbStartLeft = 0;

    topScrollbarThumb.addEventListener('mousedown', function (e) {
        isDragging = true;
        dragStartX = e.clientX;
        thumbStartLeft = parseFloat(topScrollbarThumb.style.transform.replace('translateX(', '').replace('px)', '')) || 0;
        document.body.classList.add('scrollbar-dragging');
        e.preventDefault();
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        const dx = e.clientX - dragStartX;
        const clientWidth = tableContainer.clientWidth;
        const scrollWidth = tableContainer.scrollWidth;
        const thumbWidth = topScrollbarThumb.offsetWidth;
        const maxThumbLeft = clientWidth - thumbWidth;
        let newThumbLeft = Math.min(Math.max(thumbStartLeft + dx, 0), maxThumbLeft);
        const percent = newThumbLeft / maxThumbLeft;
        tableContainer.scrollLeft = percent * (scrollWidth - clientWidth);
        updateThumb();
    });

    document.addEventListener('mouseup', function () {
        if (isDragging) {
            isDragging = false;
            document.body.classList.remove('scrollbar-dragging');
        }
    });

    // Si el usuario hace click en la barra (track), mueve el thumb ahí
    topScrollbarTrack.addEventListener('mousedown', function (e) {
        if (e.target === topScrollbarThumb) return;
        const rect = topScrollbarTrack.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const clientWidth = tableContainer.clientWidth;
        const scrollWidth = tableContainer.scrollWidth;
        const thumbWidth = topScrollbarThumb.offsetWidth;
        const maxThumbLeft = clientWidth - thumbWidth;
        let newThumbLeft = Math.min(Math.max(clickX - thumbWidth / 2, 0), maxThumbLeft);
        const percent = newThumbLeft / maxThumbLeft;
        tableContainer.scrollLeft = percent * (scrollWidth - clientWidth);
        updateThumb();
    });

    // Si la tabla cambia de tamaño, actualiza el thumb
    window.addEventListener('resize', updateThumb);

    // Inicializa
    updateThumb();
});