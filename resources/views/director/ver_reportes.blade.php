@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-dark"><i class="fas fa-chart-line"></i> Reportes Financieros Dirección administrativa</h2>

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
                @endphp

                <div class="col-md-12 mb-5">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><strong>{{ $reporte->titulo }}</strong></h5>
                            <button id="btnExportar_{{ $reporte->id }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </button>
                        </div>
                        <div class="card-body">
                            
                            <ul class="nav nav-tabs" id="tabs_{{ $reporte->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#barras_{{ $reporte->id }}" type="button" role="tab">
                                        <i class="fas fa-chart-bar"></i> Comparativa
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pastel_{{ $reporte->id }}" type="button" role="tab">
                                        <i class="fas fa-chart-pie"></i> Gastos Pagados
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabla_{{ $reporte->id }}" type="button" role="tab">
                                        <i class="fas fa-table"></i> Tabla Detallada
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-4" id="contenidoTabs_{{ $reporte->id }}">
                                
                                <div class="tab-pane fade show active" id="barras_{{ $reporte->id }}" role="tabpanel">
                                    <div class="d-flex justify-content-end mb-2">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Zoom gráfico de barras">
                                            <button type="button" id="zoomOut_{{ $reporte->id }}" class="btn btn-outline-primary" title="Reducir zoom">
                                                <i class="fas fa-search-minus"></i>
                                            </button>
                                            <button type="button" id="zoomIn_{{ $reporte->id }}" class="btn btn-outline-primary" title="Aumentar zoom">
                                                <i class="fas fa-search-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bg-white border rounded p-3 shadow-sm" style="max-height: 680px; overflow-x: auto; overflow-y: auto; -webkit-overflow-scrolling: touch;">
                                        <div style="min-width: 920px; width: fit-content; padding-bottom: 12px; display: inline-block;">
                                            <canvas id="grafica_barras_{{ $reporte->id }}" style="display: block;"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pastel_{{ $reporte->id }}" role="tabpanel">
                                    <div style="overflow-x: auto; overflow-y: hidden; width: 100%; display: block; background-color: #ffffff !important; border-radius: 8px; padding: 10px; border: 1px solid #dee2e6; -webkit-overflow-scrolling: touch;">
                                        <div style="min-width: 420px; width: 420px; height: 420px; display: inline-block;">
                                            <canvas id="grafica_pastel_{{ $reporte->id }}" style="width: 100%; height: 100%; display: block; background-color: transparent !important;"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tabla_{{ $reporte->id }}" role="tabpanel">
                                    <input type="text" id="buscadorTabla_{{ $reporte->id }}" 
                                   class="form-control mb-3" 
                                   placeholder="Buscar en la tabla...">
                                   
                                    <div class="table-responsive d-none d-md-block">
                                    <table class="table table-bordered table-striped table-hover text-center align-middle table-sm" 
                                           id="tablaDetalle_{{ $reporte->id }}" style="table-layout: auto; width: 100%;">

                                            <thead style="background-color: #691C32; color: white;">
                                                <tr>
                                                    <th style="min-width: 300px; max-width: 300px; white-space: normal; word-wrap: break-word;">Objeto del Gasto</th>
                                                    <th>Aprobado</th>
                                                    <th>Presupuesto Vigente</th>
                                                    <th>Pagado</th>
                                                    <th>Saldo (Diferencia)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($grafica->labels))
                                                    @foreach($grafica->labels as $index => $label)
                                                    <tr>
                                                        <td class="text-start fw-bold" style="white-space: normal; word-break: break-word; max-width: 300px;">{{ $label }}</td>
                                                        <td style="white-space: normal; word-break: break-word;">${{ number_format((float)($grafica->aprobado[$index] ?? 0), 2) }}</td>
                                                        <td style="white-space: normal; word-break: break-word;">${{ number_format((float)($grafica->vigente[$index] ?? 0), 2) }}</td>
                                                        <td class="text-success fw-bold" style="white-space: normal; word-break: break-word;">${{ number_format((float)($grafica->pagado[$index] ?? 0), 2) }}</td>
                                                        <td class="text-primary fw-bold" style="white-space: normal; word-break: break-word;">${{ number_format((float)($grafica->diferencia[$index] ?? 0), 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot style="background-color: #f8f9fa; font-weight: bold;">
                                                <tr>
                                                    <td class="text-end">TOTAL:</td>
                                                    <td>${{ number_format(array_sum((array)($grafica->aprobado ?? [])), 2) }}</td>
                                                    <td>${{ number_format(array_sum((array)($grafica->vigente ?? [])), 2) }}</td>
                                                    <td class="text-success">${{ number_format(array_sum((array)($grafica->pagado ?? [])), 2) }}</td>
                                                    <td class="text-primary">${{ number_format(array_sum((array)($grafica->diferencia ?? [])), 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <!-- Diseño de tarjetas para móviles -->
                                    <div class="d-md-none">
                                        @if(isset($grafica->labels))
                                            @foreach($grafica->labels as $index => $label)
                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0 fw-bold">{{ $label }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row text-center">
                                                            <div class="col-6">
                                                                <small class="text-muted">Aprobado</small><br>
                                                                <span class="fw-bold">${{ number_format((float)($grafica->aprobado[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Vigente</small><br>
                                                                <span class="fw-bold">${{ number_format((float)($grafica->vigente[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row text-center">
                                                            <div class="col-6">
                                                                <small class="text-muted">Pagado</small><br>
                                                                <span class="fw-bold text-success">${{ number_format((float)($grafica->pagado[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Saldo</small><br>
                                                                <span class="fw-bold text-primary">${{ number_format((float)($grafica->diferencia[$index] ?? 0), 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <h6 class="text-primary mb-0">TOTAL</h6>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small class="text-muted">Aprobado</small><br>
                                                            <span class="fw-bold">${{ number_format(array_sum((array)($grafica->aprobado ?? [])), 2) }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Vigente</small><br>
                                                            <span class="fw-bold">${{ number_format(array_sum((array)($grafica->vigente ?? [])), 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small class="text-muted">Pagado</small><br>
                                                            <span class="fw-bold text-success">${{ number_format(array_sum((array)($grafica->pagado ?? [])), 2) }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Saldo</small><br>
                                                            <span class="fw-bold text-primary">${{ number_format(array_sum((array)($grafica->diferencia ?? [])), 2) }}</span>
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
<script>
document.addEventListener('DOMContentLoaded', function() {

    async function addImageToPdfWithPageBreaks(pdf, imgData, x, y, width, margin = 10) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = function() {
                const pageHeight = (pdf.internal.pageSize && typeof pdf.internal.pageSize.getHeight === 'function')
                    ? pdf.internal.pageSize.getHeight()
                    : pdf.internal.pageSize.height;
                const ratio = width / img.width;
                const maxPageHeight = Math.floor((pageHeight - y - margin) / ratio);
                let srcY = 0;
                let firstPage = true;

                while (srcY < img.height) {
                    const sliceHeight = Math.min(maxPageHeight, img.height - srcY);
                    const canvasSlice = document.createElement('canvas');
                    canvasSlice.width = img.width;
                    canvasSlice.height = sliceHeight;
                    const ctx = canvasSlice.getContext('2d');
                    ctx.drawImage(img, 0, srcY, img.width, sliceHeight, 0, 0, img.width, sliceHeight);
                    const sliceData = canvasSlice.toDataURL('image/png', 1.0);
                    const slicePdfHeight = sliceHeight * ratio;
                    if (!firstPage) {
                        pdf.addPage();
                    }
                    pdf.addImage(sliceData, 'PNG', x, firstPage ? y : 10, width, slicePdfHeight);
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
        'rgba(13, 110, 253, 0.8)',
        'rgba(25, 135, 84, 0.8)',
        'rgba(255, 193, 7, 0.8)',
        'rgba(108, 117, 125, 0.8)',
        'rgba(220, 53, 69, 0.8)',
        'rgba(23, 162, 184, 0.8)',
        'rgba(255, 152, 0, 0.8)',
        'rgba(111, 66, 193, 0.8)',
        'rgba(32, 201, 151, 0.8)',
        'rgba(159, 34, 65, 0.8)'
    ];

    @foreach($reportes as $reporte)
    (function() {

        // ── 1. GRÁFICA DE BARRAS ──────────────────────────────────────────
        const ctxBarras = document.getElementById('grafica_barras_{{ $reporte->id }}');
        if (ctxBarras) {
            const labelsBarras = {!! json_encode($grafica->labels ?? []) !!};
            const canvasHeight = Math.max(480, labelsBarras.length * 42 + 120);
            const canvasWidth = Math.max(920, labelsBarras.length * 80);
            let currentBarWidth = canvasWidth;
            const minBarWidth = Math.max(760, canvasWidth * 0.75);
            const maxBarWidth = Math.max(canvasWidth * 2, 1600);
            const zoomStep = 1.15;
            ctxBarras.width = currentBarWidth;
            ctxBarras.height = canvasHeight;
            ctxBarras.style.width = currentBarWidth + 'px';
            ctxBarras.style.height = canvasHeight + 'px';

            function setBarChartWidth(newWidth) {
                currentBarWidth = Math.max(minBarWidth, Math.min(maxBarWidth, Math.round(newWidth)));
                ctxBarras.width = currentBarWidth;
                ctxBarras.style.width = currentBarWidth + 'px';
                if (window["chartBarras{{ $reporte->id }}"]) {
                    window["chartBarras{{ $reporte->id }}"].resize();
                    window["chartBarras{{ $reporte->id }}"].update();
                }
            }

            window["chartBarras{{ $reporte->id }}"] = new Chart(ctxBarras.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsBarras,
                    datasets: [
                        {
                            label: 'Aprobado',
                            data: {!! json_encode($grafica->aprobado ?? []) !!},
                            backgroundColor: 'rgba(108, 117, 125, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Vigente',
                            data: {!! json_encode($grafica->vigente ?? []) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Pagado',
                            data: {!! json_encode($grafica->pagado ?? []) !!},
                            backgroundColor: 'rgba(25, 135, 84, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Saldo',
                            data: {!! json_encode($grafica->diferencia ?? []) !!},
                            backgroundColor: 'rgba(13, 110, 253, 0.8)',
                            borderRadius: 6,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    indexAxis: 'y', // Barras horizontales
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        x: { // Eje de valores
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                color: '#555',
                                font: { size: 8 },
                                callback: function(value) {
                                    if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'k';
                                    return '$' + value;
                                }
                            }
                        },
                        y: { // Eje de categorías
                            ticks: {
                                color: '#555',
                                font: { size: 8 }
                            },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Comparativo Financiero',
                            font: { size: 14, weight: 'bold' },
                            color: '#0d6efd',
                            padding: { top: 10, bottom: 20 }
                        },
                        legend: {
                            position: 'top',
                            labels: { font: { size: 10 }, color: '#333' }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.x !== null) {
                                        label += new Intl.NumberFormat('es-MX', {
                                            style: 'currency', currency: 'MXN'
                                        }).format(context.parsed.x);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
            // Agregar atributo para mejorar rendimiento
            window["chartBarras{{ $reporte->id }}"].canvas.willReadFrequently = true;

            const zoomInButton = document.getElementById("zoomIn_{{ $reporte->id }}");
            const zoomOutButton = document.getElementById("zoomOut_{{ $reporte->id }}");
            if (zoomInButton) {
                zoomInButton.addEventListener("click", function() {
                    setBarChartWidth(currentBarWidth * zoomStep);
                });
            }
            if (zoomOutButton) {
                zoomOutButton.addEventListener("click", function() {
                    setBarChartWidth(currentBarWidth / zoomStep);
                });
            }
        }

        // ── 2. GRÁFICA DE PASTEL ──────────────────────────────────────────
        const ctxPastel = document.getElementById('grafica_pastel_{{ $reporte->id }}');
        if (ctxPastel) {
            const pastelSize = 420;
            ctxPastel.width = pastelSize;
            ctxPastel.height = pastelSize;
            ctxPastel.style.width = pastelSize + 'px';
            ctxPastel.style.height = pastelSize + 'px';
            window["chartPastel{{ $reporte->id }}"] = new Chart(ctxPastel.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($grafica->labels ?? []) !!},
                    datasets: [{
                        label: 'Total Pagado ($)',
                        data: {!! json_encode($grafica->pagado ?? []) !!},
                        backgroundColor: paletaColores,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            align: 'center',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                padding: 6,
                                font: { size: 7 },
                                textAlign: 'center'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Pagado: ' + new Intl.NumberFormat('es-MX', {
                                        style: 'currency', currency: 'MXN'
                                    }).format(context.parsed);
                                }
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 20,
                            left: 10,
                            right: 10
                        }
                    }
                }
            });
            // Agregar atributo para mejorar rendimiento
            window["chartPastel{{ $reporte->id }}"].canvas.willReadFrequently = true;
        }

        // ── 3. BOTÓN EXPORTAR PDF ─────────────────────────────────────────
        const btnExportar = document.getElementById("btnExportar_{{ $reporte->id }}");
        if (!btnExportar) return;

        btnExportar.addEventListener("click", async function() {
            // Cambiar botón a estado de carga
            btnExportar.disabled = true;
            btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';

            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF("p", "mm", "a4");
                const pageWidth = 190;
                let hayContenido = false;

                // Gráfica de Barras (ya activa por defecto)
                const chartBarras = window["chartBarras{{ $reporte->id }}"];
                if (chartBarras) {
                    try {
                        chartBarras.update();
                        const imgBarras = chartBarras.toBase64Image();
                        pdf.setFont(undefined, 'bold');
                        pdf.setFontSize(16);
                        pdf.setTextColor(105, 28, 50);
                        pdf.text("Gráfica de Barras", 10, 15);
                        pdf.setDrawColor(105, 28, 50);
                        pdf.line(10, 16.5, 85, 16.5);
                        await addImageToPdfWithPageBreaks(pdf, imgBarras, 10, 22, pageWidth, 10);
                        hayContenido = true;
                        pdf.addPage();
                    } catch(e) {
                        console.error("Error capturando gráfica de barras:", e);
                    }
                }

                // Gráfica de Pastel
                const chartPastel = window["chartPastel{{ $reporte->id }}"];
                if (chartPastel) {
                    try {
                        // Activar tab de pastel para asegurar renderizado
                        const tabPastel = document.querySelector('#tabs_{{ $reporte->id }} .nav-link[data-bs-target="#pastel_{{ $reporte->id }}"]');
                        if (tabPastel) tabPastel.click();
                        await new Promise(r => setTimeout(r, 150));
                        chartPastel.update();
                        await new Promise(r => setTimeout(r, 100));
                        const imgPastel = chartPastel.toBase64Image();
                        pdf.setFont(undefined, 'bold');
                        pdf.setFontSize(16);
                        pdf.setTextColor(105, 28, 50);
                        pdf.text("Gráfica de Pastel", 10, 15);
                        pdf.setDrawColor(105, 28, 50);
                        pdf.line(10, 16.5, 85, 16.5);
                        pdf.addImage(imgPastel, "PNG", 25, 22, 150, 150);
                        hayContenido = true;
                        pdf.addPage();
                    } catch(e) {
                        console.error("Error capturando gráfica de pastel:", e);
                    }
                }

                // Tabla Detallada - Generar tabla en texto dentro del PDF
                const tablaElement = document.querySelector("#tablaDetalle_{{ $reporte->id }}");
                if (tablaElement) {
                    try {
                        // Activar tab de tabla para asegurar visibilidad
                        const tabTabla = document.querySelector('#tabs_{{ $reporte->id }} .nav-link[data-bs-target="#tabla_{{ $reporte->id }}"]');
                        if (tabTabla) tabTabla.click();
                        await new Promise(r => setTimeout(r, 100));

                        const rows = tablaElement.querySelectorAll('tbody tr');
                        const tfoot = tablaElement.querySelector('tfoot tr');

                        if (rows.length > 0) {
                            pdf.setFont(undefined, 'bold');
                            pdf.setFontSize(16);
                            pdf.setTextColor(105, 28, 50);
                            pdf.text("Tabla Detallada", 10, 15);
                            pdf.setDrawColor(105, 28, 50);
                            pdf.line(10, 16.5, 85, 16.5);
                            pdf.setFont(undefined, 'normal');
                            pdf.setFontSize(9);
                            pdf.setTextColor(0, 0, 0);

                            let yPosition = 25;
                            const pageHeight = pdf.internal.pageSize.height;
                            const col1Width = 65;
                            const col2Width = 31;

                            // Encabezado
                            pdf.setFillColor(105, 28, 50);
                            pdf.setTextColor(255, 255, 255);
                            pdf.rect(10, yPosition, col1Width, 8, 'F');
                            pdf.rect(10 + col1Width, yPosition, col2Width, 8, 'F');
                            pdf.rect(10 + col1Width + col2Width, yPosition, col2Width, 8, 'F');
                            pdf.rect(10 + col1Width + col2Width * 2, yPosition, col2Width, 8, 'F');
                            pdf.rect(10 + col1Width + col2Width * 3, yPosition, col2Width, 8, 'F');

                            pdf.text("Objeto del Gasto", 12, yPosition + 5);
                            pdf.text("Aprobado", 10 + col1Width + 2, yPosition + 5);
                            pdf.text("Vigente", 10 + col1Width + col2Width + 2, yPosition + 5);
                            pdf.text("Pagado", 10 + col1Width + col2Width * 2 + 2, yPosition + 5);
                            pdf.text("Saldo", 10 + col1Width + col2Width * 3 + 2, yPosition + 5);

                            yPosition += 8;
                            pdf.setTextColor(0, 0, 0);

                            // Filas
                            rows.forEach((row, index) => {
                                const cells = row.querySelectorAll('td');
                                const label = cells[0]?.textContent.trim() || '';
                                const aprobado = cells[1]?.textContent.trim() || '';
                                const vigente = cells[2]?.textContent.trim() || '';
                                const pagado = cells[3]?.textContent.trim() || '';
                                const saldo = cells[4]?.textContent.trim() || '';

                                // Dividir el texto largo en líneas que caben en col1Width
                                const labelLines = pdf.splitTextToSize(label, col1Width - 4);
                                const dynamicRowHeight = Math.max(8, labelLines.length * 5 + 4);

                                if (yPosition + dynamicRowHeight > pageHeight - 10) {
                                    pdf.addPage();
                                    yPosition = 10;
                                }

                                if (index % 2 === 0) {
                                    pdf.setFillColor(240, 240, 240);
                                    pdf.rect(10, yPosition, col1Width + col2Width * 4, dynamicRowHeight, 'F');
                                }

                                // Dibujar el texto de la primera columna línea por línea
                                labelLines.forEach((line, li) => {
                                    pdf.text(line, 12, yPosition + 4 + (li * 5));
                                });

                                // Las otras columnas se centran verticalmente en la fila
                                const midRow = yPosition + dynamicRowHeight / 2;
                                pdf.text(aprobado, 10 + col1Width + 2, midRow);
                                pdf.text(vigente, 10 + col1Width + col2Width + 2, midRow);
                                pdf.text(pagado, 10 + col1Width + col2Width * 2 + 2, midRow);
                                pdf.text(saldo, 10 + col1Width + col2Width * 3 + 2, midRow);

                                yPosition += dynamicRowHeight;
                            });

                            // Total
                            yPosition += 2;
                            pdf.setFillColor(105, 28, 50);
                            pdf.setTextColor(255, 255, 255);
                            pdf.rect(10, yPosition, col1Width + col2Width * 4, 8, 'F');

                            const totalCells = tfoot.querySelectorAll('td');
                            const totalLabel = totalCells[0]?.textContent.trim() || 'TOTAL:';
                            const totalAprobado = totalCells[1]?.textContent.trim() || '';
                            const totalVigente = totalCells[2]?.textContent.trim() || '';
                            const totalPagado = totalCells[3]?.textContent.trim() || '';
                            const totalSaldo = totalCells[4]?.textContent.trim() || '';

                            pdf.text(totalLabel, 12, yPosition + 5);
                            pdf.text(totalAprobado, 10 + col1Width + 2, yPosition + 5);
                            pdf.text(totalVigente, 10 + col1Width + col2Width + 2, yPosition + 5);
                            pdf.text(totalPagado, 10 + col1Width + col2Width * 2 + 2, yPosition + 5);
                            pdf.text(totalSaldo, 10 + col1Width + col2Width * 3 + 2, yPosition + 5);

                            hayContenido = true;
                        }
                    } catch(e) {
                        console.error("Error capturando tabla:", e);
                    }
                }

                // Regresar al tab de barras
                const tabBarras = document.querySelector('#tabs_{{ $reporte->id }} .nav-link[data-bs-target="#barras_{{ $reporte->id }}"]');
                if (tabBarras) tabBarras.click();

                if (hayContenido) {
                    pdf.save("reporte_{{ $reporte->id }}.pdf");
                } else {
                    alert("No se pudo generar el PDF: no se encontraron gráficas ni tabla.");
                }
            } finally {
                // Restaurar botón
                btnExportar.disabled = false;
                btnExportar.innerHTML = '<i class="fas fa-file-pdf"></i> Exportar PDF';
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
            });
        }
    @endforeach
});
</script>


@endsection
