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
        const macroregionSelect = document.getElementById('energia-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregionSelect.value !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('energia-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregionSelect.value !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('energia-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipioSelect.value !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const regiones = [];
        if (macroregionNombre) regiones.push(macroregionNombre);
        if (microregionNombre) regiones.push(microregionNombre);
        if (municipioNombre) regiones.push(municipioNombre);
        const regionNombre = regiones.length > 0 ? regiones.join(', ') : '—';

        const camposEnergia = ['suministro_energia','energia_paneles_solares','energia_planta'];

        const energiaSeleccionada = camposEnergia
         .filter(campo => document.getElementById(campo)?.checked)
         .map(campo => campo.replace(/_/g, ' '))
        .map(texto => texto.charAt(0).toUpperCase() + texto.slice(1))
        .join(', ');

        // Actualizar leyenda visual
        const nivel = document.getElementById('energia-nivel').value.trim();

        document.getElementById('leyenda-energia-nivel').textContent = nivel || '—';
        document.getElementById('leyenda-energia-region').textContent = regionNombre;
        document.getElementById('leyenda-energia-tipo').textContent = energiaSeleccionada || '—';
        document.getElementById('leyenda-energia').style.display = 'block';


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

                planteles.forEach(p => {

                    const niveles = Array.isArray(p.niveles)
                     ? p.niveles.map(n => n.nivel.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())).join(', ')
                    : 'Sin nivel registrado';

                    if (p.latitud && p.longitud) {
                        const energia = [];
                        const e = p.energia || {};

                    if (e.suministro_energia === 1) energia.push('Suministro eléctrico');
                    if (e.energia_paneles_solares === 1) energia.push('Paneles solares');
                    if (e.energia_planta === 1) energia.push('Planta de energía');

                    const energiaTexto = energia.length > 0 ? energia.join(', ') : 'Sin datos energéticos';

                        L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {

                            
                            icon: crearIconoPorEstado(p.estatus_plantel)
                        }).addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            CCT: ${p.cct}<br>
                            <b>Nivel educativo:</b> ${niveles}<br>
                            Estado: ${normalizarEstado(p.estatus_plantel)}<br>
                            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Infraestructura energética:</b> ${energiaTexto}<br>
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
