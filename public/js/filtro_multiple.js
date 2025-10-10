document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnAplicarFiltros').addEventListener('click', function () {
    const form = document.getElementById('formMultifiltro');
    const formData = new FormData(form);
    const params = {};

    // Extraer nombres visibles de regiones
    const macroregionSelect = document.getElementById('macroregion');
    const microregionSelect = document.getElementById('microregion');
    const municipioSelect = document.getElementById('municipio');

    const macroregionNombre = macroregionSelect?.value !== ''
     ? macroregionSelect.options[macroregionSelect.selectedIndex].text
     : '';
    const microregionNombre = microregionSelect?.value !== ''
      ? microregionSelect.options[microregionSelect.selectedIndex].text
    : '';
    const municipioNombre = municipioSelect?.value !== ''
     ? municipioSelect.options[municipioSelect.selectedIndex].text
     : '';

    const nombresLegibles = {
     macroregion: macroregionNombre,
     microregion: microregionNombre,
    municipio: municipioNombre
        };

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
            mostrarLeyendaFiltros(params, nombresLegibles);

        })
        .catch(error => {
            console.error('Error al aplicar filtros:', error);
        });

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalMultifiltro'));
    modal.hide();
});

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
                <a href="/planteles/${p.id}" target="_blank">Para ver todos sus detalles da click aquí</a>
            `);
        }
    });
}

function mostrarLeyendaFiltros(params, nombresLegibles={}) {
  const lista = document.getElementById('lista-filtros-activos');
  lista.innerHTML = ''; // Limpia leyenda anterior

  const camposNumericos = [
    'banos_hombres_min',
    'banos_mujeres_min',
    'lavamanos_min',
    'tomas_bebederos_min'
  ];
    //Estas etiquetas sirven para mostrar los filtros aplicados
  const etiquetas = {
    //Regiones y nivel 
    nivel: 'Nivel educativo',
    macroregion: 'Macroregión',
    microregion: 'Microregión',
    municipio: 'Municipio',
    //Superficie
    superficie: '¿Cual es su superficie en metros cuadrados?', 
    //Suministro de energia
    suministro_energia: '¿Tiene suministro eléctrico?',
    energia_paneles_solares: '¿Tiene paneles solares?',
    energia_planta: '¿Tiene planta eléctrica?',
    //Seguridad
    proteccion_civil: '¿Cuenta con dictamen de protección Civil?',
    barda_completa: '¿Tiene barda completa?',
    estado_barda: '¿Cual es el estado de la barda?',
    estado_cerco: '¿Cual es el estado del cerco?',
    //Accesibilidad
    infraestructura_discapacidad: '¿Cuenta con infraestructura para personas discapacitadas?', 
    sin_infraestructura_discapacidad: '¿El inmueble no cuenta con infraestructura para personas discapacitadas?', 
    equipo_discapacidad_categoria: '¿Cual es el nivel de equipamiento para discapacitados con el que se cuenta?', 
    //Obras realizadas
    rehabilitacion_realizada: '¿Se han realizado obras de rehabilitación en los últimos cinco años?',
    rehabilitacion_impermeabilizacion:'¿Obras de impermeabilización?', 
    rehabilitacion_albanileria:'¿Obras de albañilería?' ,
    rehabilitacion_pintura:'¿Rehabilitación con pintura general?',
    rehabilitacion_red_hidraulica: '¿Obras en la red hidráulica?',
    rehabilitacion_red_sanitaria:'¿Obras en la red sanitaria?',
    rehabilitacion_esctructural:'¿Mejoras estructurales?',
    obras_nuevas:'¿Obras nuevas en los últimos cinco años?',
    construccion_educativa:'¿Construcción en espacios educativos?',
    construccion_deportiva:'¿Construcción en espacios deportivos o recreativos?',
    construccion_sanitaria:'¿Construcción en sanitarios?',
    construccion_complementos:'¿Construcción de complementos?',
    construccion_otro:'¿Otros tipos de construcción?',
    //Agua
    agua_red_publica: '¿Cuenta con agua de red pública?', 
    agua_pozo:'¿Tiene acceso a agua de pozo?', 
    agua_cuerpo: '¿Utiliza agua de cuerpo natural?', 
    agua_pipas: '¿Recibe agua por pipas?', 
    agua_otro: '¿Existe otro tipo de suministro?', 
    cisterna: '¿Dispone de cisterna?', 
    tinacos: '¿Cuenta con tinacos?', 
    tanque: '¿Tiene tanque de almacenamiento?', 
    almacenamiento_otro: '¿Utiliza otro tipo de almacenamiento?', 
    //Baños
    estado_banos: '¿Cual es el estado del baño?', 
    banos_hombres_min: '¿Cual es la cantidad minima de baños de hombres', 
    banos_mujeres_min: '¿Cual es la cantidad minima de baños de mujeres', 
    lavamanos_min: '¿Cual es la cantidad minima de lavamanos?', 
    estado_lavamanos: '¿Cual es el estado de los lavamanos?', 
    tomas_bebederos_min: '¿Cual es la cantidad minima de bebederos?', 
    estado_bebederos: '¿Cual es el estado de los bebederos?', 
    //Drenaje
    drenaje_publico: '¿Cuenta con drenaje público?', 
    fosa_septica: '¿Cuenta con fosa septica?', 
    planta_tratamiento: '¿Cuenta con planta de tratamiento para aguas negras?', 
    descarga_otro: '¿Cuenta con otro tipo de descarga?', 
    separacion_aguas: '¿Cuenta con sepacion de aguas negras?', 
    //Estados de conservacion
    estado_red_hidraulica: '¿Cual es el estado de la red hidraulica', 
    estado_instalacion_sanitaria: '¿Cual es el estado de la instalación sanitaria?', 
    estado_instalacion_electrica: '¿Cual es el estado de la instalación electrica?', 
  };

  Object.entries(params).forEach(([key, value]) => {
    if (!etiquetas[key]) return;

    const texto = nombresLegibles[key] ||
              (camposNumericos.includes(key) ? value :
              value === '1' ? 'Sí' :
              value === '0' ? 'No' :
              value.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));

    const li = document.createElement('li');
    li.innerHTML = `<b>${etiquetas[key]}:</b> <span class="leyenda-badge">${texto}</span>`;
    lista.appendChild(li);
  });

  document.getElementById('leyenda-filtros-activos').style.display = 'block';

  document.getElementById('cerrar-leyenda-general')?.addEventListener('click', () => {
    document.getElementById('leyenda-filtros-activos').style.display = 'none';
  });
}
});


