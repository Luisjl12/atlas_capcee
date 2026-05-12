

<?php $__env->startSection('title', 'Dictamen del Ticket'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-header">
    <a href="<?php echo e(route('seguimiento-proyectos')); ?>" class="btn-icon-only">
        <i class="fas fa-arrow-left me-2"></i>
        <i class="fas fa-ticket-alt me-2"></i>
        <h3 class="mb-0">Dictamen del Ticket: <?php echo e($ticket->folio); ?></h3>
    </a>
</div>


<div class="form-navigation nav-tabs-text mb-3">
    <span class="nav-tab active" data-step="0" data-bs-toggle="tab" data-bs-target="#tab-0" role="tab">I. Solicitante</span>
    <span class="nav-tab" data-step="1" data-bs-toggle="tab" data-bs-target="#tab-1" role="tab">II. Persona que Turna</span>
    <span class="nav-tab" data-step="2" data-bs-toggle="tab" data-bs-target="#tab-2" role="tab">III. Clave CCT</span>
    <span class="nav-tab" data-step="3" data-bs-toggle="tab" data-bs-target="#tab-3" role="tab">IV. Informacion del Plantel</span>
</div>


<div class="tab-content">
    <!-- Sección I -->
    <div class="tab-pane fade show active" id="tab-0">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Datos del Solicitante</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="nombre_solicitante" class="form-label">Nombre</label>
                    <input type="text" name="nombre_solicitante" id="nombre_solicitante"
                        class="form-control"
                        value="<?php echo e(old('nombre_solicitante', $ticket->solicitante->nombre_solicitante ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="cargo_solicitante" class="form-label">Cargo</label>
                    <input type="text" name="cargo_solicitante" id="cargo_solicitante"
                        class="form-control"
                        value="<?php echo e(old('cargo_solicitante', $ticket->solicitante->cargo_solicitante ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="organismo_dependencia" class="form-label">Organismo o Dependencia</label>
                    <input type="text" name="organismo_dependencia" id="organismo_dependencia"
                        class="form-control"
                        value="<?php echo e(old('organismo_dependencia', $ticket->solicitante->organismo_dependencia ?? '')); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Sección II -->
    <div class="tab-pane fade" id="tab-1">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Persona que lo Turna</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="persona_turna" class="form-label">Nombre</label>
                    <input type="text" name="persona_turna" id="persona_turna"
                        class="form-control"
                        value="<?php echo e(old('persona_turna', $ticket->solicitante->persona_turna ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="cargo_turna" class="form-label">Cargo</label>
                    <input type="text" name="cargo_turna" id="cargo_turna"
                        class="form-control"
                        value="<?php echo e(old('cargo_turna', $ticket->solicitante->cargo_turna ?? '')); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Sección III -->
    <div class="tab-pane fade" id="tab-2">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Otros Datos</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="clave_cct" class="form-label">Clave CCT</label>
                    <input type="text" name="clave_cct" id="clave_cct"
                        class="form-control"
                        value="<?php echo e(old('clave_cct', $ticket->solicitante->clave_cct ?? '')); ?>">
                </div>
            </div>
        </div>
    </div>

    <!--Seccion IV--->
    <div class="tab-pane fade" id="tab-3">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Evaluación del Proyecto</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="nivel" class="form-label">Nivel</label>
                    <input type="text" name="nivel" id="nivel" class="form-control"
                           value="<?php echo e(old('nivel', $ticket->nivel ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="modalidad" class="form-label">Modalidad</label>
                    <input type="text" name="modalidad" id="modalidad" class="form-control"
                           value="<?php echo e(old('modalidad', $ticket->modalidad ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="plantel" class="form-label">Plantel</label>
                    <input type="text" name="plantel" id="plantel" class="form-control"
                           value="<?php echo e(old('plantel', $ticket->plantel ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="turno" class="form-label">Turno</label>
                    <input type="text" name="turno" id="turno" class="form-control"
                           value="<?php echo e(old('turno', $ticket->turno ?? '')); ?>">
                </div>
                <div class="mb-3">
                    <label for="numero_alumnos" class="form-label">Número de Alumnos</label>
                    <input type="number" name="numero_alumnos" id="numero_alumnos" class="form-control"
                           value="<?php echo e(old('numero_alumnos', $ticket->numero_alumnos ?? '')); ?>">
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-success mt-3">
    <i class="fas fa-save"></i> Guardar Dictamen
</button>
</form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".nav-tab");
    const panes = document.querySelectorAll(".tab-pane");

    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            tabs.forEach(t => t.classList.remove("active"));
            panes.forEach(p => p.classList.remove("show", "active"));
            this.classList.add("active");
            const target = document.querySelector(this.getAttribute("data-bs-target"));
            target.classList.add("show", "active");
        });
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/dictamen_ticket.blade.php ENDPATH**/ ?>