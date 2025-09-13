
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([19.0414, -98.2063], 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        //  Contorno del estado de Puebla
        fetch('/geojson/puebla.json')
            .then(res => res.json())
            .then(data => {
                const capaPuebla = L.geoJSON(data, {
                    style: {
                        color: '#0a0a0aff',
                        weight: 2,
                        fillOpacity: 0
                    }
                }).addTo(map);
                map.fitBounds(capaPuebla.getBounds());
            })
            .catch(err => console.error('Error al cargar el GeoJSON de Puebla:', err));

        //  Macroregiones definidas por nombre
        const regiones = {
            "Sierra Norte": [
                "Zacatlán", "Huauchinango", "Xicotepec", "Chignahuapan", "Ahuacatlán",
                "Ahuazotepec", "Amixtlán", "Aquixtlan", "Camocuautla", "Chiconcuautla",
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
                "Tenampulco", "Teteles de Avila Castillo", "Teziutlán", "Tlatlauquitepec",
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
                "Huejotzingo", "Juan C. Bonilla", "Puebla", "Puebla", "Puebla", "Puebla", "San Andrés Cholula",
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
        const colores = [
            "#cc0000", "#0066cc", "#009933", "#ff9900", "#6600cc", "#ff3399",
            "#00cccc", "#c48e8b", "#504b06", "#00cc00", "#200703",
            "#ff6600", "#9933cc", "#66ff66", "#3366ff", "#ff0066",
            "#00ffcc", "#cc9966", "#6666ff", "#ccff33", "#ffcc00",
            "#9900ff", "#33cccc", "#ff33cc", "#669900", "#cc3333", "#006633"
        ];


        const microregiones = {
            "Xicotepec": ["Francisco Z. Mena", "Honey", "Jalpan", "Jopala", "Naupan", "Pahuatlán", "Pantepec",
                "Tlacuilotepec", "Tlapacoya", "Tlaxco", "Venustiano Carranza", "Xicotepec", "Zihuateutla"
            ],
            "Huauchinango": ["Ahuazotepec", "Chiconcuautla", "Huauchinango", "Juan Galindo", "Tlaola", "Zacatlán"],
            "Chignahuapan": ["Aquixtla ", "Chignahuapan", "Ixtacamaxtitlán", "Tetela de Ocampo", "Xochiapulco", "Zautla"],
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
            "Acatzingo ": ["Acatzingo", "Mazapiltepec de Juárez", "Nopalucan", "Rafael Lara Grajales", "San José Chiapa", "San Nicolás Buenos Aires", "San Salvador el Seco", "Soltepec"],
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
            "Tahuacan": ["Atexcal", "Caltepec", "Chapulco", "Coyotepec", "Ixcaquixtla", "Juan N. Méndez", "Molcaxac", "Nicolás Bravo", "Santiago Miahuatlán",
                "Tehuacán", "Tepanco de López", "Tlacotepec de Benito Juárez", "Xochitlán Todos Santos", "Zapotitlán"
            ],
            "Ajalpan": ["Ajalpan", "Altepexi", "Coxcatlán", "Coyomeapan", "Eloxochitlán", "San Antonio Cañada", "San Gabriel Chilac", "San José Miahuatlán", "San Sebastián Tlacotepec",
                "Vicente Guerrero", "Zinacatepec", "Zoquitlán"
            ]
        };

        //  Función para graficar municipios por región
        function graficarMacroregion(nombre, municipios, estilo) {
            fetch('/geojson/municipios.json')
                .then(res => res.json())
                .then(data => {
                    const seleccionados = {
                        type: "FeatureCollection",
                        features: data.features.filter(f =>
                            municipios.includes(f.properties.NOMGEO)
                        )
                    };
                    L.geoJSON(seleccionados, {
                        style: estilo
                    }).addTo(map);
                })
                .catch(err => console.error(`Error al cargar ${nombre}:`, err));
        }

        //  Ejecutar graficado por región
        let capasActivas = [];

        function limpiarMapa() {
            capasActivas.forEach(capa => map.removeLayer(capa));
            capasActivas = [];
        }

        function cargarMacroregiones() {
            Object.entries(regiones).forEach(([nombre, municipios], index) => {
                const estilo = {
                    color: colores[index % colores.length],
                    weight: 2,
                    fillOpacity: 0
                };
                fetch('/geojson/municipios.json')
                    .then(res => res.json())
                    .then(data => {
                        const seleccionados = {
                            type: "FeatureCollection",
                            features: data.features.filter(f =>
                                municipios.includes(f.properties.NOMGEO)
                            )
                        };
                        const capa = L.geoJSON(seleccionados, {
                            style: estilo
                        }).addTo(map);
                        capasActivas.push(capa);
                    })
                    .catch(err => console.error(`Error al cargar ${nombre}:`, err));
            });
        }

        function cargarMicroregiones() {
            fetch('/geojson/municipios.json')
                .then(res => res.json())
                .then(data => {
                    Object.entries(microregiones).forEach(([nombre, municipios], index) => {
                        const seleccionados = {
                            type: "FeatureCollection",
                            features: data.features.filter(f =>
                                municipios.includes(f.properties.NOMGEO)
                            )
                        };

                        const estilo = {
                            color: colores[index % colores.length], // Color único por microregión
                            weight: 2,
                            fillOpacity: 0.15
                        };

                        const capa = L.geoJSON(seleccionados, {
                            style: estilo
                        }).addTo(map);
                        capasActivas.push(capa);
                    });
                })
                .catch(err => console.error('Error al cargar microregiones:', err));
        }




        // Marcadores de planteles
        const planteles = @json($planteles);
        const markers = [];

        function normalizarEstado(estado) {
            estado = estado.toLowerCase().trim();
            return estado === 'en_revision' ? 'revision' : estado;
        }

        function crearIconoPorEstado(estado) {
            const clase = normalizarEstado(estado);
            return L.divIcon({
                className: 'custom-marker',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30],
                html: `<i class="bi bi-geo-alt-fill marker-icon ${clase}"></i>`
            });
        }

        planteles.forEach(plantel => {
            const estado = (plantel.estatus_plantel || 'revision').toLowerCase();
            const icono = crearIconoPorEstado(estado);

            const marker = L.marker([plantel.lat, plantel.lng], {
                    icon: icono
                })
                .addTo(map)
                .bindPopup(
                    `<b>${plantel.nombre}</b><br>` +
                    `CCT: ${plantel.cct}<br>` +
                    `Estado: ${estado.charAt(0).toUpperCase() + estado.slice(1)}`
                );

            markers.push({
                cct: plantel.cct.toLowerCase(),
                nombre: plantel.nombre.toLowerCase(),
                marker: marker
            });
        });

        // Buscador de planteles
        const buscador = document.getElementById('buscadorPlantel');
        buscador.addEventListener('input', function() {
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

        buscador.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                buscador.dispatchEvent(new Event('input'));
            }
        });
        // Inicializar con macroregiones
        cargarMacroregiones();

        // Escuchar cambios en el selector
        document.getElementById('tipoMapa').addEventListener('change', function(e) {
            limpiarMapa();
            if (e.target.value === 'macro') {
                cargarMacroregiones();
            } else {
                cargarMicroregiones();
            }
        });

    });
