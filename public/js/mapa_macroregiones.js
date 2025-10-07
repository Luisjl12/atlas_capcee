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
                    color: '#7a0019',
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
            fillColor: '#f9f9f9',
            fillOpacity: 0.3
        };

        const capa = L.geoJSON(geometriaUnida, { style: estilo }).addTo(map);
        capasActivas.push(capa);
    }

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

    planteles.forEach(plantel => {
        const marker = L.marker([plantel.lat, plantel.lng], {
            icon: crearIconoPorEstado(plantel.estatus_plantel)
        }).addTo(map).bindPopup(`
            <b>${plantel.nombre}</b><br>
            CCT: ${plantel.cct}<br>
            Estado: ${normalizarEstado(plantel.estatus_plantel).charAt(0).toUpperCase() + normalizarEstado(plantel.estatus_plantel).slice(1)}<br>
            Municipio: ${plantel.municipio?.nombre_municipio || 'Sin dato'}<br>
            Localidad: ${plantel.localidad?.nombre_localidad || 'Sin dato'}<br>
            <a href="/planteles/${plantel.id}" target="_blank">Ver ficha completa</a>
        `);

        markers.push({
            cct: (plantel.cct || '').toLowerCase(),
            nombre: (plantel.nombre || '').toLowerCase(),
            marker
        });
    });


    const buscador = document.getElementById('buscadorPlantel');
    if (buscador) {
        buscador.addEventListener('input', function () {
            const texto = buscador.value.toLowerCase().trim();
            if (texto.length < 3) return;

            const resultado = markers.find(p =>
                p.cct.includes(texto) || p.nombre.includes(texto)
            );

            if (resultado) {
                map.setView(resultado.marker.getLatLng(), 15);
                resultado.marker.openPopup();
            }
        });

        buscador.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') buscador.dispatchEvent(new Event('input'));
        });
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
});
