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
    let todosLosProyectos = []; 

    cargarMarcadoresProyectos();

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

    let diseñoInterior = "";

    // Lógica para elegir el ícono basado en el módulo
    if (modulo && modulo.toLowerCase().includes('mobiliario')) {
        diseñoInterior = `
            <g stroke="${color}" fill="none" stroke-linejoin="round" stroke-linecap="round">
                <!-- Pizarrón -->
                <rect x="35" y="24" width="50" height="26" stroke-width="2"/>
                <rect x="37" y="26" width="46" height="22" stroke-width="1.5"/>
                <rect x="46" y="44" width="8" height="4" stroke-width="1.5"/>

                <!-- Bases Pizarrón -->
                <rect x="32" y="52" width="56" height="3" stroke-width="2"/>
                <line x1="34" y1="58" x2="86" y2="58" stroke-width="2"/>

                <!-- Mesa -->
                <rect x="30" y="64" width="60" height="4" fill="white" stroke-width="2"/>
                <line x1="33" y1="68" x2="33" y2="86" stroke-width="2"/>
                <line x1="38" y1="68" x2="36" y2="86" stroke-width="2"/>
                <line x1="87" y1="68" x2="87" y2="86" stroke-width="2"/>
                <line x1="82" y1="68" x2="84" y2="86" stroke-width="2"/>

                <!-- Sillas -->
                <g fill="white" stroke-width="2">
                    <!-- Silla Izquierda -->
                    <line x1="42" y1="80" x2="42" y2="88"/>
                    <line x1="48" y1="80" x2="48" y2="88"/>
                    <path d="M38 80 L52 80 L50 64 L40 64 Z" stroke-linejoin="round"/>
                    <line x1="43" y1="68" x2="47" y2="68" stroke-width="2" stroke-linecap="round"/>
                    
                    <!-- Silla Derecha -->
                    <line x1="72" y1="80" x2="72" y2="88"/>
                    <line x1="78" y1="80" x2="78" y2="88"/>
                    <path d="M68 80 L82 80 L80 64 L70 64 Z" stroke-linejoin="round"/>
                    <line x1="73" y1="68" x2="77" y2="68" stroke-width="2" stroke-linecap="round"/>
                </g>
            </g>
        `;
    } else {
        diseñoInterior = `
            <defs>
                <g id="win">
                    <rect x="0" y="0" width="2.5" height="3.5" fill="white"/>
                    <rect x="3.5" y="0" width="2.5" height="3.5" fill="white"/>
                    <rect x="0" y="4.5" width="2.5" height="3.5" fill="white"/>
                    <rect x="3.5" y="4.5" width="2.5" height="3.5" fill="white"/>
                </g>
            </defs>
            <rect x="20" y="78" width="80" height="3" rx="1" fill="${color}"/>
            <rect x="24" y="54" width="22" height="24" fill="${color}"/>
            <polygon points="20,54 46,54 46,42 28,42" fill="${color}"/>
            <rect x="74" y="54" width="22" height="24" fill="${color}"/>
            <polygon points="74,54 100,54 92,42 74,42" fill="${color}"/>
            <rect x="46" y="32" width="28" height="46" fill="${color}"/>
            <polygon points="42,32 78,32 60,18" fill="${color}"/>
            <line x1="60" y1="18" x2="60" y2="8" stroke="${color}" stroke-width="2"/>
            <path d="M60 8 Q64 6 66 9 T72 8 L72 13 Q70 14 66 11 T60 14 Z" fill="${color}"/>
            <circle cx="60" cy="42" r="7" fill="white"/>
            <circle cx="60" cy="37.5" r="0.8" fill="${color}"/>
            <circle cx="64.5" cy="42" r="0.8" fill="${color}"/>
            <circle cx="60" cy="46.5" r="0.8" fill="${color}"/>
            <circle cx="55.5" cy="42" r="0.8" fill="${color}"/>
            <path d="M60 42 L60 38.5 M60 42 L63 42" stroke="${color}" stroke-width="1.5" stroke-linecap="round"/>
            <use href="#win" x="28" y="58"/>
            <use href="#win" x="28" y="68"/>
            <use href="#win" x="37" y="58"/>
            <use href="#win" x="37" y="68"/>
            <use href="#win" x="77" y="58"/>
            <use href="#win" x="77" y="68"/>
            <use href="#win" x="86" y="58"/>
            <use href="#win" x="86" y="68"/>
            <use href="#win" x="51" y="52"/>
            <use href="#win" x="63" y="52"/>
            <rect x="51" y="64" width="7" height="14" fill="white"/>
            <rect x="62" y="64" width="7" height="14" fill="white"/>
        `;
    }

    // Se ajustó el viewBox para recortar el espacio vacío que dejó el pin
    const svgIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="10 0 100 95" width="100%" height="100%">
        ${diseñoInterior}
    </svg>
    `;

    return L.divIcon({
        className: 'custom-marker',
        
        iconSize:    [40, 40],   
        
        iconAnchor:  [20, 40],   
        
        popupAnchor: [0, -40],
        
        html: `<div style="width:100%; height:100%;">${svgIcon}</div>`
    });
}
    function cargarMarcadoresProyectos(anio = null) {
        mostrarLoader();
        let url = '/mapa/datos-proyectos';
        if (anio) {
            url += '?anio=' + anio;
        }

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
                            <b>Monto inversión: $</b>${proyecto.monto_inversion}<br>
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
                            icon: crearIconoProyecto(proyecto.inicio, proyecto.termino)
                        }).bindPopup(`
                            <b>Folio PPI:</b> ${proyecto.folio_ppi || 'Sin folio'}<br>
                            <b>${proyecto.nombre_proyecto}</b><br>
                            <b>Monto inversion: $</b>${proyecto.monto_inversion}<br>
                            <div style="margin-top: 12px; text-align: center;">
                                <a href="/proyectos${proyecto.id}/ver-detalles" target="_blank" class="btn btn-sm btn-outline-danger w-100" style="font-size: 12px;">
                                    <i class="fas fa-eye"></i> Ver detalles del proyecto
                                </a>
                            </div>
                            `
                        );
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

    const planteles = window.planteles || [];
    const markers = [];

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

    const filtroFecha = document.getElementById('filtroFecha');
    if (filtroFecha) {
        filtroFecha.addEventListener('change', function () {
            const anioSeleccionado = filtroFecha.value;
            cargarMarcadoresProyectos(anioSeleccionado);
        });
    }

    const btnFiltroFecha = document.getElementById('btnFiltroFecha');
    const opcionesFiltro = document.getElementById('opcionesFiltro');

    btnFiltroFecha.addEventListener('click', () => {
    opcionesFiltro.style.display = 
        opcionesFiltro.style.display === 'block' ? 'none' : 'block';
    });

    opcionesFiltro.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
        const anioSeleccionado = btn.getAttribute('data-anio');
        cargarMarcadoresProyectos(anioSeleccionado);
        opcionesFiltro.style.display = 'none'; 
    });
    });
});
