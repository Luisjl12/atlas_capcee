document.addEventListener('DOMContentLoaded', () => {
    const btnAgua = document.getElementById('btn-filtros-agua');
    const modalAgua = document.getElementById('modal-agua');
    const cerrarAgua = modalAgua?.querySelector('.close');
    const formAgua = document.getElementById('form-agua');

    if (btnAgua && modalAgua) {
        btnAgua.addEventListener('click', () => {
            modalAgua.style.display = 'flex';
        });

        cerrarAgua?.addEventListener('click', () => {
            modalAgua.style.display = 'none';
        });
    }

    formAgua?.addEventListener('submit', function (e) {
        e.preventDefault();

        // ✅ Primero obtenemos los valores
        const macroregion = document.getElementById('agua-macroregion').value;
        const microregion = document.getElementById('agua-microregion').value;
        const municipio = document.getElementById('agua-municipio').value;
        const nivel = document.getElementById('agua-nivel').value.toLowerCase().trim();

        // 🔹 Validación de orden
        if (!macroregion && !microregion && !municipio) {
            alert('Debes seleccionar al menos una región (macroregión, microregión o municipio).');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo antes de aplicar los filtros.');
            return;
        }

        //  Oculta modal solo si pasa validación
        modalAgua.style.display = 'none';

        //  Construir parámetros
        const params = new URLSearchParams({
            macroregion,
            microregion,
            municipio,
            nivel
        });

        ['agua_red_publica','agua_pozo','agua_cuerpo','agua_pipas','agua_otro','cisterna','tinacos','tanque','almacenamiento_otro'].forEach(campo => {
            const checkbox = document.getElementById(campo);
            if (checkbox?.checked) {
                params.append(campo, 1);
            }
        });

        console.log('Parámetros enviados (agua):', params.toString());

        fetch('/filtrar-agua?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                if (planteles.length === 0) {
                    alert('No se encontraron planteles con los filtros seleccionados.');
                    return;
                }

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
                    if (p.plantel?.latitud && p.plantel?.longitud) {
                        L.marker([parseFloat(p.plantel.latitud), parseFloat(p.plantel.longitud)], {
                            icon: crearIconoPorEstado(p.plantel.estatus_plantel)
                        }).addTo(map).bindPopup(`
                            <b>${p.plantel.nombre_escuela}</b><br>
                            CCT: ${p.plantel.cct}<br>
                            Estado: ${normalizarEstado(p.plantel.estatus_plantel)}<br>
                            Municipio: ${p.plantel.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.plantel.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <a href="/planteles/${p.plantel.id}" target="_blank">Ver ficha completa</a>
                        `);
                    }
                });
            })
            .catch(error => {
                console.error('Error al aplicar filtros de agua:', error.message);
            });
    });
});
