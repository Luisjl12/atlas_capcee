document.addEventListener('DOMContentLoaded', () => {
    const btnEnergia = document.getElementById('btn-filtros-energia');
    const modalEnergia = document.getElementById('modal-energia');
    const cerrarEnergia = modalEnergia?.querySelector('.close');
    const formEnergia = document.getElementById('form-energia');

    if (btnEnergia && modalEnergia) {
        btnEnergia.addEventListener('click', () => {
            modalEnergia.style.display = 'flex';
        });

        cerrarEnergia?.addEventListener('click', () => {
            modalEnergia.style.display = 'none';
        });
    }

    formEnergia?.addEventListener('submit', function (e) {
        e.preventDefault();

        //  Primero obtenemos los valores
        const macroregion = document.getElementById('energia-macroregion').value;
        const microregion = document.getElementById('energia-microregion').value;
        const municipio = document.getElementById('energia-municipio').value;
        const nivel = document.getElementById('energia-nivel').value.trim();

        //  Validación de orden
        if (!macroregion && !microregion && !municipio) {
            alert('Debes seleccionar al menos una región (macroregión, microregión o municipio).');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo antes de aplicar los filtros.');
            return;
        }

        // Oculta modal solo si pasa validación
        modalEnergia.style.display = 'none';

        //  Construir parámetros
        const params = new URLSearchParams({
            macroregion,
            microregion,
            municipio,
            nivel
        });

        ['suministro_energia','energia_paneles_solares','energia_planta'].forEach(campo => {
            const checkbox = document.getElementById(campo);
            if (checkbox?.checked) {
                params.append(campo, 1);
            }
        });

        console.log('Parámetros enviados (energía):', params.toString());

        fetch('/filtrar-energia?' + params.toString())
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
                    if (p.latitud && p.longitud) {
                        L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {
                            icon: crearIconoPorEstado(p.estatus_plantel)
                        }).addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            CCT: ${p.cct}<br>
                            Estado: ${normalizarEstado(p.estatus_plantel)}<br>
                            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <a href="/planteles/${p.id}" target="_blank">Para ver todos sus detalles da click aquí</a>
                        `);
                    }
                });
            })
            .catch(error => {
                console.error('Error al aplicar filtros de energía:', error.message);
            });
    });
});
