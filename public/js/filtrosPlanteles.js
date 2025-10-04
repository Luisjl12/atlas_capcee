function formatearTextoClave(texto) {
    return texto
        .replaceAll('_', ' ')
        .toLowerCase()
        .replace(/(^|\s)\S/g, l => l.toUpperCase());
}

function mostrarLeyendaSuperficie(texto) {
    const leyenda = document.getElementById('leyenda-superficie');
    leyenda.querySelector('.badge').textContent = texto + ' m²';
    leyenda.style.display = 'block';
}

function ocultarLeyendaSuperficie() {
    document.getElementById('leyenda-superficie').style.display = 'none';
}

function actualizarLeyendaFiltros({ superficie, nivel, region }) {
        document.getElementById('leyenda-superficie-texto').textContent = superficie || '—';
        document.getElementById('leyenda-nivel-texto').textContent = nivel || '—';
        document.getElementById('leyenda-region-texto').textContent = region || '—';
        document.getElementById('leyenda-superficie').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', () => {
    const btnAbrir = document.getElementById('btn-filtros-superficie');
    const modal = document.getElementById('modal-superficie');
    const cerrar = modal?.querySelector('.close');
    const form = document.getElementById('form-superficie');

    btnAbrir?.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    cerrar?.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    form?.addEventListener('submit', function (e) {
        e.preventDefault();

        const macroregionSelect = document.getElementById('filtro-macroregion');
        const macroregion = macroregionSelect.value;
        const macroregionNombre = macroregion !== '' ? macroregionSelect.options[macroregionSelect.selectedIndex].text : '';

        const microregionSelect = document.getElementById('filtro-microregion');
        const microregion = microregionSelect.value;
        const microregionNombre = microregion !== '' ? microregionSelect.options[microregionSelect.selectedIndex].text : '';

        const municipioSelect = document.getElementById('filtro-municipio');
        const municipio = municipioSelect.value;
        const municipioNombre = municipio !== '' ? municipioSelect.options[municipioSelect.selectedIndex].text : '';


        const nivel = document.getElementById('filtro-nivel').value;
        const nivelLegible = formatearTextoClave(nivel);
        const superficie = document.getElementById('filtro-superficie').value;
        const superficieLegible = formatearTextoClave(superficie);

        if (!(macroregion || microregion || municipio)) {
            alert('Debes seleccionar al menos una región (macro, micro o municipio).');
            return;
        }

        if (!nivel) {
            alert('Debes seleccionar un nivel antes de filtrar.');
            return;
        }


        modal.style.display = 'none';

        const params = new URLSearchParams({
            macroregion,
            microregion,
            municipio,
            nivel,
            superficie
        });

        const regionesActivas = [macroregionNombre, microregionNombre, municipioNombre]
        .filter(nombre => nombre !== '')
        .map(formatearTextoClave)
        .join(', ');


        const regionNombre = macroregionNombre || microregionNombre || municipioNombre || '—';
        actualizarLeyendaFiltros({
         superficie: superficieLegible,
         nivel: nivelLegible,
        region: regionesActivas || '—'
        });

        fetch('/filtrar-planteles?' + params.toString())
            .then(res => res.json())
            .then(respuesta => {
                const planteles = respuesta.data || [];
                document.getElementById('contador-planteles-numero').textContent = planteles.length;
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
                    if (p.latitud && p.longitud) {
                        L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {
                            icon: crearIconoPorEstado(p.estatus_plantel)
                        }).addTo(map).bindPopup(`
                            <b>${p.nombre_escuela}</b><br>
                            <b>CCT:</b> ${p.cct}<br>
                            <b>Nivel filtrado:</b> ${nivelLegible}<br>
                            <b>Estado:</b> ${normalizarEstado(p.estatus_plantel)}<br>
                            <b>Municipio:</b> ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
                            <b>Localidad:</b> ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
                            <b>Superficie filtrada:</b> ${superficieLegible} m²<br>
                            <a href="/planteles/${p.id}" target="_blank">Ver ficha completa</a>
                        `);
                    }
                });
            })
            .catch(error => {
                console.error('Error al aplicar filtros:', error);
            });
    });
});
