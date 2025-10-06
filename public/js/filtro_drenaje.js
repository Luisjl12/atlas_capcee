document.addEventListener('DOMContentLoaded', () => {
    const btnDrenaje = document.getElementById('btn-filtros-drenaje');
    const modalDrenaje = document.getElementById('modal-drenaje');
    const cerrarDrenaje = modalDrenaje?.querySelector('.close');
    const formDrenaje = document.getElementById('form-drenaje');

    formDrenaje?.querySelectorAll('.filtro-select').forEach(select => {
    select.addEventListener('change', () => {
        if (select.value !== "") {
            select.classList.add('activo');
        } else {
            select.classList.remove('activo');
        }
    });
    });

    if (btnDrenaje && modalDrenaje) {
        btnDrenaje.addEventListener('click', () => modalDrenaje.style.display = 'flex');
        cerrarDrenaje?.addEventListener('click', () => modalDrenaje.style.display = 'none');
    }

    formDrenaje?.addEventListener('submit', function(e) {
        e.preventDefault();

        const macroregionSelect = document.getElementById('drenaje-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregionSelect.value !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('drenaje-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregionSelect.value !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('drenaje-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipioSelect.value !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';

        const regiones = [];
        if (macroregionNombre) regiones.push(macroregionNombre);
        if (microregionNombre) regiones.push(microregionNombre);
        if (municipioNombre) regiones.push(municipioNombre);
        const regionNombre = regiones.length > 0 ? regiones.join(', ') : '—';

        const camposDrenaje = ['drenaje_publico','fosa_septica','planta_tratamiento','descarga_otro','separacion_aguas'];

        const drenajeSeleccionado = camposDrenaje
         .filter(campo => document.getElementById(campo)?.checked)
         .map(campo => campo.replace(/_/g, ' '))
         .map(texto => texto.charAt(0).toUpperCase() + texto.slice(1))
        .join(', ');

        // Actualizar leyenda visual
        const nivel = document.getElementById('drenaje-nivel').value;

        document.getElementById('leyenda-drenaje-nivel').textContent = nivel || '—';
        document.getElementById('leyenda-drenaje-region').textContent = regionNombre;
        document.getElementById('leyenda-drenaje-tipo').textContent = drenajeSeleccionado || '—';
        document.getElementById('leyenda-drenaje').style.display = 'block';


        // Validación de orden
        if (!(macroregion || microregion || municipio)) {
            alert('Debes seleccionar al menos una región.');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel educativo.');
            return;
        }

        modalDrenaje.style.display = 'none';

        const params = new URLSearchParams({ macroregion, microregion, municipio, nivel });

        ['drenaje_publico','fosa_septica','planta_tratamiento','descarga_otro','separacion_aguas'].forEach(campo => {
            const checkbox = document.getElementById(campo);
            if (checkbox?.checked) params.append(campo, 1);
        });

        console.log('Parámetros enviados (drenaje):', params.toString());

        fetch('/filtrar-drenaje?' + params.toString())
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

                // Limpia los marcadores del mapa
                map.eachLayer(layer => { if (layer instanceof L.Marker) map.removeLayer(layer); });

                function normalizarEstado(estado) {
                    estado = (estado || '').toLowerCase().trim();
                    return estado === 'en_revision' ? 'revision' : estado;
                }

                function crearIconoPorEstado(estado) {
                    return L.divIcon({
                        className: 'custom-marker',
                        iconSize: [30,30],
                        iconAnchor: [15,30],
                        popupAnchor: [0,-30],
                        html: `<i class="bi bi-geo-alt-fill marker-icon ${normalizarEstado(estado)}"></i>`
                    });
                }

                visibles.forEach(p => {
                    if (p.latitud && p.longitud) {
                        const drenaje = [];
                        const d = p.drenaje || {};

                    if (d.drenaje_publico === 1) drenaje.push('Drenaje público');
                    if (d.fosa_septica === 1) drenaje.push('Fosa séptica');
                    if (d.planta_tratamiento === 1) drenaje.push('Planta de tratamiento');
                    if (d.descarga_otro === 1) drenaje.push('Descarga alternativa');
                    if (d.separacion_aguas === 1) drenaje.push('Separación de aguas');

                    const drenajeTexto = drenaje.length > 0 ? drenaje.join(', ') : 'Sin datos de drenaje';

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
                            <b>Infraestructura de drenaje:</b> ${drenajeTexto}<br>
                            <a href="/planteles/${p.id}" target="_blank">Ver ficha completa</a>
                        `);
                    }
                });

            }).catch(error => console.error('Error al aplicar filtros de drenaje:', error));
    });
});
