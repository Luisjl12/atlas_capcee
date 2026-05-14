document.addEventListener('DOMContentLoaded', function () {
    window.map = L.map('map', {
        minZoom: 8
    }).setView([19.0414, -98.2063], 7);

    let capaPuebla;
    let municipioSeleccionado = null;
    fetch('/geojson/municipios_limpios_dos.json')
        .then(res => res.json())
        .then(data => {
            capaPuebla = L.geoJSON(data, {
                style: {
                    color: '#3b3303',
                    weight: 1,
                    fillOpacity: 0
                }
            }).addTo(map);

            map.fitBounds(capaPuebla.getBounds(), { padding: [0, 0] });
            map.setZoom(map.getZoom() + 2);
        })
        .catch(err => console.error('Error al cargar el GeoJSON de Puebla:', err));

    function mostrarMunicipio(nombreMunicipio) {
        if (municipioSeleccionado) {
            municipioSeleccionado.setStyle({
                color: '#7a0019',
                weight: 1.5,
                fillOpacity: 0
            });
            municipioSeleccionado.closePopup();
            municipioSeleccionado = null;
        }

        capaPuebla.eachLayer(layer => {
            const props = layer.feature.properties;
            if (props.NOMGEO.toLowerCase() === nombreMunicipio.toLowerCase()) {
                layer.setStyle({
                    color: '#cc0000',
                    weight: 3,
                    fillColor: '#a52a2a',
                    fillOpacity: 0.4
                });
                map.fitBounds(layer.getBounds());
                layer.bindPopup(`<b>${props.NOMGEO}</b>`).openPopup();
                municipioSeleccionado = layer;
            }
        });
    }

    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const nombre = btn.textContent.trim();
            mostrarMunicipio(nombre);
        });
    });

    fetch('/geojson/municipios_limpios_dos.json')
        .then(res => res.json())
        .then(data => {
            window._geojsonData = data;
            cargarMacroregionesPorLotes(data);

            const tipoMapa = document.getElementById('tipoMapa');
            if (tipoMapa) {
                tipoMapa.addEventListener('change', function (e) {
                    limpiarMapa();
                    mostrarLoader();
                    if (e.target.value === 'macro') {
                        cargarMacroregionesPorLotes(data);
                    } else {
                        cargarMicroregionesPorLotes(data);
                    }
                });
            }

            const selectorRegion = document.getElementById('selectorRegion');
            if (selectorRegion) {
                selectorRegion.addEventListener('change', function (e) {
                    const valor = e.target.value;
                    if (!valor) return;
                    const [tipo, nombre] = valor.split(':');
                    limpiarMapa();
                    mostrarLoader();
                    const color = '#cc0000';
                    setTimeout(() => {
                        cargarRegionDesdeData(tipo, nombre, color, data);
                        const bounds = L.featureGroup(capasActivas).getBounds();
                        map.fitBounds(bounds);
                        ocultarLoader();
                    }, 50);
                });
            }

            document.querySelectorAll('.filtro-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const tipo = btn.dataset.tipo;
                    const nombre = btn.dataset.nombre;

                    limpiarMapa();
                    mostrarLoader();

                    setTimeout(() => {
                        cargarRegionDesdeData(tipo, nombre, "#cc0000", window._geojsonData);
                        const bounds = L.featureGroup(capasActivas).getBounds();
                        map.fitBounds(bounds);
                        ocultarLoader();
                    }, 100);
                });
            });

        });

    const regiones = {
        "Sierra Norte": [
            "Zacatlán", "Huauchinango", "Xicotepec", "Chignahuapan", "Ahuacatlán",
            "Ahuazotepec", "Amixtlán", "Aquixtla", "Camocuautla", "Chiconcuautla",
            "Coatepec", "Cuautempan", "Francisco Z. Mena", "Hermenegildo Galeana",
            "Honey", "Huitzilan de Serdán", "Ixtacamaxtitlán", "Jalpan", "Jopala",
            "Juan Galindo", "Naupan", "Pahuatlán", "Pantepec", "San Felipe Tepatlán",
            "Tepango de Rodríguez", "Tepetzintla", "Tetela de Ocampo", "Tlacuilotepec",
            "Tlaola", "Tlapacoya", "Tlaxco", "Venustiano Carranza", "Xochiapulco", "Xochitlán de Vicente Suárez",
            "Zapotitlán de Méndez", "Zautla", "Zihuateutla", "Zongozotla"
        ],
        "Sierra Nororiental": [
            "Acateno", "Atempan", "Atlequizayan", "Ayotoxco de Guerrero", "Caxhuacan",
            "Chignautla", "Cuetzalan del Progreso", "Cuyoaco", "Huehuetla", "Hueyapan",
            "Hueytamalco", "Hueytlalpan", "Ixtepec", "Jonotla", "Nauzontla", "Olintla",
            "Tenampulco", "Teteles de Ávila Castillo", "Teziutlán", "Tlatlauquitepec",
            "Tuzamapan de Galeana", "Xiutetelco", "Yaonáhuac", "Zacapoaxtla", "Zaragoza", "Zoquiapan"
        ],
        "Valle Serdán": [
            "Acatzingo", "Aljojuca", "Atoyatempan", "Atzitzintla", "Cañada Morelos", "Chalchicomula de Sesma", "Chichiquila",
            "Chilchotla", "Cuapiaxtla de Madero", "Esperanza", "General Felipe Ángeles", "Guadalupe Victoria", "Huitziltepec", "Lafragua",
            "Libres", "Los Reyes de Juárez", "Mazapiltepec de Juárez", "Mixtla", "Nopalucan", "Ocotepec", "Oriental",
            "Palmar de Bravo", "Quecholac", "Quimixtlán", "Rafael Lara Grajales", "San José Chiapa", "San Juan Atenco", "San Nicolás Buenos Aires",
            "San Salvador el Seco", "San Salvador Huixcolotla", "Santo Tomás Hueyotlipan", "Soltepec", "Tecamachalco",
            "Tepeaca", "Tepeyahualco", "Tepeyahualco de Cuauhtémoc", "Tlachichuca", "Tlanepantla", "Tochtepec", "Yehualtepec"
        ],
        "Angelópolis": [
            "Acajete", "Amozoc", "Calpan", "Chiautzingo", "Coronango", "Cuautinchán", "Cuautlancingo", "Domingo Arenas",
            "Huejotzingo", "Juan C. Bonilla", "Puebla", "San Andrés Cholula",
            "San Felipe Teotlalcingo", "San Martín Texmelucan", "San Matías Tlalancaleca", "San Miguel Xoxtla", "San Pedro Cholula",
            "San Salvador el Verde", "Tecali de Herrera", "Tepatlaxco de Hidalgo", "Tlahuapan", "Tlaltenango"
        ],
        "Valle de Atlixco y Matamoros": [
            "Acteopan", "Atlixco", "Atzala", "Atzitzihuacán", "Chietla", "Cohuecán", "Epatlán", "Huaquechula",
            "Izúcar de Matamoros", "Nealtican", "Ocoyucan", "San Gregorio Atzompa", "San Jerónimo Tecuanipan", "San Nicolás de los Ranchos", "Santa Isabel Cholula",
            "Tepemaxalco", "Tepeojuma", "Tepexco", "Tianguismanalco", "Tilapa", "Tlapanalá", "Tochimilco"
        ],
        "Mixteca": ["Acatlán", "Ahuatlán", "Ahuehuetitla", "Albino Zertuche", "Axutla", "Chiautla", "Chigmecatitlán", "Chila",
            "Chila de la Sal", "Chinantla", "Coatzingo", "Cohetzala", "Cuayuca de Andrade", "Guadalupe", "Huatlatlauca", "Huehuetlán el Chico",
            "Huehuetlán el Grande", "Ixcamilpa de Guerrero", "Jolalpan", "La Magdalena Tlatlauquitepec", "Petlalcingo", "Piaxtla", "San Diego la Mesa Tochimiltzingo", "San Jerónimo Xayacatlán",
            "San Juan Atzompa", "San Martín Totoltepec", "San Miguel Ixitlán", "San Pablo Anicano", "San Pedro Yeloixtlahuaca", "Santa Catarina Tlaltempan",
            "Santa Inés Ahuatempan", "Tecomatlán", "Tehuitzingo", "Teopantlán", "Teotlalco", "Tepexi de Rodríguez", "Totoltepec de Guerrero",
            "Tulcingo", "Tzicatlacoyan", "Xayacatlán de Bravo", "Xicotlán", "Xochiltepec", "Zacapala"
        ],
        "Tehuacán y Sierra Negra": ["Ajalpan", "Altepexi", "Atexcal", "Caltepec", "Chapulco", "Coxcatlán", "Coyomeapan",
            "Coyotepec", "Eloxochitlán", "Ixcaquixtla", "Juan N. Méndez", "Molcaxac", "Nicolás Bravo", "San Antonio Cañada", "San Gabriel Chilac",
            "San José Miahuatlán", "San Sebastián Tlacotepec", "Santiago Miahuatlán", "Tehuacán", "Tepanco de López", "Tlacotepec de Benito Juárez",
            "Vicente Guerrero", "Xochitlán Todos Santos", "Zapotitlán", "Zinacatepec", "Zoquitlán"
        ]
    };
    const microregiones = {
        "Xicotepec": ["Francisco Z. Mena", "Honey", "Jalpan", "Jopala", "Naupan", "Pahuatlán", "Pantepec",
            "Tlacuilotepec", "Tlapacoya", "Tlaxco", "Venustiano Carranza", "Xicotepec", "Zihuateutla"
        ],
        "Huauchinango": ["Ahuazotepec", "Chiconcuautla", "Huauchinango", "Juan Galindo", "Tlaola", "Zacatlán"],
        "Chignahuapan": ["Aquixtla", "Chignahuapan", "Ixtacamaxtitlán", "Tetela de Ocampo", "Xochiapulco", "Zautla"],
        "Cuautempan": ["Ahuacatlán", "Amixtlán", "Camocuautla", "Coatepec", "Cuautempan", "Hermenegildo Galeana", "Huitzilan de Serdán",
            "San Felipe Tepatlán", "Tepango de Rodríguez", "Tepetzintla", "Xochitlán de Vicente Suárez", "Zapotitlán de Méndez", "Zongozotla"
        ],
        "Zacapoaxtla": ["Atlequizayan", "Caxhuacan", "Cuetzalan del Progreso", "Huehuetla", "Hueytlalpan", "Ixtepec",
            "Jonotla", "Nauzontla", "Olintla", "Tuzamapan de Galeana", "Zacapoaxtla", "Zoquiapan"
        ],
        "Teziutlan": ["Acateno", "Atempan", "Ayotoxco de Guerrero", "Hueyapan", "Hueytamalco", "Tenampulco", "Teteles de Avila Castillo", "Teziutlán", "Yaonáhuac"],
        "Tlatlauquitepec": ["Chignautla", "Cuyoaco", "Tlatlauquitepec", "Xiutetelco", "Zaragoza"],
        "Libres": ["Chichiquila", "Chilchotla", "Guadalupe Victoria", "Lafragua", "Libres", "Ocotepec", "Oriental", "Quimixtlán", "Tepeyahualco"],
        "Ciudad Serdan": ["Aljojuca", "Atzitzintla", "Chalchicomula de Sesma", "Esperanza", "General Felipe Ángeles", "San Juan Atenco", "Tlachichuca"],

        "tecamachalco": ["Cañada Morelos", "Palmar de Bravo", "Quecholac", "San Salvador Huixcolotla", "Tecamachalco", "Yehualtepec"],
        "Acatzingo": ["Acatzingo", "Mazapiltepec de Juárez", "Nopalucan", "Rafael Lara Grajales", "San José Chiapa", "San Nicolás Buenos Aires", "San Salvador el Seco", "Soltepec"],
        "Tepeaca": ["Atoyatempan", "Cuapiaxtla de Madero", "Huitziltepec", "Los Reyes de Juárez", "Mixtla", "Santo Tomás Hueyotlipan", "Tepeaca", "Tepeyahualco de Cuauhtémoc", "Tlanepantla", "Tochtepec"],
        "Puebla Capital": ["Puebla"],
        "Amozoc": ["Acajete", "Amozoc", "Cuautinchán", "Tecali de Herrera", "Tepatlaxco de Hidalgo"],
        "Cholula": ["Cuautlancingo", "San Andrés Cholula", "San Pedro Cholula"],
        "Huejotzingo": ["Calpan", "Chiautzingo", "Coronango", "Domingo Arenas", "Huejotzingo", "Juan C. Bonilla", "San Miguel Xoxtla", "Tlaltenango"],
        "Texmelucan": ["San Felipe Teotlalcingo", "San Martín Texmelucan", "San Matías Tlalancaleca", "San Salvador el Verde", "Tlahuapan"],
        "Atlixco": ["Atlixco", "Nealtican", "Ocoyucan", "San Gregorio Atzompa", "San Jerónimo Tecuanipan", "Santa Isabel Cholula", "Tianguismanalco"],
        "Izucar": ["Acteopan", "Atzala", "Atzitzihuacán", "Chietla", "Cohuecán", "Epatlán", "Huaquechula", "Izúcar de Matamoros", "San Nicolás de los Ranchos",
            "Tepemaxalco", "Tepeojuma", "Tepexco", "Tilapa", "Tlapanalá", "Tochimilco"
        ],
        "Acatlan": ["Acatlán", "Ahuehuetitla", "Axutla", "Chila", "Chinantla", "Guadalupe", "Petlalcingo", "Piaxtla", "San Jerónimo Xayacatlán",
            "San Miguel Ixitlán", "San Pablo Anicano", "San Pedro Yeloixtlahuaca", "Tecomatlán", "Tehuitzingo", "Totoltepec de Guerrero", "Tulcingo", "Xayacatlán de Bravo"
        ],
        "Chiautla": ["Albino Zertuche", "Chiautla", "Chila de la Sal", "Cohetzala", "Huehuetlán el Chico", "Ixcamilpa de Guerrero", "Jolalpan", "Teotlalco", "Xicotlán"],
        "Tepexi": ["Ahuatlán", "Chigmecatitlán", "Coatzingo", "Cuayuca de Andrade", "Huatlatlauca", "Huehuetlán el Grande", "La Magdalena Tlatlauquitepec", "San Diego la Mesa Tochimiltzingo", "San Juan Atzompa",
            "San Martín Totoltepec", "Santa Catarina Tlaltempan", "Santa Inés Ahuatempan", "Teopantlán", "Tepexi de Rodríguez", "Tzicatlacoyan", "Xochiltepec", "Zacapala"
        ],
        "Tehuacan": ["Atexcal", "Caltepec", "Chapulco", "Coyotepec", "Ixcaquixtla", "Juan N. Méndez", "Molcaxac", "Nicolás Bravo", "Santiago Miahuatlán",
            "Tehuacán", "Tepanco de López", "Tlacotepec de Benito Juárez", "Xochitlán Todos Santos", "Zapotitlán"
        ],
        "Ajalpan": ["Ajalpan", "Altepexi", "Coxcatlán", "Coyomeapan", "Eloxochitlán", "San Antonio Cañada", "San Gabriel Chilac", "San José Miahuatlán", "San Sebastián Tlacotepec",
            "Vicente Guerrero", "Zinacatepec", "Zoquitlán"
        ]
    };

    const coloresMacro = ["#7a0019"];
    let capasActivas = [];

    function limpiarMapa() {
        capasActivas.forEach(capa => map.removeLayer(capa));
        capasActivas = [];
    }

    function fusionarMunicipios(features) {
        return features.reduce((acumulado, actual) => {
            return acumulado ? turf.union(acumulado, actual) : actual;
        }, null);
    }

    function cargarRegionDesdeData(tipo, nombre, color, data) {
        const fuente = tipo === 'macro' ? regiones : microregiones;
        const municipios = fuente[nombre] || [];

        const seleccionados = data.features.filter(f =>
            municipios.includes(f.properties.NOMGEO)
        );

        const geometriaUnida = municipios.length > 1
            ? fusionarMunicipios(seleccionados)
            : seleccionados[0];

        const estilo = {
            color: color,
            weight: 3,
            fillColor: '#f0f0f0',
            fillOpacity: 0.3
        };

        const capa = L.geoJSON(geometriaUnida, { style: estilo }).addTo(map);
        capasActivas.push(capa);
    }

    const capaProyectos = L.featureGroup().addTo(map);
    let listaProyectos = [];

    // Llamada inicial para cargar todo
    cargarMarcadoresProyectos();

    const leyenda = L.control({ position: 'topright' });

leyenda.onAdd = function (map) {
    const div = L.DomUtil.create('div', 'leyenda-mapa');
    div.style.backgroundColor = 'white';
    div.style.padding = '12px';
    div.style.borderRadius = '8px';
    div.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    div.style.fontFamily = 'Arial, sans-serif';
    div.style.fontSize = '13px';
    div.style.color = '#333';
    div.style.lineHeight = '1.5';

    div.innerHTML = `
        <h4 style="margin: 0 0 10px 0; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
            <i class="fas fa-info-circle"></i> Simbología
        </h4>
        
        <strong style="display: block; margin-bottom: 5px;">Año del Proyecto</strong>
        <div style="display: flex; align-items: center; margin-bottom: 5px;">
            <div style="width: 14px; height: 14px; background: #366159; border-radius: 50%; margin-right: 8px;"></div> 
            <span>2026</span>
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 5px;">
            <div style="width: 14px; height: 14px; background: #861E34; border-radius: 50%; margin-right: 8px;"></div> 
            <span>2025</span>
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <div style="width: 14px; height: 14px; background: #C79B66; border-radius: 50%; margin-right: 8px;"></div> 
            <span>Otro año</span>
        </div>

        <strong style="display: block; margin-bottom: 5px;">Tipo de Proyecto</strong>
        
        <div style="display: flex; align-items: center; margin-bottom: 5px;">
            <svg width="20" height="28" viewBox="0 0 100 100" style="margin-right: 5px;">
                <path d="M50 0 A35 35 0 0 0 15 35 C15 65 50 100 50 100 C50 100 85 65 85 35 A35 35 0 0 0 50 0 Z" fill="#666" stroke="white" stroke-width="3"/>
                <path d="M50 18 L32 34 L38 34 L38 50 L46 50 L46 40 L54 40 L54 50 L62 50 L62 34 L68 34 Z" fill="white" stroke="white" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
            </svg>
            <span>Obra</span>
        </div>

        <div style="display: flex; align-items: center;">
            <svg width="20" height="28" viewBox="0 0 100 100" style="margin-right: 5px;">
                <path d="M50 0 A35 35 0 0 0 15 35 C15 65 50 100 50 100 C50 100 85 65 85 35 A35 35 0 0 0 50 0 Z" fill="#666" stroke="white" stroke-width="3"/>
                <polygon points="50,20 65,43 35,43" fill="white" stroke-linejoin="round"/>
            </svg>
            <span>Mobiliario</span>
        </div>
    `;
    return div;
};

leyenda.addTo(map);
    function crearIconoProyecto(inicio, termino, modulo) {
        function obtenerAnio(fecha) {
            if (!fecha) return null;
            const f = new Date(fecha);
            return isNaN(f) ? null : f.getFullYear();
        }

        const anioInicio = obtenerAnio(inicio);
        const anioFin = obtenerAnio(termino);

        let color = "#C79B66"; // dorado por defecto

        if (anioInicio === 2026 || anioFin === 2026) {
            color = "#366159"; // verde
        } else if (anioInicio === 2025 || anioFin === 2025) {
            color = "#861E34"; // vino
        }

        let figuraInterior = "";

        if (modulo && modulo.toLowerCase().includes('mobiliario')) {
            figuraInterior = `<polygon points="50,20 65,43 35,43" fill="white" stroke-linejoin="round"/>`;
        } else {
            figuraInterior = `<path d="M50 18 L32 34 L38 34 L38 50 L46 50 L46 40 L54 40 L54 50 L62 50 L62 34 L68 34 Z" fill="white" stroke="white" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>`;
        }

        const svgIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
            <path d="M50 0 A35 35 0 0 0 15 35 C15 65 50 100 50 100 C50 100 85 65 85 35 A35 35 0 0 0 50 0 Z" fill="${color}" stroke="white" stroke-width="3"/>
            ${figuraInterior}
        </svg>
        `;

        return L.divIcon({
            className: 'custom-marker',
            iconSize:    [28, 40],   
            iconAnchor:  [14, 40],   
            popupAnchor: [0, -40],
            html: `<div style="width:100%; height:100%; display:flex; justify-content:center; align-items:center;">${svgIcon}</div>`
        });
    }

    // ACTIALIZADO: Ahora acepta anio y modulo
    function cargarMarcadoresProyectos(anio = null, modulo = null) {
        mostrarLoader();
        
        let url = new URL('/mapa/datos-proyectos', window.location.origin);
        if (anio) url.searchParams.append('anio', anio);
        if (modulo) url.searchParams.append('modulo', modulo);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                listaProyectos = [];
                capaProyectos.clearLayers();

                data.forEach(proyecto => {
                    const lat = parseFloat(proyecto.latitud);
                    const lng = parseFloat(proyecto.longitud);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        const marker = L.marker([lat, lng], {
                            icon: crearIconoProyecto(proyecto.inicio, proyecto.termino, proyecto.modulo)
                        }).bindPopup(`
                            <b>Folio PPI:</b> ${proyecto.folio_ppi || 'Sin folio'}<br>
                            <b>CCT:</b>${proyecto.cct}<br>
                            <b>Modulo:</b>${proyecto.modulo}<br>
                            <b>Nombre del proyecto u origen: </b>${proyecto.nombre_proyecto}<br>
                            <b>Monto inversión: $</b>${Number(proyecto.monto_inversion).toLocaleString('es-MX')}<br>
                            <div style="margin-top: 12px; text-align: center;">
                                <a href="/proyectos${proyecto.id}/ver-detalles" target="_blank" 
                                class="btn btn-sm btn-outline-danger w-100" style="font-size: 12px;">
                                    <i class="fas fa-eye"></i> Ver detalles del proyecto
                                </a>
                            </div>
                        `);

                        listaProyectos.push({
                            folio: (proyecto.folio_ppi || '').toLowerCase(),
                            marker: marker,
                            proyecto: proyecto
                        });

                        capaProyectos.addLayer(marker);
                    }
                });
                ocultarLoader();
            })
            .catch(err => {
                console.error('Error al cargar proyectos de inversión:', err);
                ocultarLoader();
            });
    }

    function buscarProyectoPorFolio(folio) {
        fetch('/mapa/datos-proyectos?folio=' + encodeURIComponent(folio))
            .then(res => res.json())
            .then(data => {
                capaProyectos.clearLayers();
                data.forEach(proyecto => {
                    const lat = parseFloat(proyecto.latitud);
                    const lng = parseFloat(proyecto.longitud);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const marker = L.marker([lat, lng], {
                            icon: crearIconoProyecto(proyecto.inicio, proyecto.termino, proyecto.modulo)
                        }).bindPopup(`
                            <b>Folio PPI:</b> ${proyecto.folio_ppi || 'Sin folio'}<br>
                            <b>CCT:</b>${proyecto.cct}<br>
                            <b>Modulo:</b>${proyecto.modulo}<br>
                            <b>Nombre del proyecto u origen: </b>${proyecto.nombre_proyecto}<br>
                            <b>Monto inversión: $</b>${Number(proyecto.monto_inversion).toLocaleString('es-MX')}<br>
                            <div style="margin-top: 12px; text-align: center;">
                                <a href="/proyectos/${proyecto.id}/ver-detalles" target="_blank" 
                                class="btn btn-sm btn-outline-danger w-100" style="font-size: 12px;">
                                    <i class="fas fa-eye"></i> Ver detalles del proyecto
                                </a>
                            </div>
                        `);
                        capaProyectos.addLayer(marker);
                        marker.openPopup();
                    }
                });
            })
            .catch(err => console.error('Error en búsqueda:', err));
    }

    document.getElementById('btnBuscar').addEventListener('click', () => {
        const folio = document.getElementById('folioInput').value.trim();
        if (folio) buscarProyectoPorFolio(folio);
    });

    function cargarMacroregionesPorLotes(data, lote = 3, delay = 100) {
        mostrarLoader();
        const nombres = Object.keys(regiones);
        let index = 0;

        function procesarLote() {
            const fin = Math.min(index + lote, nombres.length);
            for (; index < fin; index++) {
                const nombre = nombres[index];
                const color = coloresMacro[index % coloresMacro.length];
                cargarRegionDesdeData('macro', nombre, color, data);
            }
            if (index < nombres.length) {
                setTimeout(procesarLote, delay);
            } else {
                ocultarLoader();
                const bounds = L.featureGroup(capasActivas).getBounds();
                map.fitBounds(bounds);
            }
        }
        procesarLote();
    }

    function cargarMicroregionesPorLotes(data, lote = 3, delay = 100) {
        mostrarLoader();
        const nombres = Object.keys(microregiones);
        let index = 0;

        function procesarLote() {
            const fin = Math.min(index + lote, nombres.length);
            for (; index < fin; index++) {
                const nombre = nombres[index];
                const color = coloresMacro[index % coloresMacro.length];
                cargarRegionDesdeData('micro', nombre, color, data);
            }
            if (index < nombres.length) {
                setTimeout(procesarLote, delay);
            } else {
                ocultarLoader();
                const bounds = L.featureGroup(capasActivas).getBounds();
                map.fitBounds(bounds);
            }
        }
        procesarLote();
    }

    function mostrarLoader() {
        document.getElementById('loader').style.display = 'block';
    }
    function ocultarLoader() {
        document.getElementById('loader').style.display = 'none';
    }

    window.addEventListener('load', () => {
        function ordenarGrupo(selector) {
            const contenedor = document.querySelector(selector);
            if (!contenedor) return;
            const botones = Array.from(contenedor.querySelectorAll('.filtro-btn'));
            botones.sort((a, b) =>
                a.textContent.localeCompare(b.textContent, 'es', { sensitivity: 'base' })
            );
            botones.forEach(b => contenedor.appendChild(b));
        }
        ordenarGrupo('.grupo-macro');
        ordenarGrupo('.grupo-micro');
    });

    document.getElementById('buscadorRegion').addEventListener('input', function () {
        const valorBusqueda = this.value.toLowerCase();
        const botones = document.querySelectorAll('.filtro-btn');
        botones.forEach(boton => {
            const nombre = boton.getAttribute('data-nombre').toLowerCase();
            if (nombre.includes(valorBusqueda)) {
                boton.style.display = 'inline-block';
            } else {
                boton.style.display = 'none';
            }
        });
    });

    
    const btnFiltros = document.getElementById('btnFiltros');
    const panelFiltros = document.getElementById('panelFiltros');
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');

    if (btnFiltros && panelFiltros) {
        btnFiltros.addEventListener('click', (e) => {
            e.preventDefault();
            if (panelFiltros.style.display === 'none' || panelFiltros.style.display === '') {
                panelFiltros.style.display = 'block';
            } else {
                panelFiltros.style.display = 'none';
            }
        });
    }

    if (btnAplicarFiltros) {
        btnAplicarFiltros.addEventListener('click', (e) => {
            e.preventDefault();
            const anioSeleccionado = document.getElementById('filtroAnio').value;
            const moduloSeleccionado = document.getElementById('filtroModulo').value;
            
            cargarMarcadoresProyectos(anioSeleccionado, moduloSeleccionado);
            panelFiltros.style.display = 'none'; 
        });
    }

    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');

    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', (e) => {
            e.preventDefault();

            document.getElementById('filtroAnio').value = "";
            document.getElementById('filtroModulo').value = "";

            const folioInput = document.getElementById('folioInput');
            if (folioInput) folioInput.value = "";

            cargarMarcadoresProyectos();

            document.getElementById('panelFiltros').style.display = 'none';
        });
    }

});