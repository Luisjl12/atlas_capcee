@extends('layouts.app')

@section('title', 'Ver Plantel')


@section('content')
@php
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
@endphp



<div class="container mt-4">
    <div class="card-header bg-white border-bottom mb-4">
        <a href="{{ route('planteles.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-0">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-school me-2"></i> {{ $plantel->nombre_escuela }}
                <small class="text-muted">(CCT: {{ $plantel->cct }})</small>
            </h4>
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
        <span class="nav-tab" data-step="7">Detalles avanzados del plantel</span>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
    @endif


    <!-- Ficha Base -->
    <div class="form-ficha-base">

        <div class="form-section step-section" data-step="0">

            <h4>Información General del Plantel</h4>
            <div class="data-grid">
                <div class="data-pair"><label>Nombre Oficial:</label><span> {{ $plantel->nombre_escuela }}</span></div>
                <div class="data-pair"><label>Nivel Educativo:</label><span> {{ $plantel->nivel_educativo }}</span></div>
                <div class="data-pair"><label>Turno:</label><span> {{ $plantel->turno }}</span></div>
                <div class="data-pair"><label>Sostenimiento:</label><span> {{ $plantel->sostenimiento }}</span></div>
            </div>
            <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-primary mt-3">
                <i class=" fas fa-edit"></i> Editar Ficha Base
            </a>
        </div>


        <!-- Espacios / Áreas -->
        <div class="form-section step-section d-none" data-step="1">
            <h4>Inventario de Espacios Físicos</h4>
            <h5>Agregar nuevo espacio</h5>
            <form action="{{ route('espacios.store') }}" method="POST" class="row g-3 mb-4">
                @csrf
                <input type="hidden" name="cct" value="{{ $plantel->cct }}">

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
                        @foreach ($estadosConservacion as $estado)
                        <option value="{{$estado}}">{{ ucwords(str_replace('_', ' ', strtolower($estado)))}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
            </form>

            @if ($plantel->espacios->isEmpty())
            <div class="alert alert-info text-center">No hay espcacios registrados.</div>
            @else
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
                        @foreach ($plantel->espacios as $espacio)

                        <!-- Fila principal visible solo en móvil -->
                        <tr class="espacio-row d-table-row d-md-none">
                            <td colspan="4" class="espacio-nombre position-relative" style="cursor: pointer;">
                                <div>
                                    <i class="fas fa-door-open text-primary me-2"></i>
                                    {{ $espacio->nombre_espacio }}
                                </div>
                                <div class="toggle-icon position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </td>
                        </tr>


                        <!-- Fila completa visible solo en escritorio -->
                        <tr class="d-none d-md-table-row">
                            <td>{{ $espacio->nombre_espacio }}</td>
                            <td>{{ $espacio->cantidad }}</td>
                            <td>
                                <span class="badge status-{{ ucwords(str_replace('_', ' ', strtolower($espacio->estado_conservacion))) }}">
                                    {{ $espacio->estado_conservacion }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este espacio?', '{{ route('espacios.destroy', $espacio->id) }}')">
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
                                        <strong>Cantidad:</strong> {{ $espacio->cantidad }}<br>
                                        <strong>Estado:</strong>
                                        <span class="badge status-{{ ucwords(str_replace('_', ' ', strtolower($espacio->estado_conservacion))) }}">
                                            {{ $espacio->estado_conservacion }}
                                        </span>
                                    </div>
                                    <div class="w-100 mt-2">
                                        <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este espacio?', '{{ route('espacios.destroy', $espacio->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @endif
        </div>


        <!-- Servicios -->
        <div class="form-section step-section d-none" data-step="2">
            <h4>Infraestructura y Servicios</h4>
            <div class="row mt-3">
                <div class="col-12 mb-3">
                    <h5><i class="fas fa-faucet" style="color:var(--color-info);"></i> Hidrosanitaria</h5>
                    <div class="data-grid">
                        <div class="data-pair"><label>Fuente de Agua:</label><span> {{ $hidrosanitario->fuente_agua ?? 'No disponible' }}</span></div>
                        <div class="data-pair"><label>Tipo de Drenaje:</label><span> {{ $hidrosanitario->tipo_drenaje ?? 'No disponible' }}</span></div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <h5><i class="fas fa-bolt" style="color:var(--color-amarillo-primario);"></i>Servicios Básicos</h5>
                    <div class="data-grid">
                        <div class="data-pair">
                            <label>Contrato Electricidad:</label>
                            {{
                                $servicio?->electricidad_contrato === 1 ? 'Sí' :
                                ($servicio?->electricidad_contrato === 0 ? 'No' : 'No disponible')
                            }}
                        </div>
                        <div class="data-pair">
                            <label>Telefonía Fija:</label>
                            {{
                                $servicio?->telefonia_fija === 1 ? 'Sí' :
                                ($servicio?->telefonia_fija === 0 ? 'No' : 'No disponible')
                            }}
                        </div>
                        <div class="data-pair">
                            <label>Acceso a Internet:</label>
                            {{
                                $servicio?->internet_acceso === 1 ? 'Sí' :
                                ($servicio?->internet_acceso === 0 ? 'No' : 'No disponible')
                            }}
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('infraestructura.editar_completa', $plantel->cct) }}" class="btn btn-primary mt-3">
                <i class="fas fa-edit"></i> Editar Infraestructura y Servicios
            </a>
        </div>


        <!-- Protección Civil -->

        <div class="form-section step-section d-none" data-step="3">
            <h4>Protección Civil</h4>

            @php
            $detalle = $plantel->detalleProteccionCivil;
            @endphp

            @if($detalle)
            <div class="data-grid">

                <div class="data-pair"><label>Programa Interno PC:</label> {{ $detalle->programa_interno_pc ? 'Sí' : 'No' }}</div>
                <div class="data-pair"><label>Fecha Programa Interno:</label> {{ $detalle->programa_interno_pc_fecha }}</div>
                <div class="data-pair"><label>Alarma Sísmica:</label> {{ $detalle->alarma_sismica ? 'Sí' : 'No' }}</div>
                <div class="data-pair"><label>Alarma Funcional:</label> {{ $detalle->alarma_sismica_funcional ? 'Sí' : 'No' }}</div>
                <div class="data-pair"><label>Señalética Estado:</label> {{ ucwords(str_replace('_', ' ', strtolower($detalle->senaletica_estado))) }}</div>
                <div class="data-pair"><label># Extintores:</label> {{ $detalle->extintores_cantidad }}</div>
                <div class="data-pair"><label>Extintores Vigentes:</label> {{ $detalle->extintores_vigentes  }}</div>
                <div class="data-pair"><label>Brigadas Conformadas:</label> {{ $detalle->brigadas_conformadas ? 'Sí' : 'No' }}</div>
            </div>
            @else
            <p>No hay información de Protección Civil disponible para este plantel.</p>
            @endif

            <a href="{{ route('planteles.editar_proteccion_civil', $plantel->id) }}" class="btn btn-primary mt-3">
                <i class="fas fa-edit"></i> Editar Protección Civil
            </a>
        </div>

        <!--Archivos-->
        <div class="form-section step-section d-none" data-step="4">
            <h4>Gestor de Archivos</h4>
            <h5>Subir Nuevo Archivo</h5>

            <form action="{{ route('archivos.store', ['id' => $plantel->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cct" value="{{ $plantel->cct }}">
                <input type="hidden" name="id_plantel" value="{{ $plantel->id }}">

                <div class="row">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Seleccionar archivo</label>
                        <input type="file" class="form-control" name="archivo" required>
                    </div>
                    <div class="mb-3">
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

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Subir
                </button>
            </form>

            <div class="table-responsive mt-3">
                @if($archivos->isEmpty())
                <div class="alert alert-info text-center">No hay archivos subidos para este plantel.</div>
                @else
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
                        @foreach($archivos as $archivo)
                        @php
                        $esImagen = Str::startsWith($archivo->mime_type, 'image/');
                        [$icono, $color] = obtenerIconoArchivo($archivo->nombre_archivo_original);
                        @endphp

                        <!-- Fila principal visible solo en móvil -->
                        <tr class="archivo-row d-table-row d-md-none">
                            <td colspan="5" class="archivo-nombre position-relative" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($esImagen)
                                        <a href="javascript:void(0);" onclick="verDetallesFoto(
                                     '{{ e(Storage::url($archivo->ruta_archivo)) }}',
                                     '{{ e($archivo->cct) }}',
                                     '{{ e($plantel->nombre_escuela) }}',
                                     '{{ e($archivo->usuario->nombre ?? 'Desconocido') }}',
                                     '{{ e($archivo->descripcion) }}',
                                     '{{ e($archivo->fecha_subido) }}'
                                      )" style="text-decoration: none;">
                                            <i class="{{ $icono }} {{ $color }} me-2"></i>
                                            {{ $archivo->nombre_archivo_original }}
                                        </a>
                                        @else
                                        <a href="{{ route('archivos-plantel.visualizar', $archivo->id) }}" target="_blank">
                                            <i class="{{ $icono }} {{ $color }}"></i>
                                            {{ $archivo->nombre_archivo_original }}
                                        </a>
                                        @endif
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
                                @if($esImagen)
                                <a href="javascript:void(0);" onclick="verDetallesFoto(
                            '{{ e(Storage::url($archivo->ruta_archivo)) }}',
                            '{{ e($archivo->cct) }}',
                            '{{ e($plantel->nombre_escuela) }}',
                            '{{ e($archivo->usuario->nombre ?? 'Desconocido') }}',
                            '{{ e($archivo->descripcion) }}',
                            '{{ e($archivo->fecha_subido) }}'
                        )" style="text-decoration: none;">
                                    <i class="{{ $icono }} {{ $color }}"></i>
                                    {{ $archivo->nombre_archivo_original }}
                                </a>
                                @else
                                <a href="{{ route('archivos-plantel.visualizar', $archivo->id) }}" target="_blank" style="text-decoration: none;">
                                    <i class="{{ $icono }} {{ $color }}"></i>
                                    {{ $archivo->nombre_archivo_original }}
                                </a>
                                @endif
                            </td>
                            <td>{{ $archivo->tipo_documento }}</td>
                            <td>{{ $archivo->descripcion }}</td>
                            <td>{{ $archivo->fecha_subido }}</td>
                            <td>
                                <form action="{{ route('archivos.destroy', $archivo->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este archivo?', '{{ route('archivos.destroy', $archivo->id) }}')">
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
                                        @if($esImagen)
                                        <a href="javascript:void(0);" onclick="verDetallesFoto(
                                      '{{ e(Storage::url($archivo->ruta_archivo)) }}',
                                      '{{ e($archivo->cct) }}',
                                      '{{ e($plantel->nombre_escuela) }}',
                                      '{{ e($archivo->usuario->nombre ?? 'Desconocido') }}',
                                      '{{ e($archivo->descripcion) }}',
                                      '{{ e($archivo->fecha_subido) }}'
                                        )" class="text-decoration-none">
                                            <i class="{{ $icono }} {{ $color }}"></i>
                                            {{ $archivo->nombre_archivo_original }}
                                        </a>
                                        @else
                                        <a href="{{ route('archivos-plantel.visualizar', $archivo->id) }}" target="_blank" class="text-decoration-none">
                                            <i class="{{ $icono }} {{ $color }}"></i>
                                            {{ $archivo->nombre_archivo_original }}
                                        </a>
                                        @endif
                                        <br>
                                        <strong>Descripción:</strong> {{ $archivo->descripcion }}
                                    </div>

                                    <!-- Columna derecha: tipo + subido -->
                                    <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                        <strong>Tipo:</strong> {{ $archivo->tipo_documento }}<br>
                                        <strong>Subido:</strong> {{ $archivo->fecha_subido }}
                                    </div>

                                    <!-- Acciones -->
                                    <div class="w-100 mt-2">
                                        <form action="{{ route('archivos.destroy', $archivo->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este archivo?', '{{ route('archivos.destroy', $archivo->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @endif
            </div>
        </div>


        <!--Fotos-->
        <div class="form-section step-section d-none" data-step="5">
            @include('planteles.galeria.formulario', ['plantel' => $plantel])
            @include('planteles.galeria.imagenes', ['fotos' => $fotos])
            @include('planteles.galeria.modal')

            @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
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
                        Nivel educativo
                    </h4>

                    @if($plantel->niveles->isEmpty())
                    <p>No se registran niveles educativos para este plantel.</p>
                    @else
                    <ul class="list-group">
                        @foreach($plantel->niveles->where('imparte', true) as $nivel)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst(str_replace('_', ' ', $nivel->nivel)) }}
                            <span class="badge bg-success">Sí</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                {{-- Superficie --}}
                <h4><i class="fas fa-ruler-combined"></i> Superficie del inmueble</h4>
                @if($plantel->superficies->where('aplica', true)->isNotEmpty())
                <ul class="list-group mb-3">
                    @foreach($plantel->superficies->where('aplica', true) as $superficie)
                    @php
                    $textoSuperficie = match($superficie->rango) {
                    'menos_de_50' => 'Menos de 50 m²',
                    'de_50_a_499' => 'De 50 a 499 m²',
                    'de_500_a_999' => 'De 500 a 999 m²',
                    'de_1000_a_9999' => 'De 1000 a 9999 m²',
                    'de_10000_o_mas' => 'De 10000 m² o más',
                    default => ucfirst(str_replace('_', ' ', $superficie->rango)),
                    };
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $textoSuperficie }}
                        <span class="badge bg-info"><i class="fas fa-check"></i></span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No se registran datos de superficie para este plantel.</p>
                @endif

                {{-- Número de edificios --}}
                <h4 class="mt-4"><i class="fas fa-building"></i> Edificios</h4>
                @if($plantel->numero_edificios)
                <p><strong>Número de edificios utilizados:</strong> {{ $plantel->numero_edificios }}</p>
                @else
                <p class="text-muted">No se ha registrado el número de edificios.</p>
                @endif

                {{-- Agua --}}
                <h4 class="mt-4"><i class="fas fa-tint"></i> Suministro y almacenamiento de agua</h4>
                @if($plantel->agua)
                <ul class="list-group mb-3">
                    @foreach([
                    'agua_red_publica' => 'Red pública',
                    'agua_pozo' => 'Pozo',
                    'agua_cuerpo' => 'Cuerpos de agua',
                    'agua_pipas' => 'Pipas',
                    'agua_otro' => 'Otro tipo de suministro',
                    'cisterna' => 'Cisterna',
                    'tinacos' => 'Tinacos',
                    'tanque' => 'Tanque',
                    'almacenamiento_otro' => 'Otro tipo de almacenamiento',
                    ] as $campo => $etiqueta)
                    @if($plantel->agua->$campo)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $etiqueta }}
                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No hay datos registrados sobre suministro de agua.</p>
                @endif


                {{-- Energía --}}
                <h4 class="mt-4"><i class="fas fa-bolt"></i> Energía</h4>
                @if($plantel->energia)
                <ul class="list-group mb-3">
                    @foreach([
                    'energia_red_contrato'=> 'Cuenta con suministro de energía eléctrica en red pública',
                    'energia_red_sin_contrato'=> 'No cuenta con suministro de energía eléctrica',
                    'energia_planta' => 'Planta generadora',
                    'energia_paneles_solares'=> 'Paneles solares',
                    'sin_energia'=>'No cuenta con energía eléctrica',
                    'gas_natural'=>'Gas natural',
                    'gas_estacionario'=>'Gas estacionario',
                    'gas_cilindro'=> 'Gas en cilindro',
                    'sin_gas'=> 'No cuenta con suministro de gas'
                    ] as $campo => $etiqueta)
                    @if($plantel->energia->$campo)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $etiqueta }}
                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No hay datos registrados sobre energía.</p>
                @endif



                {{-- Drenaje --}}
                <h4 class="mt-4"><i class="fas fa-water"></i> Drenaje</h4>
                @if($plantel->drenaje)
                <ul class="list-group mb-3">
                    @foreach([
                    'drenaje_publico'=>'Drenaje público',
                    'fosa_septica'=>'Fosa séptica',
                    'planta_tratamiento'=>'Planta de tratamiento',
                    'descarga_otro'=>'Otro tipo de descarga',
                    'separacion_aguas'=>'Separación de aguas negras y pluviales'
                    ] as $campo => $etiqueta)
                    @if($plantel->drenaje->$campo)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $etiqueta }}
                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No hay datos registrados sobre drenaje.</p>
                @endif


                {{-- Sanitarios --}}
                <h4 class="mt-4"><i class="fas fa-restroom"></i> Infraestructura sanitaria</h4>
                @if($plantel->sanitario)
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">Baños hombres <span>{{ $plantel->sanitario->banos_hombres }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Baños mujeres <span>{{ $plantel->sanitario->banos_mujeres }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Baños mixtos <span>{{ $plantel->sanitario->banos_mixtos }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Total sanitarios <span>{{ $plantel->sanitario->total_sanitarios }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Sanitarios ambos <span>{{ $plantel->sanitario->sanitarios_ambos }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Lavamanos <span>{{ $plantel->sanitario->lavamanos }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Bebederos <span>{{ $plantel->sanitario->tomas_bebederos }}</span></li>
                    <li class="list-group-item d-flex justify-content-between">Baños accesibles para discapacitados <span>{{ $plantel->sanitario->banos_discapacitados }}</span></li>
                </ul>
                @else
                <p class="text-muted">No se han registrado datos sanitarios.</p>
                @endif

                {{-- Obras --}}
                <h4 class="mt-4"><i class="fas fa-tools"></i> Obras</h4>
                @if($plantel->obras)
                <ul class="list-group mb-3">
                    @foreach([
                    'rehabilitacion_realizada'=> 'Obras de rehabilitación en últimos 5 años',
                    'rehabilitacion_impermeabilizacion'=> 'Impermeabilización',
                    'rehabilitacion_albanileria' => 'Albañilería',
                    'rehabilitacion_pintura'=> 'Pintura general',
                    'rehabilitacion_red_hidraulica'=>'Red hidráulica',
                    'rehabilitacion_red_sanitaria'=>'Red sanitaria',
                    'rehabilitacion_estructural'=>'Obras estructurales',
                    'obras_nuevas'=> 'Obras nuevas',
                    'construccion_educativa'=> 'Espacios educativos',
                    'construccion_deportiva'=> 'Espacios deportivos',
                    'construccion_sanitaria'=> 'Construcciones sanitarias',
                    'construccion_complementos'=>'Complementos instalaciones',
                    'construccion_total'=>'Construcción total',
                    'construccion_otro'=>'Otro tipo de construcción'
                    ] as $campo => $etiqueta)
                    @if($plantel->obras->$campo)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $etiqueta }}
                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No hay datos registrados sobre obras.</p>
                @endif

                {{-- Seguridad --}}
                <h4 class="mt-4"><i class="fas fa-shield-alt"></i> Seguridad</h4>
                @if($plantel->seguridad)
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        Equipo/mobiliario discapacidad
                        <span>{{ $plantel->seguridad->equipo_discapacidad_total }}</span>
                    </li>
                    @foreach([
                    'proteccion_civil' => 'Programa de protección civil',
                    'barda_completa' => 'Barda completa',
                    'barda_incompleta'=> 'Barda incompleta',
                    'infraestructura_discapacidad'=>'Infraestructura para discapacitados',
                    'sin_infraestructura_discapacidad'=>'Sin infraestructura para discapacitados',
                    ] as $campo => $etiqueta)
                    @if($plantel->seguridad->$campo)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $etiqueta }}
                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No hay datos registrados sobre seguridad.</p>
                @endif

            </div>
        </div>
    </div>


    {{-- Estilos de Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    {{-- Script de Leaflet --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @push('scripts')
    @include('planteles.galeria.scripts')

    <script>
        var plantelData = @json($mapData);
    </script>

    <script src="{{ asset('js/mapa_plantel.js') }}"></script>

    @endpush





    <!--Script para elegir tipo de documento-->
    <script src="{{ asset('js/documento_tipo.js') }}"></script>

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
        const CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/modal-confirmacion.js') }}"></script>

    <!---Script para menu expandible de archivos-->
    <script src="{{ asset('js/tabla-expandible-archivos.js')}}"></script>

    <!---Script para menu expandible de espacios-->
    <script src="{{ asset('js/tabla-expandible-espacios.js')}}"></script>




    @endsection

    <!--Modal para confirmación-->
    <div id="modalConfirmacion" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirmación</h3>
            <p id="mensajeConfirmacion">¿Estás seguro de continuar?</p>
            <div class="modal-actions">
                <button id="btnCancelar" class="btn-cancelar">Cancelar</button>
                <a id="btnEliminar" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-contenido">

                {{-- Encabezado con botones --}}
                {{-- Botón pantalla completa a la izquierda --}}
                <button id="btnPantallaCompleta" class="btn btn-outline-light" onclick="togglePantallaCompleta()" title="Pantalla completa">
                    <i id="iconPantallaCompleta" class="fas fa-expand"></i>
                </button>

                {{-- Botón cerrar a la derecha --}}
                {{-- Botón cerrar personalizado --}}
                <button class="cerrar-modal" onclick="cerrarModal()">✕</button>

                {{-- Cuerpo del modal --}}
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

</div>