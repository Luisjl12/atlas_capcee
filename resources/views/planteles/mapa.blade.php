@extends('layouts.app')

@section('title', 'Mapa de Planteles')

@section('content')
<div class="mb-3">
    <input type="text" id="buscadorPlantel" class="form-control" placeholder="Buscar por CCT o nombre...">
</div>

<div class="container mt-4">
    <h3 class="mb-3">Mapa de Planteles</h3>
    <div id="map" style="height: 500px; border-radius: 8px;"></div>
</div>

{{-- Estilos de Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

{{-- Script de Leaflet --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

{{-- Estilos para íconos CSS --}}
<style>
    .custom-marker .marker-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .marker-icon.activo {
        background-color: #4CAF50;
    }

    .marker-icon.inactivo {
        background-color: #F44336;
    }

    .marker-icon.revision {
        background-color: #FFC107;
    }
</style>

<!--Script para graficar los mapas-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([19.0414, -98.2063], 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // 🟦 Contorno del estado de Puebla
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
                "Acatzingo",
                "Aljojuca",
                "Atoyatempan",
                "Atzitzintla",
                "Cañada Morelos",
                "Chalchicomula de Sesma",
                "Chichiquila",
                "Chilchotla",
                "Cuapiaxtla de Madero",
                "Esperanza",
                "General Felipe Ángeles",
                "Guadalupe Victoria",
                "Huitziltepec",
                "Lafragua",
                "Libres",
                "Los Reyes de Juárez",
                "Mazapiltepec de Juárez",
                "Mixtla",
                "Nopalucan",
                "Ocotepec",
                "Oriental",
                "Palmar de Bravo",
                "Quecholac",
                "Quimixtlán",
                "Rafael Lara Grajales",
                "San José Chiapa",
                "San Juan Atenco",
                "San Nicolás Buenos Aires",
                "San Salvador el Seco",
                "San Salvador Huixcolotla",
                "Santo Tomás Hueyotlipan",
                "Soltepec",
                "Tecamachalco",
                "Tepeaca",
                "Tepeyahualco",
                "Tepeyahualco de Cuauhtémoc",
                "Tlachichuca",
                "Tlanepantla",
                "Tochtepec",
                "Yehualtepec"
            ],
            "Angelópolis": [
                "Acajete",
                "Amozoc",
                "Calpan",
                "Chiautzingo",
                "Coronango",
                "Cuautinchán",
                "Cuautlancingo",
                "Domingo Arenas",
                "Huejotzingo",
                "Juan C. Bonilla",
                "Puebla",
                "Puebla",
                "Puebla",
                "Puebla",
                "San Andrés Cholula",
                "San Felipe Teotlalcingo",
                "San Martín Texmelucan",
                "San Matías Tlalancaleca",
                "San Miguel Xoxtla",
                "San Pedro Cholula",
                "San Salvador el Verde",
                "Tecali de Herrera",
                "Tepatlaxco de Hidalgo",
                "Tlahuapan",
                "Tlaltenango"
            ],
            "Valle de Atlixco y Matamoros": [
                "Acteopan",
                "Atlixco",
                "Atzala",
                "Atzitzihuacán",
                "Chietla",
                "Cohuecán",
                "Epatlán",
                "Huaquechula",
                "Izúcar de Matamoros",
                "Nealtican",
                "Ocoyucan",
                "San Gregorio Atzompa",
                "San Jerónimo Tecuanipan",
                "San Nicolás de los Ranchos",
                "Santa Isabel Cholula",
                "Tepemaxalco",
                "Tepeojuma",
                "Tepexco",
                "Tianguismanalco",
                "Tilapa",
                "Tlapanalá",
                "Tochimilco"
            ],
            "Mixteca": [
                "Acatlán",
                "Ahuatlán",
                "Ahuehuetitla",
                "Albino Zertuche",
                "Axutla",
                "Chiautla",
                "Chigmecatitlán",
                "Chila",
                "Chila de la Sal",
                "Chinantla",
                "Coatzingo",
                "Cohetzala",
                "Cuayuca de Andrade",
                "Guadalupe",
                "Huatlatlauca",
                "Huehuetlán el Chico",
                "Huehuetlán el Grande",
                "Ixcamilpa de Guerrero",
                "Jolalpan",
                "La Magdalena Tlatlauquitepec",
                "Petlalcingo",
                "Piaxtla",
                "San Diego la Mesa Tochimiltzingo",
                "San Jerónimo Xayacatlán",
                "San Juan Atzompa",
                "San Martín Totoltepec",
                "San Miguel Ixitlán",
                "San Pablo Anicano",
                "San Pedro Yeloixtlahuaca",
                "Santa Catarina Tlaltempan",
                "Santa Inés Ahuatempan",
                "Tecomatlán",
                "Tehuitzingo",
                "Teopantlán",
                "Teotlalco",
                "Tepexi de Rodríguez",
                "Totoltepec de Guerrero",
                "Tulcingo",
                "Tzicatlacoyan",
                "Xayacatlán de Bravo",
                "Xicotlán",
                "Xochiltepec",
                "Zacapala"
            ],
            "Tehuacán y Sierra Negra": [
                "Ajalpan",
                "Altepexi",
                "Atexcal",
                "Caltepec",
                "Chapulco",
                "Coxcatlán",
                "Coyomeapan",
                "Coyotepec",
                "Eloxochitlán",
                "Ixcaquixtla",
                "Juan N. Méndez",
                "Molcaxac",
                "Nicolás Bravo",
                "San Antonio Cañada",
                "San Gabriel Chilac",
                "San José Miahuatlán",
                "San Sebastián Tlacotepec",
                "Santiago Miahuatlán",
                "Tehuacán",
                "Tepanco de López",
                "Tlacotepec de Benito Juárez",
                "Vicente Guerrero",
                "Xochitlán Todos Santos",
                "Zapotitlán",
                "Zinacatepec",
                "Zoquitlán"
            ]

        };

        // Colores por región
        const colores = ['#cc0000', '#0066cc', '#009933', '#ff9900', '#6600cc', '#ff3399', '#00cccc'];

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
        Object.entries(regiones).forEach(([nombre, municipios], index) => {
            const estilo = {
                color: colores[index % colores.length],
                weight: 2,
                fillOpacity: 0
            };
            graficarMacroregion(nombre, municipios, estilo);
        });

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
    });
</script>


@endsection