document.addEventListener('DOMContentLoaded', function () {
    const btnBuscar = document.getElementById('btn-buscar-cct');
    const inputCCT = document.getElementById('input-cct');

    btnBuscar.addEventListener('click', function () {
        const cct = inputCCT.value.trim();
        if (!cct) {
            alert('Por favor ingresa un CCT válido.');
            return;
        }

        // Lógica principal de búsqueda
        buscarPorCCT(cct);
    });
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


function buscarPorCCT(cct) {
    console.log('Buscando por CCT:', cct);

    fetch(`/buscar-cct/${cct}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo obtener el plantel');
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);

            if (data && data.latitud && data.longitud) {
                const estado = data.estado || '';
                const icono = crearIconoPorEstado(estado);

                const marker = L.marker([data.latitud, data.longitud], { icon: icono })
                    .addTo(window.map)
                    .bindPopup(`
                        <strong>${data.nombre}</strong><br>
                        CCT: ${data.cct}<br>
                        Estado: ${estado}<br>
                        Nivel educativo: ${data.nivel_educativo}<br>
                        Municipio: ${data.municipio}<br>
                        Localidad: ${data.localidad}<br>  
                        <a href="${data.ficha_url}" target="_blank" >Ver ficha base</a>
                        `           
                    )
                    .openPopup();

                window.map.setView([data.latitud, data.longitud], 8);
            } else {
                alert('No se encontró el plantel o faltan coordenadas.');
            }
        })
        .catch(error => {
            console.error('Error al buscar el CCT:', error);
            alert('Ocurrió un error al buscar el plantel.');
        });
}



