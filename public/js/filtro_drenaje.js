document.addEventListener('DOMContentLoaded', () => {
    const btnDrenaje = document.getElementById('btn-filtros-drenaje');
    const modalDrenaje = document.getElementById('modal-drenaje');
    const cerrarDrenaje = modalDrenaje?.querySelector('.close');
    const formDrenaje = document.getElementById('form-drenaje');

    if (btnDrenaje && modalDrenaje) {
        btnDrenaje.addEventListener('click', () => modalDrenaje.style.display = 'flex');
        cerrarDrenaje?.addEventListener('click', () => modalDrenaje.style.display = 'none');
    }

    formDrenaje?.addEventListener('submit', function(e) {
        e.preventDefault();

        const macroregion = document.getElementById('drenaje-macroregion').value;
        const microregion = document.getElementById('drenaje-microregion').value;
        const municipio = document.getElementById('drenaje-municipio').value;
        const nivel = document.getElementById('drenaje-nivel').value;

        // Validación de orden
        if (!(macroregion || microregion || municipio)) {
            alert('Debes seleccionar al menos una región.');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo.');
            return;
        }

        modalDrenaje.style.display = 'none';

        const params = new URLSearchParams({ macroregion, microregion, municipio, nivel });

        ['drenaje_publico','fosa_septica','planta_tratamiento','descarga_otro','separacion_aguas'].forEach(campo => {
            const checkbox = document.getElementById(campo);
            if (checkbox?.checked) params.append(campo, 1);
        });

        console.log('Parámetros enviados (drenaje):', params.toString());

        fetch('/filtrar-drenaje?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                if (planteles.length === 0) {
                    alert('No se encontraron planteles con los filtros seleccionados.');
                    return;
                }

                // Limpia los marcadores del mapa
                map.eachLayer(layer => { if (layer instanceof L.Marker) map.removeLayer(layer); });

                function normalizarEstado(estado) {
                    estado = (estado || '').toLowerCase().trim();
                    return estado === 'en_revision' ? 'revision' : estado;
                }

                function crearIconoPorEstado(estado) {
                    return L.divIcon({
                        className: 'custom-marker',
                        iconSize: [30,30],
                        iconAnchor: [15,30],
                        popupAnchor: [0,-30],
                        html: `<i class="bi bi-droplet-fill marker-icon ${normalizarEstado(estado)}"></i>`
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

            }).catch(error => console.error('Error al aplicar filtros de drenaje:', error));
    });
});
