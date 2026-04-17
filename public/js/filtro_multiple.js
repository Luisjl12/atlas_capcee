
document.addEventListener('DOMContentLoaded', () => {
  // Ayuda a atrapar errores no esperados
  window.addEventListener('error', (e) => {
    console.error('[GLOBAL ERROR]', e.message, 'en', e.filename + ':' + e.lineno);
  });

  console.log('[DEBUG] DOM completamente cargado');

  // Helpers
  const qs = sel => document.querySelector(sel);
  const g = id => document.getElementById(id);

  // Comprobar existencia de elementos clave
  const form = g('formMultifiltro');
  const btnAplicar = g('btnAplicarFiltros');
  const btnLimpiar = g('btnLimpiarFiltros');

  if (!form) console.warn('[DEBUG] #formMultifiltro no encontrado en el DOM');
  if (!btnAplicar) console.warn('[DEBUG] #btnAplicarFiltros no encontrado en el DOM');
  if (!btnLimpiar) console.warn('[DEBUG] #btnLimpiarFiltros no encontrado en el DOM');

  // ---------- Funciones auxiliares ----------
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

  function limpiarMarcadoresMapa() {
    if (typeof map !== 'undefined' && map && typeof map.eachLayer === 'function') {
      map.eachLayer(layer => {
        if (layer instanceof L.Marker) map.removeLayer(layer);
      });
      console.log('[DEBUG] Marcadores eliminados del mapa');
    } else {
      console.warn('[DEBUG] map no definido o no tiene eachLayer');
    }
  }

  // ---------- Mostrar planteles en mapa ----------
  function mostrarPlantelesEnMapa(planteles) {
    try {
      if (!Array.isArray(planteles)) {
        console.warn('[DEBUG] planteles no es array:', planteles);
        return;
      }

      const visibles = planteles.filter(p => p.latitud && p.longitud);
      const contadorNumero = g('contador-planteles-numero');
      const contador = g('contador-planteles');

      if (contadorNumero) contadorNumero.textContent = visibles.length;
      if (contador) contador.style.display = 'block';

      if (visibles.length === 0) {
        alert('No se encontraron planteles con los filtros seleccionados.');
        limpiarMarcadoresMapa();
        if (contador) contador.style.display = 'none';
        return;
      }

      limpiarMarcadoresMapa();

      visibles.forEach(p => {
        const niveles = Array.isArray(p.niveles)
          ? p.niveles.map(n => (n.nivel || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())).join(', ')
          : 'Sin nivel registrado';

        if (p.latitud && p.longitud && !isNaN(parseFloat(p.latitud)) && !isNaN(parseFloat(p.longitud))) {
          L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], {
            icon: crearIconoPorEstado(p.estatus_plantel)
          }).addTo(map).bindPopup(`
            <b>${p.nombre_escuela || '—'}</b><br>
            CCT: ${p.cct || '—'}<br>
            <b>Nivel educativo:</b> ${niveles}<br>
            Estado: ${normalizarEstado(p.estatus_plantel)}<br>
            Municipio: ${p.municipio?.nombre_municipio || 'Sin dato'}<br>
            Localidad: ${p.localidad?.nombre_localidad || 'Sin dato'}<br>
            <a href="/planteles/${p.id}" target="_blank">Ver detalles</a>
          `);
        }
      });

      // ocultar contador si se desea (aquí lo dejamos visible)
    } catch (err) {
      console.error('[ERROR mostrarPlantelesEnMapa]', err);
    }
  }
  

  // ---------- Mostrar leyenda ----------
  function mostrarLeyendaFiltros(params = {}, nombresLegibles = {}) {
    try {
      const lista = g('lista-filtros-activos');
      if (!lista) {
        console.warn('[DEBUG] #lista-filtros-activos no encontrado');
        return;
      }
      lista.innerHTML = '';

      const camposNumericos = ['banos_hombres_min','banos_mujeres_min','lavamanos_min','tomas_bebederos_min'];
      const etiquetas = {
        nivel: 'Nivel educativo',
        macroregion: 'Macroregión',
        microregion: 'Microregión',
        municipio: 'Municipio',
        suministro_energia: '¿Tiene suministro eléctrico?',
        energia_paneles_solares: '¿Tiene paneles solares?',
        energia_planta: '¿Tiene planta eléctrica?',
        // ... (mantén el resto de etiquetas si las necesitas)
      };

      Object.entries(params).forEach(([key, value]) => {
        if (!etiquetas[key]) return;
        const texto = nombresLegibles[key] ||
          (camposNumericos.includes(key) ? value :
          value === '1' ? 'Sí' :
          value === '0' ? 'No' :
          String(value).replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));

        const li = document.createElement('li');
        li.innerHTML = `<b>${etiquetas[key]}:</b> <span class="leyenda-badge">${texto}</span>`;
        lista.appendChild(li);
      });

      const leyenda = g('leyenda-filtros-activos');
      if (leyenda) leyenda.style.display = 'block';

      const cerrar = g('cerrar-leyenda-general');
      if (cerrar) cerrar.addEventListener('click', () => {
        if (leyenda) leyenda.style.display = 'none';
      });
    } catch (err) {
      console.error('[ERROR mostrarLeyendaFiltros]', err);
    }
  }

  // ---------- Lógica Aplicar filtros ----------
  if (btnAplicar) {
    btnAplicar.addEventListener('click', (ev) => {
      try {
        ev.preventDefault();
        if (!form) {
          console.warn('[DEBUG] formulario no disponible al aplicar filtros');
          return;
        }

        const formData = new FormData(form);
        const params = {};

        const macroregionSelect = g('macroregion');
        const microregionSelect = g('microregion');
        const municipioSelect = g('municipio');

        const macroregionNombre = macroregionSelect?.value ? macroregionSelect.options[macroregionSelect.selectedIndex]?.text || '' : '';
        const microregionNombre = microregionSelect?.value ? microregionSelect.options[microregionSelect.selectedIndex]?.text || '' : '';
        const municipioNombre = municipioSelect?.value ? municipioSelect.options[municipioSelect.selectedIndex]?.text || '' : '';

        const nombresLegibles = { macroregion: macroregionNombre, microregion: microregionNombre, municipio: municipioNombre };

        for (const [key, value] of formData.entries()) {
          if (value !== '' && value !== null) params[key] = value;
        }

        const tieneRegion = params.macroregion || params.microregion || params.municipio;
        const tieneNivel = params.nivel;

       

        if (typeof axios === 'undefined') {
          console.error('[DEBUG] axios no está cargado');
          return;
        }

        axios.get('/filtrar-avanzado', { params })
          .then(response => {
            const planteles = response?.data?.data || [];
            console.log('Resultados filtrados:', planteles);
            mostrarPlantelesEnMapa(planteles);
            mostrarLeyendaFiltros(params, nombresLegibles);
          })
          .catch(error => {
            console.error('Error al aplicar filtros:', error);
          });

        const modalEl = g('modalMultifiltro');
        if (modalEl && typeof bootstrap !== 'undefined') {
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        }
      } catch (err) {
        console.error('[ERROR aplicar filtros]', err);
      }
    });
  }

  // ---------- Lógica Limpiar filtros ----------
  if (btnLimpiar) {
    btnLimpiar.addEventListener('click', (ev) => {
      try {
        ev.preventDefault();
        if (!form) {
          console.warn('[DEBUG] formulario no disponible al limpiar');
          return;
        }

        console.log('[DEBUG] Clic en botón Limpiar Filtros');
        const inputs = form.querySelectorAll('input, select, textarea');
        console.log(`[DEBUG] Total de inputs encontrados: ${inputs.length}`);

        inputs.forEach(input => {
          if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
          } else {
            input.value = '';
          }
        });

        // reset select2 si existe
        if (typeof $ !== 'undefined' && $('.select2').length > 0) {
          $('.select2').val(null).trigger('change');
          console.log('[DEBUG] select2 reiniciado');
        }

        const lista = g('lista-filtros-activos');
        const leyenda = g('leyenda-filtros-activos');
        if (lista) lista.innerHTML = '';
        if (leyenda) leyenda.style.display = 'none';

        limpiarMarcadoresMapa();

        const contador = g('contador-planteles');
        if (contador) contador.style.display = 'none';
      } catch (err) {
        console.error('[ERROR limpiar filtros]', err);
      }
    });
  }

  // Exponer en window para facilitar debugging en consola
  window.__mostrarPlantelesEnMapa = mostrarPlantelesEnMapa;
  window.__mostrarLeyendaFiltros = mostrarLeyendaFiltros;
});
