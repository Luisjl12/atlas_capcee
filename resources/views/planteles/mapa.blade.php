@extends('layouts.app')

@section('title', 'Mapa de Planteles')

@section('content')

@include('partials.multifiltro')



<div class="card-header-custom">
    @php
    use App\Helpers\RoleHelper;
    @endphp
    <a href="{{ RoleHelper::mapaVista(session('role_id')) }}" class="btn-icon-only">
        <i class="fas fa-arrow-left"></i>
        <h2><i class="bi bi-geo-alt-fill"></i> Mapa de Planteles</h2>
    </a>
</div>

<div class="contenedor-mapa-filtros">
    <div class="sidebar-filtros">
        <h4>Regiones</h4>
        <input type="text" id="buscadorRegion" placeholder="Buscar región...">

        <div class="tipo-mapa-container" style="margin-bottom: 15px;">
            <label for="tipoMapa">Tipo de mapa:</label>
            <select id="tipoMapa" class="form-select">
                <option value="macro">Macroregiones</option>
                <option value="micro">Microregiones</option>
            </select>
        </div>
        <div class="lista-filtros">
            <h6>Macroregiones</h6>
            <div class="grupo-macro">
                <button class="filtro-btn" data-tipo="macro" data-nombre="Sierra Norte">Sierra Norte</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Sierra Nororiental">Sierra
                    Nororiental</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Valle Serdán">Valle de Serdán</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Angelópolis">Angelópolis</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Valle de Atlixco y Matamoros">Valle de Atlixco
                    y Matamoros</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Mixteca">Mixteca</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Tehuacán y Sierra Negra">Tehuacán y Sierra
                    Negra</button>
            </div>
            <hr>
            <h6>Microregiones</h6>
            <div class="grupo-micro">
                <button class="filtro-btn" data-tipo="micro" data-nombre="Xicotepec">Xicotepec</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Huauchinango">Huauchinango</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Zacapoaxtla">Zacapoaxtla</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Teziutlan">Teziutlán</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tlatlauquitepec">Tlatlauquitepec</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Libres">Libres</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Ciudad Serdan">Ciudad Serdán</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="tecamachalco">Tecamachalco</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Acatzingo">Acatzingo</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tepeaca">Tepeaca</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Puebla Capital">Puebla</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Amozoc">Amozoc</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Cholula">Cholula</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Huejotzingo">Huejotzingo</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Texmelucan">San Martín Texmelucan</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Atlixco">Atlixco</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Izucar">Izúcar</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Acatlan">Acatlán</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Chiautla">Chiautla</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tepexi">Tepexi de Rodríguez</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tehuacan">Tehuacán</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Ajalpan">Ajalpan</button>
            </div>

        </div>
    </div>

    <div class="contenedor-mapa">
        <!-- Botón flotante con menú de filtros -->
        <div style="position: absolute; top: 20px; right: 20px; z-index: 1000;">
            <div class="dropdown">
                <button class="btn-custom btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Aplicar filtros
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <h6 class="dropdown-header">Búsqueda rápida</h6>
                    </li>
                    <li class="px-3">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="input-cct" placeholder=" CCT">
                            <button class="btn btn-primary" type="button" id="btn-buscar-cct">Buscar</button>
                        </div>
                    </li>

                    <li>
                        <h6 class="dropdown-header">Filtro avanzado</h6>
                    </li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalMultifiltro">Filtros avanzados</a></li>
                    
                
                </ul>
            </div>
        </div>
        <div id="loader" style="
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,255,255,0.9);
            padding: 20px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 9999;
        ">
            Cargando mapa...
        </div>

        <!--Leyenda flotante sobre el mapa-->
        <div class="contenedor-leyenda">
            <!-- Leyenda de filtros para superficie -->
            <div id="leyenda-superficie" class="leyenda-superficie" style="display: none;">
                <button id="cerrar-leyenda-superficie" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Filtros activos:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Superficie:</b> <span class="leyenda-badge" id="leyenda-superficie-texto"></span></li>
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-nivel-texto"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-region-texto"></span></li>
                </ul>
            </div>

            <!--Leyenda de filtros para obras nuevas -->
            <div id="leyenda-obras" class="leyenda-obras" style="display: none;">
                <button id="cerrar-leyenda-obras" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Obras filtradas:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-obras-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-obras-region"></span></li>
                    <li><b>Intervenciones:</b> <span class="leyenda-badge" id="leyenda-obras-tipo"></span></li>
                </ul>
            </div>

            <!--Leyendad de filtros para aguas-->
            <div id="leyenda-agua" class="leyenda-agua" style="display: none;">
                <button id="cerrar-leyenda-agua" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Detalle hidráulico filtrado:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-agua-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-agua-region"></span></li>
                    <li><b>Características:</b> <span class="leyenda-badge" id="leyenda-agua-tipo"></span></li>
                </ul>
            </div>

            <!--Lyenda para filtros electricidad-->
            <div id="leyenda-energia" class="leyenda-energia" style="display: none;">
                <button id="cerrar-leyenda-energia" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Infraestructura energética filtrada:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-energia-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-energia-region"></span></li>
                    <li><b>Características:</b> <span class="leyenda-badge" id="leyenda-energia-tipo"></span></li>
                </ul>
            </div>

            <!--Leynda para filtros de drenaje--->
            <div id="leyenda-drenaje" class="leyenda-drenaje" style="display: none;">
                <button id="cerrar-leyenda-drenaje" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Infraestructura de drenaje filtrada:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-drenaje-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-drenaje-region"></span></li>
                    <li><b>Características:</b> <span class="leyenda-badge" id="leyenda-drenaje-tipo"></span></li>
                </ul>
            </div>

            <!--Leyenda para filtros de accesibilidad-->
            <div id="leyenda-accesibilidad" class="leyenda-accesibilidad" style="display: none;">
                <button id="cerrar-leyenda-accesibilidad" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Filtros de accesibilidad aplicados:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-accesibilidad-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-accesibilidad-region"></span></li>
                    <li><b>Categoría:</b> <span class="leyenda-badge" id="leyenda-accesibilidad-categoria"></span></li>
                    <li><b>Infraestructura:</b> <span class="leyenda-badge" id="leyenda-accesibilidad-tipo"></span></li>
                </ul>
            </div>

            <!--Leyenda para filtros segun el estado de conservacion-->
            <div id="leyenda-estado" class="leyenda-estado" style="display: none;">
                <button id="cerrar-leyenda-estado" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Filtros de conservación aplicados:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-estado-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-estado-region"></span></li>
                    <li><b>Red hidráulica:</b> <span class="leyenda-badge" id="leyenda-estado-red"></span></li>
                    <li><b>Instalación sanitaria:</b> <span class="leyenda-badge" id="leyenda-estado-sanitaria"></span></li>
                    <li><b>Instalación eléctrica:</b> <span class="leyenda-badge" id="leyenda-estado-electrica"></span></li>
                </ul>
            </div>

            <!--Leyenda para filtros segun sanitarios-->
            <div id="leyenda-sanitarios" class="leyenda-sanitarios" style="display: none;">
                <button id="cerrar-leyenda-sanitarios" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Filtros de sanitarios aplicados:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-sanitarios-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-sanitarios-region"></span></li>
                    <li><b>Estado baños:</b> <span class="leyenda-badge" id="leyenda-sanitarios-banos"></span></li>
                    <li><b>Estado lavamanos:</b> <span class="leyenda-badge" id="leyenda-sanitarios-lavamanos"></span></li>
                    <li><b>Estado bebederos:</b> <span class="leyenda-badge" id="leyenda-sanitarios-bebederos"></span></li>
                </ul>
            </div>

            <!--Leyenda para filtros segun la seguridad-->
            <div id="leyenda-seguridad" class="leyenda-seguridad" style="display: none;">
                <button id="cerrar-leyenda-seguridad" class="cerrar-leyenda" title="Ocultar leyenda">✖</button>
                <strong>Filtros de seguridad aplicados:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-seguridad-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-seguridad-region"></span></li>
                    <li><b>Protección Civil:</b> <span class="leyenda-badge" id="leyenda-seguridad-pc"></span></li>
                    <li><b>Barda completa:</b> <span class="leyenda-badge" id="leyenda-seguridad-barda"></span></li>
                    <li><b>Estado barda:</b> <span class="leyenda-badge" id="leyenda-seguridad-estado-barda"></span></li>
                    <li><b>Estado cerca:</b> <span class="leyenda-badge" id="leyenda-seguridad-estado-cerca"></span></li>
                </ul>
            </div>

            <!--Contenedor de leyendas para el multifiltro-->
            <div id="leyenda-filtros-activos" class="leyenda-general" style="display: none;">
                <button id="cerrar-leyenda-general" class="cerrar-leyenda">✖</button>
                <strong>Filtros aplicados:</strong>
                <ul id="lista-filtros-activos" style="margin: 0; padding-left: 18px;"></ul>
                <!-- Contador de planteles -->
                <!-- Botón de descarga CSV -->
                <button id="btn-descargar-csv" class="btn btn-success" style="margin-top:10px; display:none;"> Descargar CSV </button>
                <div id="contador-planteles" style="display: none;">
                    <strong>Planteles encontrados:</strong> <span class="leyenda-badge" id="contador-planteles-numero">0</span>
                </div>
            </div>
        </div>

        <div id="map" style="height: 600px; border-radius: 8px; background: #fafafa;"></div>
    </div>
</div>

<!-- Modal Informativo -->
<div id="modalInformativo" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h5><i class="fas fa-info-circle"></i> Información</h5>
        <p id="mensajeInformativo">Mensaje informativo</p>
        <div class="modal-actions-info">
            <button id="btnCerrarInfo" class="btn-custom btn-aceptar">Aceptar</button>
        </div>
    </div>
</div>



{{-- Estilos de Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

{{-- Script de Leaflet --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!--Script para graficar los mapas-->
<script src="{{ asset('js/mapa_macroregiones.js') }}"></script>

<!--Script para los filtros-->
<script src="{{ asset('js/modal-info.js') }}"></script>
<script src="{{ asset('js/filtrosPlanteles.js') }}"></script>

<!--Script para el filtro de agua-->
<script src="{{ asset('js/filtro_agua.js') }}"></script>

<!--script para el filtro de energia-->
<script src="{{ asset('js/filtro_energia.js') }}"></script>

<!--script para el filtro de drenaje-->
<script src="{{ asset('js/filtro_drenaje.js') }}"></script>

<!--script para filtrar por estado-->
<script src="{{ asset('js/filtro_estado.js') }}"></script>

<!--script para filtrar por obras realizadas-->
<script src="{{ asset('js/filtro_obras.js') }}"></script>

<!--scrip para filtrar por seguridad-->
<script src="{{ asset('js/filtro_seguridad.js') }}"></script>

<!--script para filtrar por accesibilidad-->
<script src="{{ asset('js/filtro_accesibilidad.js') }}"></script>

<!--script para filtrar por número y estado de sanitarios-->
<script src="{{ asset('js/filtro_sanitarios.js') }}"></script>

<!--script para el multifiltro-->
<script>
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
      console.log('[DEBUG] DOM completamente cargado');

  const btnLimpiar = document.getElementById('btnLimpiarFiltros');
  if (!btnLimpiar) {
    console.warn('[DEBUG] Botón #btnLimpiarFiltros no encontrado en el DOM');
    return;
  }

  btnLimpiar.addEventListener('click', function () {
    console.log('[DEBUG] Clic en botón Limpiar Filtros');

    const form = document.getElementById('formMultifiltro');
    if (!form) {
      console.warn('[DEBUG] Formulario #formMultifiltro no encontrado');
      return;
    }

    const inputs = form.querySelectorAll('input, select, textarea');
    console.log(`[DEBUG] Total de inputs encontrados: ${inputs.length}`);

    inputs.forEach(input => {
      const tipo = input.type;
      const nombre = input.name || input.id || '[sin nombre]';
      const valorAntes = input.value;

      if (tipo === 'checkbox' || tipo === 'radio') {
        input.checked = false;
        console.log(`[DEBUG] ${nombre} (${tipo}) → checked = false`);
      } else {
        input.value = '';
        console.log(`[DEBUG] ${nombre} (${tipo}) → valor: "${valorAntes}" → ""`);
      }
    });

    if (typeof $ !== 'undefined' && $('.select2').length > 0) {
      console.log('[DEBUG] Reiniciando select2');
      $('.select2').val(null).trigger('change');
    } else {
      console.warn('[DEBUG] jQuery o select2 no disponible');
    }

    const leyenda = document.getElementById('leyenda-filtros-activos');
    const lista = document.getElementById('lista-filtros-activos');
    if (leyenda) {
      leyenda.style.display = 'none';
      console.log('[DEBUG] Ocultando leyenda de filtros activos');
    }
    if (lista) {
      lista.innerHTML = '';
      console.log('[DEBUG] Limpiando lista de filtros activos');
    }

    if (typeof map !== 'undefined' && map.eachLayer) {
      console.log('[DEBUG] Eliminando marcadores del mapa');
      map.eachLayer(layer => {
        if (layer instanceof L.Marker) {
          map.removeLayer(layer);
        }
      });
    } else {
      console.warn('[DEBUG] Objeto "map" no definido o sin método eachLayer');
    }

    const contador = document.getElementById('contador-planteles');
    if (contador) {
      contador.style.display = 'none';
      console.log('[DEBUG] Ocultando contador de planteles');
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
  
  document.getElementById('leyenda-filtros-activos').style.display = 'block'

  const btnCSV = document.getElementById('btn-descargar-csv'); 
  btnCSV.style.display = 'inline-block';

  const queryString = new URLSearchParams(params).toString();
  const categoriaSeleccionada = params.categoria || 'sanitario';
  const url = `/exportar-csv/${categoriaSeleccionada}?${queryString}`;

  btnCSV.onclick = () => {
    window.location.href = url;
  };  

  document.getElementById('cerrar-leyenda-general')?.addEventListener('click', () => {
    document.getElementById('leyenda-filtros-activos').style.display = 'none';
    btnCSV.style.display = 'none';
  });

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

</script>

<!--script para buscador rapido--->
<script src="{{ asset('js/buscador_rapido.js') }}"></script>

<script>
    window.alert = function(mensaje) {
        mostrarModalInformativo(mensaje);
    };
</script>

<x-modal-filtros
    id="modal-agua"
    formId="form-agua"
    titulo="Filtro por suministro de agua">
    @include('partials.filtros_agua', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>


<x-modal-filtros
    id="modal-energia"
    formId="form-energia"
    titulo="Filtro por suministro de energía">
    @include('partials/filtro_energia', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-drenaje"
    formId="form-drenaje"
    titulo="Filtro por drenaje">
    @include('partials.filtro_drenaje', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>


<x-modal-filtros
    id="modal-instalaciones"
    formId="form-instalaciones"
    titulo="Filtro por estado de instalaciones">

    @include('partials.filtro_estado', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-obras"
    formId="form-obras"
    titulo="Filtro por obras realizadas en los últimos 5 años">

    @include('partials.filtro_obras', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-seguridad"
    formId="form-seguridad"
    titulo="Filtro por condiciones de seguridad del inmueble">

    @include('partials.filtro_seguridad', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-accesibilidad"
    formId="form-accesibilidad"
    titulo="Filtro por accesibilidad el inmueble">
    @include('partials.filtro_accesibilidad', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-sanitarios"
    formId="form-sanitarios"
    titulo="Filtro por número y estado de sanitarios">
    @include('partials.filtro_sanitarios', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-superficie"
    formId="form-superficie"
    titulo="Filtro por superficie del inmueble">
    @include('partials.filtro_superficie', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles,
    'rangosSuperficie' => $rangosSuperficie
    ])
</x-modal-filtros>



@endsection