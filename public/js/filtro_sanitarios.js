document.addEventListener('DOMContentLoaded', () => {
    const btnSanitarios = document.getElementById('btn-filtros-sanitarios');
    const modalSanitarios = document.getElementById('modal-sanitarios');
    const cerrarSanitarios = modalSanitarios?.querySelector('.close');
    const formSanitarios = document.getElementById('form-sanitarios');

    formSanitarios?.querySelectorAll('.filtro-select').forEach(select => {
    select.addEventListener('change', () => {
        if (select.value !== "") {
            select.classList.add('activo');
        } else {
            select.classList.remove('activo');
        }
    });
    });

    btnSanitarios?.addEventListener('click', () => {
        modalSanitarios.style.display = 'flex';
    });

    cerrarSanitarios?.addEventListener('click', () => {
        modalSanitarios.style.display = 'none';
    });

    const btnCerrarLeyendaSanitarios = document.getElementById('cerrar-leyenda-sanitarios');
    btnCerrarLeyendaSanitarios?.addEventListener('click', () => {
    document.getElementById('leyenda-sanitarios').style.display = 'none';
    });


    formSanitarios?.addEventListener('submit', function (e) {
        e.preventDefault();

        const macroregionSelect = document.getElementById('sanitarios-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregionSelect.value !== ''
         ? macroregionSelect.options[macroregionSelect.selectedIndex].text
     : '';

        const microregionSelect = document.getElementById('sanitarios-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregionSelect.value !== ''
           ? microregionSelect.options[microregionSelect.selectedIndex].text
           : '';

        const municipioSelect = document.getElementById('sanitarios-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipioSelect.value !== ''
        ? municipioSelect.options[municipioSelect.selectedIndex].text
        : '';

        const nivel = document.getElementById('sanitarios-nivel').value.trim();

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

        [
            'estado_banos',
            'banos_hombres_min',
            'banos_mujeres_min',
            'lavamanos_min',
            'estado_lavamanos',
            'tomas_bebederos_min',
            'estado_bebederos'
        ].forEach(campo => {
            const valor = formSanitarios.querySelector(`[name="${campo}"]`)?.value;
            if (valor) params.append(campo, valor);
        });

        modalSanitarios.style.display = 'none';

        // Mostrar leyenda con filtros aplicados
        document.getElementById('leyenda-sanitarios').style.display = 'block';

        // Nivel educativo
        document.getElementById('leyenda-sanitarios-nivel').textContent = nivel;

        // Región (prioridad: municipio > micro > macro)
        let regionTexto = municipioNombre || microregionNombre || macroregionNombre || 'Sin dato';
        document.getElementById('leyenda-sanitarios-region').textContent = regionTexto;

        // Estado baños
        const estadoBanos = formSanitarios.querySelector('[name="estado_banos"]')?.value || 'Sin dato';
        document.getElementById('leyenda-sanitarios-banos').textContent = estadoBanos;

        // Estado lavamanos
        const estadoLavamanos = formSanitarios.querySelector('[name="estado_lavamanos"]')?.value || 'Sin dato';
        document.getElementById('leyenda-sanitarios-lavamanos').textContent = estadoLavamanos;

        // Estado bebederos
        const estadoBebederos = formSanitarios.querySelector('[name="estado_bebederos"]')?.value || 'Sin dato';
        document.getElementById('leyenda-sanitarios-bebederos').textContent = estadoBebederos;


        fetch('/filtrar-sanitarios?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {

                const planteles = respuesta.data || [];
                // Mostrar y actualizar contador de planteles
                const contador = document.getElementById('contador-planteles');
                const contadorNumero = document.getElementById('contador-planteles-numero');

            contadorNumero.textContent = planteles.length;
            contador.style.display = 'block';


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
                    const estado = p.estatus_plantel;
                    const icono = crearIconoPorEstado(estado);

                    if (p.latitud && p.longitud) {
                        L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {
                            icon: icono
                        }).addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            CCT: ${p.cct}<br>
                            <br><b>Niveles educativos:</b> ${(p.niveles?.map(n => n.nivel).join(', ') || 'Sin dato')}<br>
                            Estado: ${normalizarEstado(estado)}<br>
                            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Sanitarios:</b>
                            <br>Baños hombres: ${p.sanitario?.banos_hombres ?? 'N/A'}
                            <br>Baños mujeres: ${p.sanitario?.banos_mujeres ?? 'N/A'}
                            <br>Estado baños: ${p.sanitario?.estado_banos ?? 'N/A'}
                            <br>Lavamanos: ${p.sanitario?.lavamanos ?? 'N/A'}
                            <br>Estado lavamanos: ${p.sanitario?.estado_lavamanos ?? 'N/A'}
                            <br>Bebederos: ${p.sanitario?.tomas_bebederos ?? 'N/A'}
                            <br>Estado bebederos: ${p.sanitario?.estado_bebederos ?? 'N/A'}
                            <br><a href="/planteles/${p.id}" target="_blank">Ver ficha completa</a>
                        `);
                    }
                });
            })
            .catch(error => {
                console.error('Error al aplicar filtros de sanitarios:', error.message);
            });
    });
});