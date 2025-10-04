document.addEventListener('DOMContentLoaded', () => {
    const btnObras = document.getElementById('btn-filtros-obras');
    const modalObras = document.getElementById('modal-obras');
    const cerrarObras = modalObras?.querySelector('.close');
    const formObras = document.getElementById('form-obras');

    if (btnObras && modalObras) {
        btnObras.addEventListener('click', () => {
            modalObras.style.display = 'flex';
        });

        cerrarObras?.addEventListener('click', () => {
            modalObras.style.display = 'none';
        });
    }

    formObras?.addEventListener('submit', function (e) {
        e.preventDefault();

        const macroregionSelect = document.getElementById('obras-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregionSelect.value !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('obras-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregionSelect.value !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('obras-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipioSelect.value !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const regionNombre = macroregionNombre || microregionNombre || municipioNombre || '—';

        const nivel = document.getElementById('obras-nivel').value.trim();


        if (!macroregion && !microregion && !municipio) {
            alert('Debes seleccionar al menos una región (macroregión, microregión o municipio).');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo antes de aplicar los filtros.');
            return;
        }

        modalObras.style.display = 'none';

        const params = new URLSearchParams({
            macroregion,
            microregion,
            municipio,
            nivel
        });

        // Agregar checkboxes marcados
        const obrasCampos = [
        'rehabilitacion_realizada',
        'rehabilitacion_impermeabilizacion',
        'rehabilitacion_albanileria',
        'rehabilitacion_pintura',
        'rehabilitacion_red_hidraulica',
        'rehabilitacion_red_sanitaria',
        'rehabilitacion_estructural',
        'obras_nuevas',
        'construccion_educativa',
        'construccion_deportiva',
        'construccion_sanitaria',
        'construccion_complementos',
        'construccion_otro'
        ]; 

        const obrasSeleccionadas = obrasCampos
        .filter(campo => document.getElementById(campo)?.checked)
        .map(campo => {
        params.append(campo, 1); //  se manda al backend
          return campo.replace(/_/g, ' ');
        })
        .map(texto => texto.charAt(0).toUpperCase() + texto.slice(1))
        .join(', ');

  // Actualizar leyenda visual
        document.getElementById('leyenda-obras-nivel').textContent = nivel || '—';
        document.getElementById('leyenda-obras-region').textContent = regionNombre || '—';
        document.getElementById('leyenda-obras-tipo').textContent = obrasSeleccionadas || '—';
        document.getElementById('leyenda-obras').style.display = 'block';

        console.log('Parámetros enviados (obras):', params.toString());

        fetch('/filtrar-obras?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];

                if (planteles.length === 0) {
                    alert('No se encontraron planteles con los filtros seleccionados.');
                    return;
                }


        document.getElementById('contador-planteles-numero').textContent = planteles.length;
        document.getElementById('contador-planteles').style.display = 'block';

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
                console.error('Error al aplicar filtros de obras:', error.message);
            });
    });
});
