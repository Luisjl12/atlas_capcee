document.addEventListener('DOMContentLoaded', () => {
    const btnAbrir = document.getElementById('btn-filtros');
    const modal = document.getElementById('modal-filtros');
    const cerrar = document.getElementById('cerrar-modal');
    const form = document.getElementById('form-filtros');

    btnAbrir.addEventListener('click', () => modal.style.display = 'block');
    cerrar.addEventListener('click', () => modal.style.display = 'none');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        modal.style.display = 'none';

        const params = new URLSearchParams({
            macroregion: document.getElementById('filtro-macroregion').value,
            nivel: document.getElementById('filtro-nivel').value,
            superficie: document.getElementById('filtro-superficie').value
        });

        console.log('Filtros enviados:', {
        macroregion: document.getElementById('filtro-macroregion').value,
        nivel: document.getElementById('filtro-nivel').value,
        superficie: document.getElementById('filtro-superficie').value
        });


        fetch('/filtrar-planteles?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                // Limpia el mapa
                map.eachLayer(layer => {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });

                // Pinta los nuevos marcadores
                console.log('Planteles recibidos:', planteles);
                function normalizarEstado(estado) {
        estado = (estado || '').toLowerCase().trim();
        return estado === 'en_revision' ? 'revision' : estado;
    }

    function crearIconoPorEstado(estado) {
        return L.divIcon({
            className: 'custom-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30],
            html: `<i class="bi bi-geo-alt-fill marker-icon ${normalizarEstado(estado)}"></i>`
        });
    }

                planteles.forEach(p => {
                if (p.latitud && p.longitud) {
                 L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {
                icon: crearIconoPorEstado(p.estatus_plantel)
                 }).addTo(map).bindPopup(`
                <b>${p.nombre_escuela}</b><br>
                CCT: ${p.cct}<br>
                Estado: ${normalizarEstado(p.estatus_plantel)}<br>
                Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                
                <a href="/planteles/${p.id}" target="_blank">Ver ficha completa</a>
               `);
    }
});
            })
            .catch(async error => {
             const respuesta = await error.response?.json?.();
             console.error('Error al aplicar filtros:', respuesta?.detalle || error);
            });

    });
});
