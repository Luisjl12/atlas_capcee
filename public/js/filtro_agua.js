document.addEventListener('DOMContentLoaded', () => {
    const btnAgua = document.getElementById('btn-filtros-agua');
    const modalAgua = document.getElementById('modal-agua');
    const cerrarAgua = modalAgua?.querySelector('.close');
    const formAgua = document.getElementById('form-agua');

    if (btnAgua && modalAgua) {
        btnAgua.addEventListener('click', () => modalAgua.style.display = 'flex');
        cerrarAgua?.addEventListener('click', () => modalAgua.style.display = 'none');
    }

    formAgua?.addEventListener('submit', function (e) {
        e.preventDefault();

        // Obtener valores y nombres legibles
        const macroregionSelect = document.getElementById('agua-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregion !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('agua-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregion !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('agua-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipio !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const nivel = document.getElementById('agua-nivel').value.toLowerCase().trim();

        // Validaciones
        if (!macroregion && !microregion && !municipio) {
            alert('Debes seleccionar al menos una región.');
            return;
        }
        if (!nivel) {
            alert('Debes seleccionar un nivel educativo.');
            return;
        }

        // Ocultar modal
        modalAgua.style.display = 'none';

        // Construir parámetros
        const params = new URLSearchParams({ macroregion, microregion, municipio, nivel });

        const camposAgua = [
            'agua_red_publica','agua_pozo','agua_cuerpo','agua_pipas','agua_otro',
            'cisterna','tinacos','tanque','almacenamiento_otro'
        ];

        const aguaSeleccionada = camposAgua
            .filter(campo => {
                const checkbox = document.getElementById(campo);
                if (checkbox?.checked) {
                    params.append(campo, 1);
                    return true;
                }
                return false;
            })
            .map(campo => campo.replace(/_/g, ' '))
            .map(texto => texto.charAt(0).toUpperCase() + texto.slice(1))
            .join(', ');

        // Construir nombre de región
        const regiones = [];
        if (macroregionNombre) regiones.push(macroregionNombre);
        if (microregionNombre) regiones.push(microregionNombre);
        if (municipioNombre) regiones.push(municipioNombre);
        const regionNombre = regiones.length > 0 ? regiones.join(', ') : '—';

        // Actualizar leyenda visual
        document.getElementById('leyenda-agua-nivel').textContent = nivel || '—';
        document.getElementById('leyenda-agua-region').textContent = regionNombre;
        document.getElementById('leyenda-agua-tipo').textContent = aguaSeleccionada || '—';
        document.getElementById('leyenda-agua').style.display = 'block';

        console.log('Parámetros enviados (agua):', params.toString());

        fetch('/filtrar-agua?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                if (planteles.length === 0) {
                    alert('No se encontraron planteles con los filtros seleccionados.');
                    return;
                }

                // Actualizar contador
                const visibles = planteles.filter(p => p.plantel?.latitud && p.plantel?.longitud);
                document.getElementById('contador-planteles-numero').textContent = visibles.length;

                document.getElementById('contador-planteles').style.display = 'block';

                // Limpiar marcadores anteriores
                map.eachLayer(layer => {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });

                // Helpers visuales
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

                // Renderizar marcadores
                visibles.forEach(p => {

                    const niveles = Array.isArray(p.plantel?.niveles)
                    ? p.plantel.niveles.map(n => n.nivel.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())).join(', ')
                     : 'Sin nivel registrado';

                    if (p.plantel?.latitud && p.plantel?.longitud) {
                        const agua = [];
                        const a = p;

                        if (a.agua_red_publica) agua.push('Red pública');
                        if (a.agua_pozo) agua.push('Pozo');
                        if (a.agua_cuerpo) agua.push('Cuerpo de agua');
                        if (a.agua_pipas) agua.push('Pipas');
                        if (a.agua_otro) agua.push('Otro suministro');
                        if (a.cisterna) agua.push('Cisterna');
                        if (a.tinacos) agua.push('Tinacos');
                        if (a.tanque) agua.push('Tanque');
                        if (a.almacenamiento_otro) agua.push('Otro almacenamiento');

                        const aguaTexto = agua.length > 0 ? agua.join(', ') : 'Sin datos hidráulicos';

                        L.marker([parseFloat(p.plantel.latitud), parseFloat(p.plantel.longitud)], {
                            icon: crearIconoPorEstado(p.plantel.estatus_plantel)
                        }).addTo(map).bindPopup(`
                            <b>${p.plantel.nombre_escuela}</b><br>
                            CCT: ${p.plantel.cct}<br>
                            <b>Nivel educativo:</b> ${niveles}<br>
                            Estado: ${normalizarEstado(p.plantel.estatus_plantel)}<br>
                            Municipio: ${p.plantel.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.plantel.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Detalle hidráulico:</b> ${aguaTexto}<br>
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
