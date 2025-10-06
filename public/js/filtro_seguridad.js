document.addEventListener('DOMContentLoaded', () => {
    const btnSeguridad = document.getElementById('btn-filtros-seguridad');
    const modalSeguridad = document.getElementById('modal-seguridad');
    const cerrarSeguridad = modalSeguridad?.querySelector('.close');
    const formSeguridad = document.getElementById('form-seguridad');

    formSeguridad?.querySelectorAll('.filtro-select').forEach(select => {
    select.addEventListener('change', () => {
        if (select.value !== "") {
            select.classList.add('activo');
        } else {
            select.classList.remove('activo');
        }
    });
    });

    // Abrir modal
    if (btnSeguridad && modalSeguridad) {
        btnSeguridad.addEventListener('click', () => {
            modalSeguridad.style.display = 'flex';
        });

        cerrarSeguridad?.addEventListener('click', () => {
            modalSeguridad.style.display = 'none';
        });
    }

    // Enviar formulario
    formSeguridad?.addEventListener('submit', function (e) {
        e.preventDefault();

        const macroregion = document.getElementById('seguridad-macroregion').value;
        const microregion = document.getElementById('seguridad-microregion').value;
        const municipio = document.getElementById('seguridad-municipio').value;
        const nivel = document.getElementById('seguridad-nivel').value.trim();

        if (!macroregion && !microregion && !municipio) {
            alert('Debes seleccionar al menos una región (macroregión, microregión o municipio).');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo antes de aplicar los filtros.');
            return;
        }

        modalSeguridad.style.display = 'none';

        const params = new URLSearchParams({
            macroregion,
            microregion,
            municipio,
            nivel
        });

        // Agregar checkboxes (booleanos)
        ['proteccion_civil', 'barda_completa'].forEach(campo => {
            const checkbox = document.getElementById(campo);
            if (checkbox?.checked) {
                params.append(campo, 1);
            }
        });

        // Agregar selects (strings)
        ['estado_barda', 'estado_cerco'].forEach(campo => {
            const select = document.getElementById(campo);
            if (select && select.value.trim() !== '') {
                params.append(campo, select.value.trim());
            }
        });

        console.log('Parámetros enviados (seguridad):', params.toString());
        document.getElementById('leyenda-seguridad').style.display = 'block';
        document.getElementById('leyenda-seguridad-nivel').textContent = nivel;

        const macroregionSelect = document.getElementById('seguridad-macroregion');
        const macroregionNombre = macroregionSelect.value !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('seguridad-microregion');
        const microregionNombre = microregionSelect.value !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('seguridad-municipio');
        const municipioNombre = municipioSelect.value !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const regionTexto = municipioNombre || microregionNombre || macroregionNombre || 'Sin dato';
        document.getElementById('leyenda-seguridad-region').textContent = regionTexto;

        document.getElementById('leyenda-seguridad-pc').textContent = document.getElementById('proteccion_civil')?.checked ? 'Sí' : 'No';
        document.getElementById('leyenda-seguridad-barda').textContent = document.getElementById('barda_completa')?.checked ? 'Sí' : 'No';
        document.getElementById('leyenda-seguridad-estado-barda').textContent = document.getElementById('estado_barda')?.value || 'Sin dato';
        document.getElementById('leyenda-seguridad-estado-cerca').textContent = document.getElementById('estado_cerca')?.value || 'Sin dato';


        fetch('/filtrar-seguridad?' + params.toString())
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
                        }).addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            CCT: ${p.cct}<br>
                            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Nivel Educativo: </b>${niveles}<br>
                            <b>Seguridad:</b>
                            <br>Protección Civil: ${p.seguridad?.proteccion_civil ? 'Sí' : 'No'}
                            <br>Barda completa: ${p.seguridad?.barda_completa ? 'Sí' : 'No'}
                            <br>Estado barda: ${p.seguridad?.estado_barda || 'N/A'}
                            <br>Estado cerca: ${p.seguridad?.estado_cerco || 'N/A'}
                            <br><a href="/planteles/${p.id}" target="_blank">Para ver todos sus detalles da click aquí</a>
                        `);
                    }
                });
            })
            .catch(error => {
                console.error('Error al aplicar filtros de seguridad:', error.message);
            });
    });
});
