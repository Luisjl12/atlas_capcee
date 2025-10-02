document.addEventListener('DOMContentLoaded', () => {
    const btnEstado = document.getElementById('btn-filtros-estado');
    const modalEstado = document.getElementById('modal-instalaciones');
    const cerrarEstado = modalEstado?.querySelector('.close');
    const formEstado = document.getElementById('form-instalaciones');

    if (btnEstado && modalEstado) {
        btnEstado.addEventListener('click', () => modalEstado.style.display = 'flex');
        cerrarEstado?.addEventListener('click', () => modalEstado.style.display = 'none');
    }

    formEstado?.addEventListener('submit', function (e) {
        e.preventDefault();

        // Obtener valores
        const macroregion = document.getElementById('estado-macroregion').value;
        const microregion = document.getElementById('estado-microregion').value;
        const municipio = document.getElementById('estado-municipio').value;
        const nivel = document.getElementById('estado-nivel').value.trim();

        const estadoRed = document.getElementById('estado_red_hidraulica').value;
        const estadoSanitaria = document.getElementById('estado_instalacion_sanitaria').value;
        const estadoElectrica = document.getElementById('estado_instalacion_electrica').value;

        // 🔹 Validación de orden
        if (!macroregion && !microregion && !municipio) {
            alert('Debes seleccionar al menos una región (macroregión, microregión o municipio).');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo antes de aplicar los filtros.');
            return;
        }

        modalEstado.style.display = 'none';

        // Construir parámetros
        const params = new URLSearchParams({
            macroregion,
            microregion,
            municipio,
            nivel
        });

        // Solo agrega los filtros de estado si tienen valor
        if (estadoRed) params.append('estado_red_hidraulica', estadoRed);
        if (estadoSanitaria) params.append('estado_instalacion_sanitaria', estadoSanitaria);
        if (estadoElectrica) params.append('estado_instalacion_electrica', estadoElectrica);

        console.log('Parámetros enviados (estado):', params.toString());

        fetch('/filtrar-instalaciones?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                if (planteles.length === 0) {
                    alert('No se encontraron planteles con los filtros seleccionados.');
                    return;
                }

                map.eachLayer(layer => {
                    if (layer instanceof L.Marker) map.removeLayer(layer);
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
                        html: `<i class="bi bi-geo-fill marker-icon ${normalizarEstado(estado)}"></i>`
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
            .catch(error => console.error('Error al aplicar filtros de instalaciones:', error));
    });
});
