@extends('layouts.app')

@section('title', 'Ver Plantel')


@section('content')


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
                        <tr>
                            <td>{{ $espacio->nombre_espacio }}</td>
                            <td>{{ $espacio->cantidad }}</td>
                            <td>
                                <span class="badge status-{{ ucwords(str_replace('_', ' ', strtolower($espacio->estado_conservacion))) }}">
                                    {{($espacio->estado_conservacion)}}
                                </span>
                            </td>


                            <td>
                                <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este espacio?', '{{ route('espacios.destroy', $espacio->id) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>


                                </form>
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
                <div class="data-pair"><label>Extintores Vigentes:</label> {{ $detalle->extintores_vigente ? 'Sí' : 'No' }}</div>
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
            <h5>Subir Nuevo Archivo
            </h5>
            <form action="{{route('archivos.store', ['id'=> $plantel->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cct" value="{{$plantel->cct}}">
                <input type="hidden" name="id_plantel" value="{{$plantel->id}}">
                <div class="row">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Seleccionar archivo</label>
                        <input type="file" class="form-control" name="archivo" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo de documento</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-select" required>
                            <option value="">Seleccione una opcion</option>
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
                <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i>Subir </button>
            </form>
            <div class="table-responsive mt-3">
                @if($archivos->isEmpty())
                <div class="alert alert-info text-center">No hay archivos subidos para este plantel.</div>
                @else
                <table class="table data-table">
                    <thead class="thead-custom">
                        <tr>
                            <th>Archivo</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Subido</th>
                            <th>Acciones</th>
                        </tr>
                        </thread>
                    <tbody>
                        @foreach($archivos as $archivo)
                        <tr>
                            <td>
                                <a href="{{ route('archivos.descargar', $archivo->id) }}" style="text-decoration: none;">
                                    <i class="fas fa-file-pdf text-danger"></i>
                                    {{ $archivo->nombre_archivo_original }}
                                </a>
                            </td>
                            <td>{{$archivo->tipo_documento}}</td>
                            <td>{{$archivo->descripcion}}</td>
                            <td>{{$archivo->fecha_subido}}</td>
                            <td>
                                <form action="{{ route('archivos.destroy', $archivo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este archivo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar este archivo?', '{{ route('archivos.destroy', $archivo->id) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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

            @include ('planteles.galeria.modal')

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
    </div>

    @push('scripts')
    @include('planteles.galeria.scripts')

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

    <!--Script para confirmar eliminacion-->
    <script>
        const CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/modal-confirmacion.js') }}"></script>

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