document.addEventListener('DOMContentLoaded', () => {
    const btnAbrir = document.getElementById('btn-filtros');
    const modal = document.getElementById('modal-filtros');
    const cerrar = document.getElementById('cerrar-modal');
    const form = document.getElementById('form-filtros');

    btnAbrir.addEventListener('click', () => modal.style.display = 'block');
    cerrar.addEventListener('click', () => modal.style.display = 'none');

   form.addEventListener('submit', function (e) {
    e.preventDefault();

    const macroregion = document.getElementById('filtro-macroregion').value;
    const microregion = document.getElementById('filtro-microregion').value;
    const municipio = document.getElementById('filtro-municipio').value;
    const nivel = document.getElementById('filtro-nivel').value;
    const superficie = document.getElementById('filtro-superficie').value;

    // 🔹 Validación: debe haber una región (macro, micro o municipio) Y un nivel
    if (!(macroregion || microregion || municipio)) {
        alert('Debes seleccionar al menos una región (macro, micro o municipio).');
        return;
    }

    if (!nivel) {
        alert('Debes seleccionar un nivel antes de filtrar.');
        return;
    }

    modal.style.display = 'none';

    const params = new URLSearchParams({
        macroregion,
        microregion,
        municipio,
        nivel,
        superficie
    });

    console.log('Filtros enviados:', { macroregion, microregion, municipio, nivel, superficie });

    fetch('/filtrar-planteles?' + params.toString())
        .then(res => res.json())
        .then(respuesta => {
            const planteles = respuesta.data || [];

            if (planteles.length === 0) {
                alert('No se encontraron planteles con los filtros seleccionados.');
                return;
            }

            // 🔹 Limpia el mapa solo de marcadores (sin borrar el tileLayer base)
            map.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

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
        .catch(error => {
            console.error('Error al aplicar filtros:', error);
        });
});
});
