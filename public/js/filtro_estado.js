document.addEventListener('DOMContentLoaded', () => {
    const btnEstado = document.getElementById('btn-filtros-estado');
    const modalEstado = document.getElementById('modal-instalaciones');
    const cerrarEstado = modalEstado?.querySelector('.close');
    const formEstado = document.getElementById('form-instalaciones');

    formEstado?.querySelectorAll('.filtro-select').forEach(select => {
    select.addEventListener('change', () => {
        if (select.value !== "") {
            select.classList.add('activo');
        } else {
            select.classList.remove('activo');
        }
    });
    });

    if (btnEstado && modalEstado) {
        btnEstado.addEventListener('click', () => modalEstado.style.display = 'flex');
        cerrarEstado?.addEventListener('click', () => modalEstado.style.display = 'none');
    }

    const btnCerrarLeyendaEstado = document.getElementById('cerrar-leyenda-estado');
    btnCerrarLeyendaEstado?.addEventListener('click', () => {
    document.getElementById('leyenda-estado').style.display = 'none';
    });


    formEstado?.addEventListener('submit', function (e) {
        e.preventDefault();

        // Obtener valores
        const macroregionSelect = document.getElementById('estado-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregionSelect.value !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('estado-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregionSelect.value !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('estado-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipioSelect.value !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const regiones = [];
        if (macroregionNombre) regiones.push(macroregionNombre);
        if (microregionNombre) regiones.push(microregionNombre);
        if (municipioNombre) regiones.push(municipioNombre);
        const regionNombre = regiones.length > 0 ? regiones.join(', ') : '—';

        const nivel = document.getElementById('estado-nivel').value.trim();
        const estadoRed = document.getElementById('estado_red_hidraulica').value;
        const estadoSanitaria = document.getElementById('estado_instalacion_sanitaria').value;
        const estadoElectrica = document.getElementById('estado_instalacion_electrica').value;

        document.getElementById('leyenda-estado-nivel').textContent = nivel || '—';
        document.getElementById('leyenda-estado-region').textContent = regionNombre;
        document.getElementById('leyenda-estado-red').textContent = estadoRed || '—';
        document.getElementById('leyenda-estado-sanitaria').textContent = estadoSanitaria || '—';
        document.getElementById('leyenda-estado-electrica').textContent = estadoElectrica || '—';
        document.getElementById('leyenda-estado').style.display = 'block';

        //  Validación de orden
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
                const visibles = planteles.filter(p => p.latitud && p.longitud);
                document.getElementById('contador-planteles-numero').textContent = visibles.length;
                document.getElementById('contador-planteles').style.display = 'block';


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
                        }).addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            CCT: ${p.cct}<br>
                            <b>Nivel educativo:</b> ${niveles}<br>
                            Estado: ${normalizarEstado(p.estatus_plantel)}<br>
                            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Estado de conservación:</b><br>
                            Red hidráulica: ${p.agua?.estado_red_hidraulica || 'Sin dato'}<br>
                            Instalación sanitaria: ${p.sanitario?.estado_instalacion_sanitaria || 'Sin dato'}<br>
                            Instalación eléctrica: ${p.energia?.estado_instalacion_electrica || 'Sin dato'}<br>
                            <a href="/planteles/${p.id}" target="_blank">Ver ficha completa</a>
                        `);
                    }
                });
            })
            .catch(error => console.error('Error al aplicar filtros de instalaciones:', error));
    });
});