

<?php $__env->startSection('title', 'Ver Plantel'); ?>


<?php $__env->startSection('content'); ?>
<?php
function obtenerIconoArchivo($nombreArchivo) {
$extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

return match($extension) {
'pdf' => ['fas fa-file-pdf', 'text-danger'],
'doc', 'docx' => ['fas fa-file-word', 'text-primary'],
'xls', 'xlsx' => ['fas fa-file-excel', 'text-success'],
'jpg', 'jpeg', 'png', 'gif' => ['fas fa-file-image', 'text-warning'],
'zip', 'rar' => ['fas fa-file-archive', 'text-muted'],
'txt' => ['fas fa-file-alt', 'text-secondary'],
default => ['fas fa-file', 'text-dark'],
};
}
?>



    <div class="card-header">
        <a href="<?php echo e(route('planteles.index')); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left "></i>
            <h3><i class="fas fa-school me-2 mb-3"></i> <?php echo e($plantel->nombre_escuela); ?>

                <small class="text-muted">(CCT: <?php echo e($plantel->cct); ?>)</small></h3>
        </a>

    </div>

    <div class="form-navigation nav-tabs-text mb-3">
        <span class="nav-tab" data-step="0">Ficha Base</span>
        <span class="nav-tab" data-step="1">Espacios/Areas</span>
        <span class="nav-tab" data-step="2">Servicios</span>
        <span class="nav-tab" data-step="3">Proteccion Civil</span>
        <span class="nav-tab" data-step="4">Archivos</span>
        <span class="nav-tab" data-step="5">Galerias</span>
        <span class="nav-tab" data-step="6">Ubicacion en el mapa</span>
        <span class="nav-tab" data-step="7">Detalles avanzados</span>
        <span class="nav-tab" data-step="8">Intervencion del CAPCEE</span>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>


    <!-- Ficha Base -->
    <div class="form-ficha-base">

        <div class="form-section step-section" data-step="0">

            <h4>Información General del Plantel</h4>
            <div class="data-grid">
                <div class="data-pair"><label>Nombre Oficial:</label><span><?php echo e($plantel->nombre_escuela); ?></span></div>
                <div class="data-pair"><label>Nivel Educativo:</label><span> <?php echo e($plantel->nivel_educativo); ?></span></div>
                <div class="data-pair"><label>Turno:</label><span><?php echo e($plantel->turno); ?></span></div>
                <div class="data-pair"><label>Sostenimiento:</label><span><?php echo e($plantel->sostenimiento); ?></span></div>
            </div>

            <div class= "d-flex flex-column flex-md-row">
                <a href="<?php echo e(route('planteles.edit', $plantel->id)); ?>" class="btn-custom btn-primary mt-3">
                <i class=" fas fa-edit"></i> Editar Ficha Base
                </a>
            </div>
        </div>


        <!-- Espacios / Áreas -->
        <div class="form-section step-section d-none" data-step="1">
            <h4>Inventario de Espacios Físicos</h4>
            <h5>Agregar nuevo espacio</h5>
            <form action="<?php echo e(route('espacios.store')); ?>" method="POST" class="row g-3 mb-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="cct" value="<?php echo e($plantel->cct); ?>">

                <div class="col-md-4">
                    <label for="nombre_espacio" class="form-label">Nombre del Espacio</label>
                    <input type="text" name="nombre_espacio" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" min="1" required>
                </div>
                <div class="col-md-4">
                    <label for="estado_conservacion" class="form-label">Estado de Conservación</label>
                    <select name="estado_conservacion" class="form-select" required>
                        <option value="">Selecciona una Opción</option>
                        <?php $__currentLoopData = $estadosConservacion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($estado); ?>"><?php echo e(ucwords(str_replace('_', ' ', strtolower($estado)))); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn-custom btn-success">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
            </form>

            <?php if($plantel->espacios->isEmpty()): ?>
            <div class="alert alert-info text-center">No hay espcacios registrados.</div>
            <?php else: ?>
            <div class="table-responsive mt-3">
                <table class="table data-table">
                    <thead class="thead-custom">
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $plantel->espacios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $espacio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <!-- Fila principal visible solo en móvil -->
                        <tr class="espacio-row d-table-row d-md-none">
                            <td colspan="4" class="espacio-nombre position-relative" style="cursor: pointer;">
                                <div>
                                    <i class="fas fa-door-open text-primary me-2"></i>
                                    <?php echo e($espacio->nombre_espacio); ?>

                                </div>
                                <div class="toggle-icon position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </td>
                        </tr>


                        <!-- Fila completa visible solo en escritorio -->
                        <tr class="d-none d-md-table-row">
                            <td><?php echo e($espacio->nombre_espacio); ?></td>
                            <td><?php echo e($espacio->cantidad); ?></td>
                            <td>
                                <span class="badge status-<?php echo e(ucwords(str_replace('_', ' ', strtolower($espacio->estado_conservacion)))); ?>">
                                    <?php echo e($espacio->estado_conservacion); ?>

                                </span>
                            </td>
                            <td>
                                <form action="<?php echo e(route('espacios.destroy', $espacio->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn-custom btn-sm btn-danger"
                                        onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este espacio?', '<?php echo e(route('espacios.destroy', $espacio->id)); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Detalles expandibles solo en móvil -->
                        <tr class="espacio-detalle d-none d-md-none">
                            <td colspan="4">
                                <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                                    <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                        <strong>Cantidad:</strong> <?php echo e($espacio->cantidad); ?><br>
                                        <strong>Estado:</strong>
                                        <span class="badge status-<?php echo e(ucwords(str_replace('_', ' ', strtolower($espacio->estado_conservacion)))); ?>">
                                            <?php echo e($espacio->estado_conservacion); ?>

                                        </span>
                                    </div>
                                    <div class="w-100 mt-2">
                                        <form action="<?php echo e(route('espacios.destroy', $espacio->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="button" class="btn-custom btn-danger btn-sm"
                                                onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este espacio?', '<?php echo e(route('espacios.destroy', $espacio->id)); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>


        <!-- Servicios -->
        <div class="form-section step-section d-none" data-step="2">
            <h4>Infraestructura y Servicios</h4>
            <div class="row mt-3">
                <div class="col-12 mb-3">
                    <h5 style="font-weight:700;"><i class="fas fa-faucet" style="color:var(--color-info);"></i> Hidrosanitaria</h5>
                    <div class="data-grid">
                        <div class="data-pair"><label>Fuente de Agua:</label><span> <?php echo e($hidrosanitario->fuente_agua ?? 'No disponible'); ?></span></div>
                        <div class="data-pair"><label>Tipo de Drenaje:</label><span> <?php echo e($hidrosanitario->tipo_drenaje ?? 'No disponible'); ?></span></div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <h5 style="font-weight:700;"><i class="fas fa-bolt" style="color:var(--color-amarillo-primario);"></i> Servicios Básicos</h5>
                    <div class="data-grid">
                        <div class="data-pair">
                            <label>Contrato Electricidad:</label>
                            <?php echo e($servicio?->electricidad_contrato === 1 ? 'Sí' :
                                ($servicio?->electricidad_contrato === 0 ? 'No' : 'No disponible')); ?>

                        </div>
                        <div class="data-pair">
                            <label>Telefonía Fija:</label>
                            <?php echo e($servicio?->telefonia_fija === 1 ? 'Sí' :
                                ($servicio?->telefonia_fija === 0 ? 'No' : 'No disponible')); ?>

                        </div>
                        <div class="data-pair">
                            <label>Acceso a Internet:</label>
                            <?php echo e($servicio?->internet_acceso === 1 ? 'Sí' :
                                ($servicio?->internet_acceso === 0 ? 'No' : 'No disponible')); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class= "d-flex flex-column flex-md-row">
                <a href="<?php echo e(route('infraestructura.editar_completa', $plantel->cct)); ?>" class="btn-custom btn-primary mt-3">
                    <i class="fas fa-edit"></i> Editar Infraestructura y Servicios
                </a>
            </div>
        </div>


        <!-- Protección Civil -->
        <div class="form-section step-section d-none" data-step="3">
            <h4>Protección Civil</h4>

            <?php
            $detalle = $plantel->detalleProteccionCivil;
            ?>

            <?php if($detalle): ?>
            <div class="data-grid">

                <div class="data-pair"><label>Programa Interno PC:</label> <?php echo e($detalle->programa_interno_pc ? 'Sí' : 'No'); ?></div>
                <div class="data-pair"><label>Fecha Programa Interno:</label> <?php echo e($detalle->programa_interno_pc_fecha); ?></div>
                <div class="data-pair"><label>Alarma Sísmica:</label> <?php echo e($detalle->alarma_sismica ? 'Sí' : 'No'); ?></div>
                <div class="data-pair"><label>Alarma Funcional:</label> <?php echo e($detalle->alarma_sismica_funcional ? 'Sí' : 'No'); ?></div>
                <div class="data-pair"><label>Señalética Estado:</label> <?php echo e(ucwords(str_replace('_', ' ', strtolower($detalle->senaletica_estado)))); ?></div>
                <div class="data-pair"><label># Extintores:</label> <?php echo e($detalle->extintores_cantidad); ?></div>
                <div class="data-pair"><label>Extintores Vigentes:</label> <?php echo e($detalle->extintores_vigentes); ?></div>
                <div class="data-pair"><label>Brigadas Conformadas:</label> <?php echo e($detalle->brigadas_conformadas ? 'Sí' : 'No'); ?></div>
            </div>
            <?php else: ?>
            <p>No hay información de Protección Civil disponible para este plantel.</p>
            <?php endif; ?>
            <div class= "d-flex flex-column flex-md-row">
                <a href="<?php echo e(route('planteles.editar_proteccion_civil', $plantel->id)); ?>" class="btn-custom btn-primary mt-3">
                    <i class="fas fa-edit"></i> Editar Protección Civil
                </a>
            </div>
        </div>

        <!--Archivos-->
        <div class="form-section step-section d-none" data-step="4">
            <h4>Gestor de Archivos</h4>
            <h5>Subir Nuevo Archivo</h5>

            <form action="<?php echo e(route('archivos.store', ['id' => $plantel->id])); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="cct" value="<?php echo e($plantel->cct); ?>">
                <input type="hidden" name="id_plantel" value="<?php echo e($plantel->id); ?>">

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="archivo" class="form-label">Seleccionar archivo</label>
                        <input type="file" class="form-control" name="archivo" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="tipo_documento" class="form-label">Tipo de documento</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-select" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Plano">Plano</option>
                            <option value="Oficio">Oficio</option>
                            <option value="Otro">Otro...</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="otro_tipo_container">
                        <label for="otro_tipo" class="form-label">Especificar otro tipo de documento...</label>
                        <input type="text" name="otro_tipo" id="otro_tipo" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn-custom btn-success">
                    <i class="fas fa-upload"></i> Subir
                </button>
            </form>

            <div class="table-responsive mt-3">
                <?php if($archivos->isEmpty()): ?>
                <div class="alert alert-info text-center">No hay archivos subidos para este plantel.</div>
                <?php else: ?>
                <table class="table data-table">
                    <thead class="thead-custom">
                        <tr class="d-none d-md-table-row">
                            <th>Archivo</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Subido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $archivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $esImagen = Str::startsWith($archivo->mime_type, 'image/');
                        [$icono, $color] = obtenerIconoArchivo($archivo->nombre_archivo_original);
                        ?>

                        <!-- Fila principal visible solo en móvil -->
                        <tr class="archivo-row d-table-row d-md-none">
                            <td colspan="5" class="archivo-nombre position-relative" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if($esImagen): ?>
                                        <a href="javascript:void(0);" onclick="verDetallesFoto(
                                     '<?php echo e(asset('archivos_plantel/' . $archivo->ruta_archivo)); ?>

                                            ,
                                     '<?php echo e(e($archivo->cct)); ?>',
                                     '<?php echo e(e($plantel->nombre_escuela)); ?>',
                                     '<?php echo e(e($archivo->usuario->nombre ?? 'Desconocido')); ?>',
                                     '<?php echo e(e($archivo->descripcion)); ?>',
                                     '<?php echo e(e($archivo->fecha_subido)); ?>'
                                      )" style="text-decoration: none;">
                                            <i class="<?php echo e($icono); ?> <?php echo e($color); ?> me-2"></i>
                                            <?php echo e($archivo->nombre_archivo_original); ?>

                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('archivos-plantel.visualizar', $archivo->id)); ?>" target="_blank">
                                            <i class="<?php echo e($icono); ?> <?php echo e($color); ?>"></i>
                                            <?php echo e($archivo->nombre_archivo_original); ?>

                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                                        <i class="fas fa-chevron-down text-muted"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>


                        <!-- Detalles visibles solo en escritorio -->
                        <tr class="d-none d-md-table-row">
                            <td>
                                <?php if($esImagen): ?>
                                <a href="javascript:void(0);" 
                                onclick="verDetallesFoto(
                            '<?php echo e(asset("archivos_plantel/" . $archivo->ruta_archivo)); ?>',
                             '<?php echo e(e($archivo->cct)); ?>',
                             '<?php echo e(e($plantel->nombre_escuela)); ?>',
                             '<?php echo e(e($archivo->usuario->nombre ?? 'Desconocido')); ?>',
                            '<?php echo e(e($archivo->descripcion)); ?>',
                             '<?php echo e(e($archivo->fecha_subido)); ?>'
                            )"

                        style="text-decoration: none;">
                                    <i class="<?php echo e($icono); ?> <?php echo e($color); ?>"></i>
                                    <?php echo e($archivo->nombre_archivo_original); ?>

                                </a>
                                <?php else: ?>
                                <a href="<?php echo e(route('archivos-plantel.visualizar', $archivo->id)); ?>" target="_blank" style="text-decoration: none;">
                                    <i class="<?php echo e($icono); ?> <?php echo e($color); ?>"></i>
                                    <?php echo e($archivo->nombre_archivo_original); ?>

                                </a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($archivo->tipo_documento); ?></td>
                            <td><?php echo e($archivo->descripcion); ?></td>
                            <td><?php echo e($archivo->fecha_subido); ?></td>
                            <td>
                                <form action="<?php echo e(route('archivos.destroy', $archivo->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn-custom btn-danger btn-sm"
                                        onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este archivo?', '<?php echo e(route('archivos.destroy', $archivo->id)); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Detalles expandibles solo en móvil -->
                        <tr class="archivo-detalle d-none d-md-none">
                            <td colspan="5">
                                <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                                    <!-- Columna izquierda: nombre + descripción -->
                                    <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                        <strong>Archivo:</strong>
                                        <?php if($esImagen): ?>
                                        <a href="javascript:void(0);" onclick="verDetallesFoto(
                                         '<?php echo e(asset("archivos_plantel/" . $archivo->ruta_archivo)); ?>',
                                       '<?php echo e(e($archivo->cct)); ?>',
                                       '<?php echo e(e($plantel->nombre_escuela)); ?>',
                                          '<?php echo e(e($archivo->usuario->nombre ?? 'Desconocido')); ?>',
                                       '<?php echo e(e($archivo->descripcion)); ?>',
                                      '<?php echo e(e($archivo->fecha_subido)); ?>'
                                        )" class="text-decoration-none" style="color: inherit; font-weight: normal;">
                                    <i class="<?php echo e($icono); ?> <?php echo e($color); ?>"></i>
                                    <?php echo e($archivo->nombre_archivo_original); ?>

                                    </a>

                                        <?php else: ?>
                                        <a href="<?php echo e(route('archivos-plantel.visualizar', $archivo->id)); ?>" target="_blank" class="text-decoration-none"  style="color: inherit; font-weight: normal;">
                                            <i class="<?php echo e($icono); ?> <?php echo e($color); ?>"></i>
                                            <?php echo e($archivo->nombre_archivo_original); ?>

                                        </a>
                                        <?php endif; ?>
                                        <br>
                                        <strong>Descripción:</strong> <?php echo e($archivo->descripcion); ?>

                                    </div>

                                    <!-- Columna derecha: tipo + subido -->
                                    <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                        <strong>Tipo:</strong> <?php echo e($archivo->tipo_documento); ?><br>
                                        <strong>Subido:</strong> <?php echo e($archivo->fecha_subido); ?>

                                    </div>

                                    <!-- Acciones -->
                                    <div class="w-100 mt-2">
                                        <form action="<?php echo e(route('archivos.destroy', $archivo->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="button" class="btn-custom btn-danger btn-sm"
                                                onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este archivo?', '<?php echo e(route('archivos.destroy', $archivo->id)); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <?php endif; ?>
            </div>
        </div>


        <!--Fotos-->
        <div class="form-section step-section d-none" data-step="5">
            <?php echo $__env->make('planteles.galeria.formulario', ['plantel' => $plantel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('planteles.galeria.imagenes', ['fotos' => $fotos], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('planteles.galeria.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if($errors->any()): ?>
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <!--Mapas-->
        <div class="form-section step-section d-none" data-step="6">
            <div class="container mt-4">
                <h3 class="mb-3">Ubicacion individual del plantel</h3>
                <div id="map" style="height: 500px; border-radius: 8px;"></div>
            </div>
        </div>

        <!--Datos avanzados-->
        <div class="form-section step-section d-none" data-step="7">
            <div class="form-ficha-base mt-4">
                <h3 class="mb-3">Detalles avanzados del plantel</h3>

                <div class="mb-4">
                    <h4><i class=" fas fa-graduation-cap"></i>
                        Escolaridad que se imparte
                    </h4>

                    <?php if($plantel->niveles->isEmpty()): ?>
                    <p>No se registran niveles educativos para este plantel.</p>
                    <?php else: ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $plantel->niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(ucfirst(str_replace('_', ' ', $nivel->nivel))); ?>

                            <span class="badge bg-success">Sí</span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <?php endif; ?>
                </div>

                
                <h4><i class="fas fa-ruler-combined"></i> Superficie del inmueble</h4>
                <?php if($plantel->superficies->where('aplica', true)->isNotEmpty()): ?>
                <ul class="list-group mb-3">
                    <?php $__currentLoopData = $plantel->superficies->where('aplica', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $superficie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $textoSuperficie = match($superficie->rango) {
                    'menos_de_50' => 'Menos de 50 m²',
                    'de_50_a_499' => 'De 50 a 499 m²',
                    'de_500_a_999' => 'De 500 a 999 m²',
                    'de_1000_a_9999' => 'De 1000 a 9999 m²',
                    'de_10000_o_mas' => 'De 10000 m² o más',
                    default => ucfirst(str_replace('_', ' ', $superficie->rango)),
                    };
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($textoSuperficie); ?>

                        <span class="badge bg-info"><i class="fas fa-check"></i></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php else: ?>
                <p class="text-muted">No se registran datos de superficie para este plantel.</p>
                <?php endif; ?>

                
                <h4 class="mt-4"><i class="fas fa-building"></i> Edificios</h4>
                <?php if($plantel->numero_edificios): ?>
                <p><strong>Número de edificios utilizados:</strong> <?php echo e($plantel->numero_edificios); ?></p>
                <?php else: ?>
                <p class="text-muted">No se ha registrado el número de edificios.</p>
                <?php endif; ?>

                
                <h4 class="mt-4"><i class="fas fa-tint"></i> Suministro y almacenamiento de agua</h4>
                <?php if($plantel->agua): ?>
                <ul class="list-group mb-3">
                    <?php $__currentLoopData = [
                    'agua_red_publica' => 'Cuenta con red pública de agua potable',
                    'agua_pozo' => 'Cuenta con suministro de agua en pozo',
                    'agua_cuerpo' => 'Cuenta con suministro de cuerpos de agua',
                    'agua_pipas' => 'Cuenta con suministro de pipas de agua',
                    'agua_otro' => 'Cuenta con otro tipo de suministro de agua',
                    'cisterna' => 'Cuenta con suministro de agua en cisterna',
                    'tinacos' => 'Cuenta con suministro de agua en tinacos',
                    'tanque' => 'Cuenta con suministro de tanque de agua',
                    'almacenamiento_otro' => 'Cuenta con otro tipo de suministro de agua',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($plantel->agua->$campo): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($etiqueta); ?>

                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <li class="list-group-item d-flex justify-content-between">
                        Estado de la red hidráulica
                        <span><?php echo e($plantel->agua->estado_red_hidraulica); ?></span>
                    </li>
                </ul>
                <?php else: ?>
                <p class="text-muted">No hay datos registrados sobre suministro de agua.</p>
                <?php endif; ?>


                
                <h4 class="mt-4"><i class="fas fa-bolt"></i> Energía</h4>
                <?php if($plantel->energia): ?>
                <ul class="list-group mb-3">
                    <?php $__currentLoopData = [
                    'energia_planta' => 'Cuenta con suministro de energía eléctrica en planta generadora de luz',
                    'energia_paneles_solares' => 'Cuenta con suministro de paneles solares con batería',
                    'suministro_energia' => 'Cuenta con suministro de energía eléctrica'
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($plantel->energia->$campo): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($etiqueta); ?>

                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <li class="list-group-item d-flex justify-content-between">
                        Estado de la instalación eléctrica
                        <span><?php echo e($plantel->energia->estado_instalacion_electrica); ?></span>
                    </li>
                </ul>
                <?php else: ?>
                <p class="text-muted">No hay datos registrados sobre energía.</p>
                <?php endif; ?>



                
                <h4 class="mt-4"><i class="fas fa-water"></i> Drenaje</h4>
                <?php if($plantel->drenaje): ?>
                <ul class="list-group mb-3">
                    <?php $__currentLoopData = [
                    'drenaje_publico'=>'Cuenta con drenaje público',
                    'fosa_septica'=>'Cuenta con fosa séptica',
                    'planta_tratamiento'=>'Cuenta con planta de tratamiento',
                    'descarga_otro'=>'Cuenta con otro tipo de descarga',
                    'separacion_aguas'=>'Cuenta con separación de aguas negras y pluviales'
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($plantel->drenaje->$campo): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($etiqueta); ?>

                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php else: ?>
                <p class="text-muted">No hay datos registrados sobre drenaje.</p>
                <?php endif; ?>


                
                <h4 class="mt-4"><i class="fas fa-restroom"></i> Infraestructura sanitaria</h4>
                <?php if($plantel->sanitario): ?>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">Total baños hombres <span><?php echo e($plantel->sanitario->banos_hombres); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Total baños mujeres <span><?php echo e($plantel->sanitario->banos_mujeres); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Total de tazas sanitarias, migitorios y letrinas <span><?php echo e($plantel->sanitario->total_sanitarios); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Total Lavamanos <span><?php echo e($plantel->sanitario->lavamanos); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Total Bebederos <span><?php echo e($plantel->sanitario->tomas_bebederos); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Total baños accesibles para discapacitados <span><?php echo e($plantel->sanitario->banos_discapacitados); ?></span></li>

                    <li class="list-group-item d-flex justify-content-between">Estado de baños <span><?php echo e($plantel->sanitario->estado_banos); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Estado de mingitorios <span><?php echo e($plantel->sanitario->estado_minigitorios); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Estado de lavamanos <span><?php echo e($plantel->sanitario->estado_lavamanos); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Estado de bebederos <span><?php echo e($plantel->sanitario->estado_bebederos); ?></span></li>
                    <li class="list-group-item d-flex justify-content-between">Estado de instalación sanitaria <span><?php echo e($plantel->sanitario->estado_instalacion_sanitaria); ?></span></li>
                </ul>
                <?php else: ?>
                <p class="text-muted">No se han registrado datos sanitarios.</p>
                <?php endif; ?>

                
                <h4 class="mt-4"><i class="fas fa-tools"></i> Obras</h4>
                <?php if($plantel->obras): ?>
                <ul class="list-group mb-3">
                    <?php $__currentLoopData = [
                    'rehabilitacion_realizada'=> 'En los ultimos 5 años en el inmueble se realizarón obras de rehabilitación o mantenimiento mayor',
                    'rehabilitacion_impermeabilizacion'=> 'Obras de rehabilitación en impermeabilización',
                    'rehabilitacion_albanileria' => 'Obras de rehabilitación en albañilería',
                    'rehabilitacion_pintura'=> 'Obras de rahabilitación en pintura general',
                    'rehabilitacion_red_hidraulica'=>'Obras de rehabilitación en red hidráulica',
                    'rehabilitacion_red_sanitaria'=>'Obras de rehabilitación en red sanitaria',
                    'rehabilitacion_estructural'=>'Obras de rehabilitación estructurales',
                    'obras_nuevas'=> 'Obras nuevas en los ultimos cinco años',
                    'construccion_educativa'=> 'Construcción en espacios educativos',
                    'construccion_deportiva'=> 'Construcción en espacios deportivos',
                    'construccion_sanitaria'=> 'Construcciones en espacios sanitarios',
                    'construccion_complementos'=>'Construcción en complementos de instalaciones',
                    'construccion_total'=>'Construcción en todos los espacios del inmueble',
                    'construccion_otro'=>'Construccion de otro tipo'
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($plantel->obras->$campo): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($etiqueta); ?>

                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php else: ?>
                <p class="text-muted">No hay datos registrados sobre obras.</p>
                <?php endif; ?>

                
                <h4 class="mt-4"><i class="fas fa-shield-alt"></i> Infraestructura de seguridad</h4>
                <?php if($plantel->seguridad): ?>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        Programa de protección civil
                        <span><?php echo e($plantel->seguridad->proteccion_civil ? 'Sí' : 'No'); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Barda o cerca perimetral completa
                        <span><?php echo e($plantel->seguridad->barda_completa ? 'Sí' : 'No'); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Infraestructura, software y/o computadoras para personas con discapacidad
                        <span><?php echo e($plantel->seguridad->infraestructura_discapacidad ? 'Sí' : 'No'); ?></span>
                    </li>

                    
                    <li class="list-group-item d-flex justify-content-between">
                        Estado de la barda perimetral
                        <span><?php echo e($plantel->seguridad->estado_barda); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Estado de la cerca perimetral
                        <span><?php echo e($plantel->seguridad->estado_cerco); ?></span>
                    </li>

                    
                    <li class="list-group-item d-flex justify-content-between">
                        Equipo/mobiliario que se cuenta para personas discapacitadas
                        <span><?php echo e($plantel->seguridad->equipo_discapacidad_total); ?></span>
                    </li>
                </ul>
                <?php else: ?>
                <p class="text-muted">No se han registrado datos de seguridad.</p>
                <?php endif; ?>

            </div>
        </div>

        <div class="tab-pane" id="step-8">
            <h4>Intervención del CAPCEE</h4>

            <form action="<?php echo e(route('importar.escuelas')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="archivo" class="form-label">Seleccionar archivo Excel/CSV</label>
                    <input type="file" name="archivo" id="archivo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-import"></i> Importar datos
                </button>
            </form>

            <?php if(session('success')): ?>
                <div class="alert alert-success mt-3">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mt-3">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>


    
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <?php $__env->startPush('scripts'); ?>
    <?php echo $__env->make('planteles.galeria.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        var plantelData = <?php echo json_encode($mapData, 15, 512) ?>;
    </script>

    <script src="<?php echo e(asset('js/mapa_plantel.js')); ?>"></script>

    <?php $__env->stopPush(); ?>





    <!--Script para elegir tipo de documento-->
    <script src="<?php echo e(asset('js/documento_tipo.js')); ?>"></script>

    <!--Script para paginacion de navs o pestañas-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const secciones = document.querySelectorAll('.step-section');
            const pestañas = document.querySelectorAll('.nav-tab');

            function mostrarSeccion(numero) {
                secciones.forEach((seccion, i) => {
                    seccion.classList.toggle('active', i === numero);
                    seccion.classList.toggle('d-none', i !== numero);
                });

                pestañas.forEach((pestana, i) => {
                    pestana.classList.toggle('active', i === numero);
                });

                // Guardar el paso activo
                localStorage.setItem('pasoActivo', numero);
            }

            // Recuperar el paso guardado o usar 0 por defecto
            const pasoGuardado = parseInt(localStorage.getItem('pasoActivo')) || 0;
            mostrarSeccion(pasoGuardado);

            // Asignar eventos a las pestañas
            pestañas.forEach((pestana, i) => {
                pestana.addEventListener('click', () => {
                    mostrarSeccion(i);
                });
            });
        });
    </script>

    <!--Ver foto-->
    <script>
        function verDetallesFoto(src, cct, nombreEscuela, nombreUsuario, descripcion, fechaSubida) {
            document.getElementById('modalFoto').src = src;
            document.getElementById('modalCCT').innerText = cct;
            document.getElementById('modalEscuela').innerText = nombreEscuela;
            document.getElementById('modalUsuario').innerText = nombreUsuario;
            document.getElementById('modalDescripcion').innerText = descripcion;
            document.getElementById('modalFecha').innerText = fechaSubida;

            const modalBootstrap = new bootstrap.Modal(document.getElementById('fotoModal'));
            modalBootstrap.show();
        }

        function activarPantallaCompleta() {
            const img = document.getElementById('modalFoto');
            if (img.requestFullscreen) {
                img.requestFullscreen();
            } else if (img.webkitRequestFullscreen) {
                img.webkitRequestFullscreen();
            } else if (img.msRequestFullscreen) {
                img.msRequestFullscreen();
            }
        }

        document.addEventListener('fullscreenchange', () => {
            const btn = document.querySelector('[onclick="activarPantallaCompleta()"]');
            btn.style.display = document.fullscreenElement ? 'none' : 'block';
        });
    </script>


    <!--Script para confirmar eliminacion-->
    <script>
        const CSRF_TOKEN = "<?php echo e(csrf_token()); ?>";
    </script>
    <script src="<?php echo e(asset('js/modal-confirmacion.js')); ?>"></script>

    <!---Script para menu expandible de archivos-->
    <script src="<?php echo e(asset('js/tabla-expandible-archivos.js')); ?>"></script>

    <!---Script para menu expandible de espacios-->
    <script src="<?php echo e(asset('js/tabla-expandible-espacios.js')); ?>"></script>




    <?php $__env->stopSection(); ?>

    <!--Modal para confirmación-->
    <div id="modalConfirmacion" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h5><i class="fas fa-exclamation-triangle"></i> Confirmación</h5>
            <p id="mensajeConfirmacion">¿Estás seguro de continuar?</p>
            <div class="modal-actions">
                <button id="btnCancelar" class="btn-custom btn-cancelar">Cancelar</button>
                <a id="btnEliminar" class="btn-custom btn-danger">Eliminar</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-contenido">

                
                
                <button id="btnPantallaCompleta" class="btn-custom btn-outline-light" onclick="togglePantallaCompleta()" title="Pantalla completa">
                    <i id="iconPantallaCompleta" class="fas fa-expand"></i>
                </button>

                
                
                <button class="cerrar-modal" onclick="cerrarModal()">✕</button>

                
                <div class="modal-body text-center">
                    <img id="modalFoto" src="" class="img-fluid rounded shadow" style="max-height:80vh; object-fit:contain;">
                    <hr class="bg-secondary">
                    <p><strong>CCT:</strong> <span id="modalCCT"></span></p>
                    <p><strong>Plantel:</strong> <span id="modalEscuela"></span></p>
                    <p><strong>Subido por el usuario:</strong> <span id="modalUsuario"></span></p>
                    <p><strong>Descripción:</strong> <span id="modalDescripcion"></span></p>
                    <p><strong>Fecha de subida:</strong> <span id="modalFecha"></span></p>
                </div>

            </div>
        </div>
    </div>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/planteles/show.blade.php ENDPATH**/ ?>