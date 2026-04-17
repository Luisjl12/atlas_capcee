

<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <h2 class="mb-4 text-dark"><i class="fas fa-chart-line"></i> Reportes Financieros Dirección administrativa</h2>

    <?php if($reportes->isEmpty()): ?>
        <div class="alert alert-info text-center shadow-sm">
            <i class="fas fa-info-circle fa-2x mb-2"></i>
            <h5>Aún no hay reportes publicados.</h5>
            <p class="mb-0">Cuando el Administrador suba un nuevo reporte, aparecerá aquí.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reporte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                    $grafica = json_decode($reporte->datos_grafica); 
                ?>

                <div class="col-md-12 mb-5">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><strong><?php echo e($reporte->titulo); ?></strong></h5>
                            <button id="btnExportar_<?php echo e($reporte->id); ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </button>
                        </div>
                        <div class="card-body">
                            
                            <ul class="nav nav-tabs" id="tabs_<?php echo e($reporte->id); ?>" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#barras_<?php echo e($reporte->id); ?>" type="button" role="tab">
                                        <i class="fas fa-chart-bar"></i> Comparativa
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pastel_<?php echo e($reporte->id); ?>" type="button" role="tab">
                                        <i class="fas fa-chart-pie"></i> Gastos Pagados
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabla_<?php echo e($reporte->id); ?>" type="button" role="tab">
                                        <i class="fas fa-table"></i> Tabla Detallada
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-4" id="contenidoTabs_<?php echo e($reporte->id); ?>">
                                
                                <div class="tab-pane fade show active" id="barras_<?php echo e($reporte->id); ?>" role="tabpanel">
                                    <div class="bg-white border rounded p-3 shadow-sm" style="height: 800px;">
                                        <canvas id="grafica_barras_<?php echo e($reporte->id); ?>"></canvas>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pastel_<?php echo e($reporte->id); ?>" role="tabpanel">
                                    <div style="height: 350px; display: flex; justify-content: center; background-color: #ffffff !important; border-radius: 8px; padding: 10px; border: 1px solid #dee2e6;">
                                        <canvas id="grafica_pastel_<?php echo e($reporte->id); ?>" style="background-color: transparent !important;"></canvas>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tabla_<?php echo e($reporte->id); ?>" role="tabpanel">
                                    <input type="text" id="buscadorTabla_<?php echo e($reporte->id); ?>" 
                                   class="form-control mb-3" 
                                   placeholder="Buscar en la tabla...">
                                   
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover text-center align-middle table-sm" 
                                           id="tablaDetalle_<?php echo e($reporte->id); ?>">

                                            <thead style="background-color: #691C32; color: white;">
                                                <tr>
                                                    <th>Objeto del Gasto</th>
                                                    <th>Aprobado</th>
                                                    <th>Presupuesto Vigente</th>
                                                    <th>Pagado</th>
                                                    <th>Saldo (Diferencia)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($grafica->labels)): ?>
                                                    <?php $__currentLoopData = $grafica->labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td class="text-start fw-bold"><?php echo e($label); ?></td>
                                                        <td>$<?php echo e(number_format((float)($grafica->aprobado[$index] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format((float)($grafica->vigente[$index] ?? 0), 2)); ?></td>
                                                        <td class="text-success fw-bold">$<?php echo e(number_format((float)($grafica->pagado[$index] ?? 0), 2)); ?></td>
                                                        <td class="text-primary fw-bold">$<?php echo e(number_format((float)($grafica->diferencia[$index] ?? 0), 2)); ?></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot style="background-color: #f8f9fa; font-weight: bold;">
                                                <tr>
                                                    <td class="text-end">TOTAL:</td>
                                                    <td>$<?php echo e(number_format(array_sum((array)($grafica->aprobado ?? [])), 2)); ?></td>
                                                    <td>$<?php echo e(number_format(array_sum((array)($grafica->vigente ?? [])), 2)); ?></td>
                                                    <td class="text-success">$<?php echo e(number_format(array_sum((array)($grafica->pagado ?? [])), 2)); ?></td>
                                                    <td class="text-primary">$<?php echo e(number_format(array_sum((array)($grafica->diferencia ?? [])), 2)); ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

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

    <?php $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reporte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    (function() {

        // ── 1. GRÁFICA DE BARRAS ──────────────────────────────────────────
        const ctxBarras = document.getElementById('grafica_barras_<?php echo e($reporte->id); ?>');
        if (ctxBarras) {
            window["chartBarras<?php echo e($reporte->id); ?>"] = new Chart(ctxBarras.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($grafica->labels ?? []); ?>,
                    datasets: [
                        {
                            label: 'Aprobado',
                            data: <?php echo json_encode($grafica->aprobado ?? []); ?>,
                            backgroundColor: 'rgba(108, 117, 125, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Vigente',
                            data: <?php echo json_encode($grafica->vigente ?? []); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Pagado',
                            data: <?php echo json_encode($grafica->pagado ?? []); ?>,
                            backgroundColor: 'rgba(25, 135, 84, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Saldo',
                            data: <?php echo json_encode($grafica->diferencia ?? []); ?>,
                            backgroundColor: 'rgba(13, 110, 253, 0.8)',
                            borderRadius: 6,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                color: '#555',
                                callback: function(value) {
                                    if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return '$' + (value / 1000).toFixed(1) + 'k';
                                    return '$' + value;
                                }
                            }
                        },
                        x: {
                            ticks: { color: '#555' },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Comparativo Financiero',
                            font: { size: 16, weight: 'bold' },
                            color: '#0d6efd',
                            padding: { top: 10, bottom: 20 }
                        },
                        legend: {
                            position: 'top',
                            labels: { font: { size: 12, weight: '600' }, color: '#333' }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('es-MX', {
                                            style: 'currency', currency: 'MXN'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // ── 2. GRÁFICA DE PASTEL ──────────────────────────────────────────
        const ctxPastel = document.getElementById('grafica_pastel_<?php echo e($reporte->id); ?>');
        if (ctxPastel) {
            window["chartPastel<?php echo e($reporte->id); ?>"] = new Chart(ctxPastel.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($grafica->labels ?? []); ?>,
                    datasets: [{
                        label: 'Total Pagado ($)',
                        data: <?php echo json_encode($grafica->pagado ?? []); ?>,
                        backgroundColor: paletaColores,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Pagado: ' + new Intl.NumberFormat('es-MX', {
                                        style: 'currency', currency: 'MXN'
                                    }).format(context.parsed);
                                }
                            }
                        }
                    }
                }
            });
        }

        // ── 3. BOTÓN EXPORTAR PDF ─────────────────────────────────────────
        const btnExportar = document.getElementById("btnExportar_<?php echo e($reporte->id); ?>");
        if (!btnExportar) return;

        btnExportar.addEventListener("click", async function() {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF("p", "mm", "a4");
            const pageWidth = 190;
            let hayContenido = false;

            // Gráfica de Barras
            const chartBarras = window["chartBarras<?php echo e($reporte->id); ?>"];
            if (chartBarras) {
                try {
                    chartBarras.update();
                    await new Promise(r => setTimeout(r, 300));
                    const imgBarras = chartBarras.canvas.toDataURL("image/png", 1.0);
                    pdf.setFontSize(14);
                    pdf.setTextColor(13, 110, 253);
                    pdf.text("Gráfica de Barras", 10, 15);
                    pdf.addImage(imgBarras, "PNG", 10, 22, pageWidth, 100);
                    hayContenido = true;
                    pdf.addPage();
                } catch(e) {
                    console.error("Error capturando gráfica de barras:", e);
                }
            }

            // Gráfica de Pastel
            const chartPastel = window["chartPastel<?php echo e($reporte->id); ?>"];
            if (chartPastel) {
                try {
                    chartPastel.update();
                    await new Promise(r => setTimeout(r, 300));
                    const imgPastel = chartPastel.canvas.toDataURL("image/png", 1.0);
                    pdf.setFontSize(14);
                    pdf.setTextColor(13, 110, 253);
                    pdf.text("Gráfica de Pastel", 10, 15);
                    pdf.addImage(imgPastel, "PNG", 25, 22, 150, 150);
                    hayContenido = true;
                    pdf.addPage();
                } catch(e) {
                    console.error("Error capturando gráfica de pastel:", e);
                }
            }

            // Tabla Detallada
            const contenedorTabla = document.querySelector("#tabla_<?php echo e($reporte->id); ?> table");
            if (contenedorTabla) {
                try {
                    const tablaCanvas = await html2canvas(contenedorTabla, {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        backgroundColor: "#ffffff"
                    });
                    const imgTabla = tablaCanvas.toDataURL("image/png", 1.0);
                    const imgHeight = pageWidth * (tablaCanvas.height / tablaCanvas.width);
                    pdf.setFontSize(14);
                    pdf.setTextColor(13, 110, 253);
                    pdf.text("Tabla Detallada", 10, 15);
                    pdf.addImage(imgTabla, "PNG", 10, 22, pageWidth, imgHeight);
                    hayContenido = true;
                } catch(e) {
                    console.error("Error capturando tabla:", e);
                }
            }

            if (hayContenido) {
                pdf.save("reporte_<?php echo e($reporte->id); ?>.pdf");
            } else {
                alert("No se pudo generar el PDF: no se encontraron gráficas ni tabla.");
            }
        });

    })();
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reporte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        const input<?php echo e($reporte->id); ?> = document.getElementById("buscadorTabla_<?php echo e($reporte->id); ?>");
        const tabla<?php echo e($reporte->id); ?> = document.getElementById("tablaDetalle_<?php echo e($reporte->id); ?>");

        if(input<?php echo e($reporte->id); ?> && tabla<?php echo e($reporte->id); ?>) {
            input<?php echo e($reporte->id); ?>.addEventListener("keyup", function() {
                let filtro = this.value.toLowerCase();
                let filas = tabla<?php echo e($reporte->id); ?>.querySelectorAll("tbody tr");
                filas.forEach(fila => {
                    fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? "" : "none";
                });
            });
        }
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/director/ver_reportes.blade.php ENDPATH**/ ?>