document.getElementById('btnAplicarFiltros').addEventListener('click', function () {
    const form = document.getElementById('formMultifiltro');
    const formData = new FormData(form);
    const params = {};

    for (const [key, value] of formData.entries()) {
        if (value !== '' && value !== null) {
            params[key] = value;
        }
    }

    //Validacion de nivel academico y region obligatoria 
     const tieneRegion = params.macroregion || params.microregion || params.municipio;
    const tieneNivel = params.nivel;

    if (!tieneRegion) {
        alert('Debes seleccionar al menos una región (macroregión, microregión o municipio).');
        return;
    }

    if (!tieneNivel) {
        alert('Debes seleccionar un nivel educativo antes de aplicar los filtros.');
        return;
    }

    axios.get('/filtrar-avanzado', { params })
        .then(response => {
            const planteles = response.data.data;
            console.log('Resultados filtrados:', planteles);
            mostrarPlantelesEnMapa(planteles);
        })
        .catch(error => {
            console.error('Error al aplicar filtros:', error);
        });

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalMultifiltro'));
    modal.hide();
});

//  Función definida una sola vez
function mostrarPlantelesEnMapa(planteles) {
    const visibles = planteles.filter(p => p.latitud && p.longitud);
    document.getElementById('contador-planteles-numero').textContent = visibles.length;
    document.getElementById('contador-planteles').style.display = 'block';

    if (visibles.length === 0) {
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

        const energia = [];
        const e = p.energia || {};
        if (e.suministro_energia === 1) energia.push('Suministro eléctrico');
        if (e.energia_paneles_solares === 1) energia.push('Paneles solares');
        if (e.energia_planta === 1) energia.push('Planta de energía');
        const energiaTexto = energia.length > 0 ? energia.join(', ') : 'Sin datos energéticos';

        if (p.latitud && p.longitud) {
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
}
