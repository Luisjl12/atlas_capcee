@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-dark"><i class="fas fa-chart-line"></i> Reportes Financieros Dirección Administrativa</h2>

    @if($reportes->isEmpty())
        <div class="alert alert-info text-center shadow-sm">
            <i class="fas fa-info-circle fa-2x mb-2"></i>
            <h5>Aún no hay reportes publicados.</h5>
            <p class="mb-0">Cuando el Administrador suba un nuevo reporte, aparecerá aquí.</p>
        </div>
    @else
        <div class="row">
            @foreach($reportes as $reporte)
                @php 
                    $grafica = json_decode($reporte->datos_grafica); 
                    // Contamos los items para calcular la altura perfecta de la gráfica de barras
                    $numItems = isset($grafica->labels) ? count($grafica->labels) : 0;
                    $altoBarras = max(400, $numItems * 40); // 40px de altura por cada gasto
                @endphp

                <div class="col-12 mb-5">
                    <div class="card shadow-sm h-100 border-0 rounded-3">
                        <div class="card-header bg-white border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                            <h5 class="mb-3 mb-md-0 fw-bold text-primary">
                                <i class="fas fa-file-invoice-dollar me-2"></i>{{ $reporte->titulo }}
                            </h5>
                            <button id="btnExportar_{{ $reporte->id }}" class="btn btn-danger w-100 w-md-auto shadow-sm">
                                <i class="fas fa-file-pdf me-1"></i> Exportar Reporte HD
                            </button>
                        </div>

                        <div class="card-body bg-light">
                            
                            <ul class="nav nav-pills nav-justified mb-4 flex-column flex-md-row gap-2" id="tabs_{{ $reporte->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold border" data-bs-toggle="tab" data-bs-target="#barras_{{ $reporte->id }}" type="button" role="tab">
                                        <i class="fas fa-align-left me-1"></i> Comparativa
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold border" data-bs-toggle="tab" data-bs-target="#pastel_{{ $reporte->id }}" type="button" role="tab">
                                        <i class="fas fa-chart-pie me-1"></i> Distribución
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold border" data-bs-toggle="tab" data-bs-target="#tabla_{{ $reporte->id }}" type="button" role="tab">
                                        <i class="fas fa-table me-1"></i> Detalles
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="contenidoTabs_{{ $reporte->id }}">
                                
                                <div class="tab-pane fade show active" id="barras_{{ $reporte->id }}" role="tabpanel">
                                    <div class="alert alert-secondary py-1 px-2 mb-2 text-center" style="font-size: 0.85rem;">
                                        <i class="fas fa-info-circle"></i> Desliza hacia abajo para ver todos los conceptos.
                                    </div>
                                    <div class="bg-white border rounded p-2 shadow-sm" style="height: 550px; overflow-y: auto; overflow-x: hidden;" id="contenedor_barras_{{ $reporte->id }}">
                                        <div style="position: relative; height: {{ $altoBarras }}px; width: 100%;">
                                            <canvas id="grafica_barras_{{ $reporte->id }}"
                                                data-labels="{{ json_encode($grafica->labels ?? []) }}"
                                                data-aprobado="{{ json_encode($grafica->aprobado ?? []) }}"
                                                data-vigente="{{ json_encode($grafica->vigente ?? []) }}"
                                                data-pagado="{{ json_encode($grafica->pagado ?? []) }}"
                                                data-diferencia="{{ json_encode($grafica->diferencia ?? []) }}">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pastel_{{ $reporte->id }}" role="tabpanel">
                                    <div class="d-flex justify-content-center align-items-center bg-white border rounded p-3 shadow-sm" style="min-height: 400px;">
                                        <div style="position: relative; width: 100%; max-width: 450px; aspect-ratio: 1/1;" class="mx-auto">
                                            <canvas id="grafica_pastel_{{ $reporte->id }}"
                                                data-labels="{{ json_encode($grafica->labels ?? []) }}"
                                                data-pagado="{{ json_encode($grafica->pagado ?? []) }}">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tabla_{{ $reporte->id }}" role="tabpanel">
                                    <div class="input-group mb-3 shadow-sm">
                                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" id="buscadorTabla_{{ $reporte->id }}" class="form-control border-start-0" placeholder="Buscar concepto o cantidad...">
                                    </div>
                                   
                                    <div class="table-responsive d-none d-md-block bg-white border rounded shadow-sm">
                                        <table class="table table-bordered table-striped table-hover text-center align-middle mb-0" id="tablaDetalle_{{ $reporte->id }}">
                                            <thead style="background-color: #691C32; color: white;">
                                                <tr>
                                                    <th class="py-3">Objeto del Gasto</th>
                                                    <th class="py-3">Aprobado</th>
                                                    <th class="py-3">P. Vigente</th>
                                                    <th class="py-3">Pagado</th>
                                                    <th class="py-3">Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($grafica->labels))
                                                    @foreach($grafica->labels as $index => $label)
                                                    <tr>
                                                        <td class="text-start fw-bold">{{ $label }}</td>
                                                        <td>${{ number_format((float)($grafica->aprobado[$index] ?? 0), 2) }}</td>
                                                        <td>${{ number_format((float)($grafica->vigente[$index] ?? 0), 2) }}</td>
                                                        <td class="text-success fw-bold">${{ number_format((float)($grafica->pagado[$index] ?? 0), 2) }}</td>
                                                        <td class="text-primary fw-bold">${{ number_format((float)($grafica->diferencia[$index] ?? 0), 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot style="background-color: #f8f9fa; font-size: 1.1rem; border-top: 2px solid #ccc;">
                                                <tr>
                                                    <td class="text-end fw-bold">TOTALES:</td>
                                                    <td class="fw-bold">${{ number_format(array_sum((array)($grafica->aprobado ?? [])), 2) }}</td>
                                                    <td class="fw-bold">${{ number_format(array_sum((array)($grafica->vigente ?? [])), 2) }}</td>
                                                    <td class="text-success fw-bold">${{ number_format(array_sum((array)($grafica->pagado ?? [])), 2) }}</td>
                                                    <td class="text-primary fw-bold">${{ number_format(array_sum((array)($grafica->diferencia ?? [])), 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="d-md-none">
                                        @if(isset($grafica->labels))
                                            @foreach($grafica->labels as $index => $label)
                                                <div class="card mb-3 shadow-sm fila-movil border-0">
                                                    <div class="card-header bg-white border-bottom border-secondary text-primary">
                                                        <h6 class="mb-0 fw-bold titulo-movil">{{ $label }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row text-center mb-2">
                                                            <div class="col-6 border-end">
                                                                <small class="text-muted d-block">Aprobado</small>
                                                                <span class="fw-bold">${{ number_format((float)($grafica->aprobado[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">Vigente</small>
                                                                <span class="fw-bold">${{ number_format((float)($grafica->vigente[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row text-center bg-light rounded py-2">
                                                            <div class="col-6 border-end">
                                                                <small class="text-muted d-block">Pagado</small>
                                                                <span class="fw-bold text-success">${{ number_format((float)($grafica->pagado[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">Saldo</small>
                                                                <span class="fw-bold text-primary">${{ number_format((float)($grafica->diferencia[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="card border-primary bg-primary text-white mt-4 shadow">
                                                <div class="card-body text-center">
                                                    <h5 class="mb-3 fw-bold"><i class="fas fa-wallet me-2"></i> TOTAL GENERAL</h5>
                                                    <div class="row mt-2">
                                                        <div class="col-6 border-end border-white-50">
                                                            <small class="d-block text-white-50">Aprobado</small>
                                                            <span class="fw-bold">${{ number_format(array_sum((array)($grafica->aprobado ?? [])), 2) }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="d-block text-white-50">Vigente</small>
                                                            <span class="fw-bold">${{ number_format(array_sum((array)($grafica->vigente ?? [])), 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <hr class="bg-white">
                                                    <div class="row mt-2">
                                                        <div class="col-6 border-end border-white-50">
                                                            <small class="d-block text-white-50">Pagado</small>
                                                            <span class="fw-bold text-warning">${{ number_format(array_sum((array)($grafica->pagado ?? [])), 2) }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="d-block text-white-50">Saldo</small>
                                                            <span class="fw-bold text-info">${{ number_format(array_sum((array)($grafica->diferencia ?? [])), 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Función optimizada para rebanar imágenes largas en el PDF
    async function addImageToPdfWithPageBreaks(pdf, imgData, x, y, width, margin = 10) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = function() {
                const pageHeight = (pdf.internal.pageSize && typeof pdf.internal.pageSize.getHeight === 'function')
                    ? pdf.internal.pageSize.getHeight() : pdf.internal.pageSize.height;
                const ratio = width / img.width;
                const maxPageHeightFirst = Math.floor((pageHeight - y - margin) / ratio);
                const maxPageHeightOthers = Math.floor((pageHeight - (margin * 2)) / ratio);
                
                let srcY = 0;
                let firstPage = true;

                while (srcY < img.height) {
                    const currentMax = firstPage ? maxPageHeightFirst : maxPageHeightOthers;
                    const sliceHeight = Math.min(currentMax, img.height - srcY);
                    
                    const canvasSlice = document.createElement('canvas');
                    canvasSlice.width = img.width;
                    canvasSlice.height = sliceHeight;
                    const ctx = canvasSlice.getContext('2d');
                    
                    ctx.fillStyle = "#FFFFFF"; // Fondo blanco obligatorio
                    ctx.fillRect(0, 0, canvasSlice.width, canvasSlice.height);
                    ctx.drawImage(img, 0, srcY, img.width, sliceHeight, 0, 0, img.width, sliceHeight);
                    
                    const sliceData = canvasSlice.toDataURL('image/jpeg', 0.9);
                    const slicePdfHeight = sliceHeight * ratio;
                    
                    if (!firstPage) pdf.addPage();
                    pdf.addImage(sliceData, 'JPEG', x, firstPage ? y : margin, width, slicePdfHeight);
                    
                    firstPage = false;
                    srcY += sliceHeight;
                }
                resolve();
            };
            img.onerror = reject;
            img.src = imgData;
        });
    }

    const paletaColores = [
        'rgba(13, 110, 253, 0.8)', 'rgba(25, 135, 84, 0.8)', 'rgba(255, 193, 7, 0.8)', 'rgba(108, 117, 125, 0.8)',
        'rgba(220, 53, 69, 0.8)', 'rgba(23, 162, 184, 0.8)', 'rgba(255, 152, 0, 0.8)', 'rgba(111, 66, 193, 0.8)',
        'rgba(32, 201, 151, 0.8)', 'rgba(159, 34, 65, 0.8)'
    ];

    // PLUGIN PARA FONDO BLANCO NATÍVO EN CHART.JS
    const fondoBlancoPlugin = {
        id: 'fondoBlanco',
        beforeDraw: (chart) => {
            const ctx = chart.canvas.getContext('2d');
            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, chart.width, chart.height);
            ctx.restore();
        }
    };

    @foreach($reportes as $reporte)
    (function() {

        // ── 1. GRÁFICA DE BARRAS RESPONSIVA ────────────────────────────
        const ctxBarras = document.getElementById('grafica_barras_{{ $reporte->id }}');
        if (ctxBarras) {
            const labels     = JSON.parse(ctxBarras.dataset.labels);
            const aprobado   = JSON.parse(ctxBarras.dataset.aprobado);
            const vigente    = JSON.parse(ctxBarras.dataset.vigente);
            const pagado     = JSON.parse(ctxBarras.dataset.pagado);
            const diferencia = JSON.parse(ctxBarras.dataset.diferencia);
            const isMobile = window.innerWidth < 768;
            const contenedor = document.getElementById('contenedor_barras_{{ $reporte->id }}');
            if (isMobile) {
                contenedor.style.height = '400px'; // Altura fija para móvil
            }
            window["chartBarras{{ $reporte->id }}"] = new Chart(ctxBarras.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Aprobado', data: aprobado,   backgroundColor: 'rgba(108, 117, 125, 0.7)', borderRadius: 4 },
                        { label: 'Vigente',  data: vigente,    backgroundColor: 'rgba(54, 162, 235, 0.7)',  borderRadius: 4 },
                        { label: 'Pagado',   data: pagado,     backgroundColor: 'rgba(25, 135, 84, 0.7)',   borderRadius: 4 },
                        { label: 'Saldo',    data: diferencia, backgroundColor: 'rgba(13, 110, 253, 0.8)',  borderRadius: 4 }
                    ]
                },
                options: {
                    indexAxis: isMobile ? 'x' : 'y', // Barras verticales en móvil, horizontales en desktop
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                font: { size: isMobile ? 8 : 10 },
                                callback: function(value) {
                                    if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'k';
                                    return '$' + value;
                                }
                            }
                        },
                        y: { 
                            ticks: { font: { size: isMobile ? 8 : 10 }, autoSkip: false },
                            grid: { display: false } 
                        }
                    },
                    plugins: {
                        legend: { position: isMobile ? 'bottom' : 'top', labels: { font: { size: isMobile ? 10 : 12 } } },
                        tooltip: { callbacks: { label: function(c) { return c.dataset.label + ': ' + new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(isMobile ? c.parsed.y : c.parsed.x); } } }
                    }
                },
                plugins: [fondoBlancoPlugin]
            });
        }

        // ── 2. GRÁFICA DE PASTEL ────────────────────────────
        const ctxPastel = document.getElementById('grafica_pastel_{{ $reporte->id }}');
        if (ctxPastel) {
            const labelsPastel = JSON.parse(ctxPastel.dataset.labels);
            const pagadoPastel = JSON.parse(ctxPastel.dataset.pagado);
            const isMobile = window.innerWidth < 768;
            window["chartPastel{{ $reporte->id }}"] = new Chart(ctxPastel.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labelsPastel,
                    datasets: [{ 
                        label: 'Total Pagado ($)', 
                        data: pagadoPastel, 
                        backgroundColor: paletaColores, 
                        borderWidth: 1 
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: isMobile ? 'bottom' : 'right', 
                            labels: { 
                                boxWidth: isMobile ? 10 : 12, 
                                font: { size: isMobile ? 9 : 10 },
                                padding: isMobile ? 10 : 15
                            } 
                        }, 
                        tooltip: { 
                            callbacks: { 
                                label: function(c) { 
                                    return ' Pagado: ' + new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(c.parsed); 
                                } 
                            } 
                        } 
                    }
                },
                plugins: [fondoBlancoPlugin]
            });
        }

        // Listener para redimensionar gráficas al cambiar tamaño de ventana
        window.addEventListener('resize', function() {
            if(window["chartBarras{{ $reporte->id }}"]) window["chartBarras{{ $reporte->id }}"].resize();
            if(window["chartPastel{{ $reporte->id }}"]) window["chartPastel{{ $reporte->id }}"].resize();
        });

        // ── 3. EXPORTACIÓN PDF (SIN CLICS NI RETRASOS) ────────────────────────────
        const btnExportar = document.getElementById("btnExportar_{{ $reporte->id }}");
        if (!btnExportar) return;

        btnExportar.addEventListener("click", async function() {
            const textoOriginal = btnExportar.innerHTML;
            btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF HD...';
            btnExportar.disabled = true;

            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF("p", "mm", "a4");
                const pageWidth = pdf.internal.pageSize.getWidth();
                
                // Redimensionar las gráficas para que tomen su tamaño real
                if(window["chartBarras{{ $reporte->id }}"]) window["chartBarras{{ $reporte->id }}"].resize();
                if(window["chartPastel{{ $reporte->id }}"]) window["chartPastel{{ $reporte->id }}"].resize();

                // --- PORTADA DEL PDF ---
                pdf.setFont(undefined, 'bold');
                pdf.setFontSize(18);
                pdf.setTextColor(105, 28, 50); 
                pdf.text("Reporte Financiero Infraescolar", 10, 20);
                pdf.setFontSize(12);
                pdf.setTextColor(80, 80, 80);
                pdf.text("Documento: {!! addslashes($reporte->titulo) !!}", 10, 28);
                pdf.setDrawColor(105, 28, 50);
                pdf.setLineWidth(0.5);
                pdf.line(10, 32, pageWidth - 10, 32);

                let currentY = 40;

                // --- CAPTURAR BARRAS ---
                if (window["chartBarras{{ $reporte->id }}"]) {
                    const originalPixelRatio = window.devicePixelRatio;
                    window.devicePixelRatio = 4; // Modo HD
                    window["chartBarras{{ $reporte->id }}"].render();
                    
                    const imgBarras = window["chartBarras{{ $reporte->id }}"].toBase64Image('image/jpeg', 0.9);
                    window.devicePixelRatio = originalPixelRatio;
                    
                    pdf.setFont(undefined, 'bold');
                    pdf.setFontSize(14);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text("Comparativa de Conceptos", 10, currentY);
                    currentY += 5;
                    await addImageToPdfWithPageBreaks(pdf, imgBarras, 10, currentY, pageWidth - 20, 10);
                    pdf.addPage();
                    currentY = 20; 
                }

                // --- CAPTURAR PASTEL ---
                // --- CAPTURAR PASTEL ---
                if (window["chartPastel{{ $reporte->id }}"]) {

                    // 1. Hacer visible el canvas temporalmente para que Chart.js pueda renderizar
                    const tabPastel = document.getElementById('pastel_{{ $reporte->id }}');
                    const estabaOculto = !tabPastel.classList.contains('show');
                    if (estabaOculto) {
                        tabPastel.style.display = 'block';
                        tabPastel.style.visibility = 'hidden'; // visible para el DOM pero invisible para el usuario
                    }

                    // 2. Forzar resize y re-render con alta resolución
                    const originalPixelRatio = window.devicePixelRatio;
                    window.devicePixelRatio = 4;
                    window["chartPastel{{ $reporte->id }}"].resize();
                    window["chartPastel{{ $reporte->id }}"].render();

                    // 3. Pequeña pausa para que el canvas termine de pintar
                    await new Promise(resolve => setTimeout(resolve, 200));

                    const imgPastel = window["chartPastel{{ $reporte->id }}"].toBase64Image('image/jpeg', 0.9);
                    window.devicePixelRatio = originalPixelRatio;

                    // 4. Restaurar el estado original del tab
                    if (estabaOculto) {
                        tabPastel.style.display = '';
                        tabPastel.style.visibility = '';
                    }

                    pdf.setFont(undefined, 'bold');
                    pdf.setFontSize(14);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text("Distribución del Presupuesto", 10, currentY);
                    const imgSize = 130;
                    pdf.addImage(imgPastel, "JPEG", (pageWidth - imgSize) / 2, currentY + 10, imgSize, imgSize);
                    pdf.addPage();
                    currentY = 20;
                }

                // --- TABLA AUTOTABLE ---
                pdf.setFont(undefined, 'bold');
                pdf.setFontSize(14);
                pdf.setTextColor(0, 0, 0);
                pdf.text("Desglose Detallado", 10, currentY);

                pdf.autoTable({
                    html: `#tablaDetalle_{{ $reporte->id }}`,
                    startY: currentY + 5,
                    theme: 'striped',
                    headStyles: { fillColor: [105, 28, 50], textColor: 255 }, 
                    footStyles: { fillColor: [240, 240, 240], textColor: 0, fontStyle: 'bold' }, 
                    styles: { fontSize: 8, cellPadding: 3 },
                    margin: { left: 10, right: 10, bottom: 15 },
                    showFoot: 'lastPage' 
                });

                // Descargar
                const nombreLimpio = "{!! addslashes($reporte->titulo) !!}".replace(/[^a-z0-9]/gi, '_');
                pdf.save(`Reporte_Financiero_${nombreLimpio}_HD.pdf`);

            } catch (error) {
                console.error("Error al generar PDF: ", error);
                alert("Ocurrió un error al generar el PDF.");
            } finally {
                btnExportar.innerHTML = textoOriginal;
                btnExportar.disabled = false;
            }
        });

    })();
    @endforeach

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    @foreach($reportes as $reporte)
        const input{{ $reporte->id }} = document.getElementById("buscadorTabla_{{ $reporte->id }}");
        const tabla{{ $reporte->id }} = document.getElementById("tablaDetalle_{{ $reporte->id }}");
        
        if(input{{ $reporte->id }} && tabla{{ $reporte->id }}) {
            input{{ $reporte->id }}.addEventListener("keyup", function() {
                let filtro = this.value.toLowerCase();
                let filas = tabla{{ $reporte->id }}.querySelectorAll("tbody tr");
                filas.forEach(fila => {
                    fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? "" : "none";
                });

                let contenedorTarjetas = document.querySelector('#tabla_{{ $reporte->id }} .d-md-none');
                if(contenedorTarjetas) {
                    let tarjetas = contenedorTarjetas.querySelectorAll('.fila-movil');
                    tarjetas.forEach(tarjeta => {
                        tarjeta.style.display = tarjeta.textContent.toLowerCase().includes(filtro) ? "" : "none";
                    });
                }
            });
        }
    @endforeach
});
</script>

@endsection