document.addEventListener('DOMContentLoaded', () => {
    const btnAccesibilidad = document.getElementById('btn-filtros-accesibilidad');
    const modalAccesibilidad = document.getElementById('modal-accesibilidad');
    const cerrarAccesibilidad = modalAccesibilidad?.querySelector('.close');
    const formAccesibilidad = document.getElementById('form-accesibilidad');

    // Abrir modal
    btnAccesibilidad?.addEventListener('click', () => {
        modalAccesibilidad.style.display = 'flex';
    });

    cerrarAccesibilidad?.addEventListener('click', () => {
        modalAccesibilidad.style.display = 'none';
    });

    // Enviar formulario
    formAccesibilidad?.addEventListener('submit', function (e) {
        e.preventDefault();

        const macroregionSelect = document.getElementById('accesibilidad-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregionSelect.value !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('accesibilidad-microregion');
        const microregion = microregionSelect.value; 
        const microregionNombre = microregionSelect.value !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('accesibilidad-municipio');
        const municipio = municipioSelect.value;

        const municipioNombre = municipioSelect.value !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const regiones = [];
            if (macroregionNombre) regiones.push(macroregionNombre);
            if (microregionNombre) regiones.push(microregionNombre);
            if (municipioNombre) regiones.push(municipioNombre);
        const regionNombre = regiones.length > 0 ? regiones.join(', ') : '—';

        const tipoAccesibilidad = ['infraestructura_discapacidad', 'sin_infraestructura_discapacidad']
    .map(campo => {
        const radio = document.querySelector(`input[name="${campo}"]:checked`);
        return radio ? radio.labels?.[0]?.textContent.trim() : null;
    })
    .filter(Boolean)
    .join(', ') || '—';

    const nivel = document.getElementById('accesibilidad-nivel').value.trim();
        const categoria = document.getElementById('equipo_discapacidad_categoria').value;

        document.getElementById('leyenda-accesibilidad-nivel').textContent = nivel || '—';
        document.getElementById('leyenda-accesibilidad-region').textContent = regionNombre;
        document.getElementById('leyenda-accesibilidad-categoria').textContent = categoria || '—';
        document.getElementById('leyenda-accesibilidad-tipo').textContent = tipoAccesibilidad;
        document.getElementById('leyenda-accesibilidad').style.display = 'block';

        

        //Validaciones obligatorias 
        if (!macroregion && !microregion && !municipio) {
        alert('Por favor selecciona al menos una región (macro, micro o municipio) antes de aplicar filtros.');
        return;
        }

        if (!nivel) {
        alert('Por favor selecciona un nivel educativo antes de aplicar filtros.');
        return;
        }

        const params = new URLSearchParams();

        if (macroregion) params.append('macroregion', macroregion);
        if (microregion) params.append('microregion', microregion);
        if (municipio) params.append('municipio', municipio);
        if (nivel) params.append('nivel', nivel);
        if (categoria) params.append('equipo_discapacidad_categoria', categoria);

        ['infraestructura_discapacidad', 'sin_infraestructura_discapacidad'].forEach(campo => {
            const radio = document.querySelector(`input[name="${campo}"]:checked`);
            if (radio) {
                params.append(campo, radio.value);
            }
        });

        modalAccesibilidad.style.display = 'none';

        fetch('/filtrar-accesibilidad?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                const visibles = planteles.filter(p => p.latitud && p.longitud);
                document.getElementById('contador-planteles-numero').textContent = visibles.length;
                document.getElementById('contador-planteles').style.display = 'block';


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

                visibles.forEach(p => {
                    if (p.latitud && p.longitud) {
                        const niveles = Array.isArray(p.niveles)
                        ? p.niveles.map(n => n.nivel.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())).join(', ')
                        : 'Sin nivel registrado';

                        L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {
                        icon: crearIconoPorEstado(p.estatus_plantel)
                        })
                        .addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            CCT: ${p.cct}<br>
                            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Nivel educativo:</b> ${niveles}<br>
                            <b>Accesibilidad:</b>
                            <br>Infraestructura: ${p.seguridad?.infraestructura_discapacidad ? 'Sí' : 'No'}
                            <br>Sin infraestructura: ${p.seguridad?.sin_infraestructura_discapacidad ? 'Sí' : 'No'}
                            <br>Equipo total: ${p.seguridad?.equipo_discapacidad_total ?? 'N/A'}
                            <br><a href="/planteles/${p.id}" target="_blank">Ver detalles completos</a>
                        `);
                    }
                });
            })
            .catch(error => {
                console.error('Error al aplicar filtros de accesibilidad:', error.message);
            });
    });
});
