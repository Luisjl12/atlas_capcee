document.addEventListener('DOMContentLoaded', function () {
    const btnEliminar = document.getElementById('btnEliminarSeleccionadas');

    btnEliminar.addEventListener('click', function () {
        const url = btnEliminar.getAttribute('data-url');
        const checkboxes = document.querySelectorAll('.galeria-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length === 0) {
            alert('No hay fotos seleccionadas');
            return;
        }

        if (!confirm('¿Estás seguro de eliminar las fotos seleccionadas?')) return;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                ids.forEach(id => {
                    const card = document.getElementById(`foto-${id}`);
                    if (card) card.remove();
                });
                
            } else {
                alert(data.message || 'Error al eliminar');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error de red');
        });
    });
});
