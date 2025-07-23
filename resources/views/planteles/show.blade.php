@extends('layouts.app')

@section('title', 'Ver Plantel')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-school me-2"></i> {{ $plantel->nombre_escuela }}
            <small class="text-muted">(CCT: {{ $plantel->cct }})</small>
        </h4>
        <a href="{{ route('planteles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
    </div>

    <ul class="nav nav-tabs mt-4" id="plantelTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="ficha-base-tab" data-bs-toggle="tab" href="#ficha-base" role="tab">Ficha Base</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="espacios-tab" data-bs-toggle="tab" href="#espacios" role="tab">Espacios / Áreas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="servicios-tab" data-bs-toggle="tab" href="#servicios" role="tab">Servicios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="proteccion_civil-tab" data-bs-toggle="tab" href="#proteccion_civil" role="tab">Protección Civil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="archivos-tab" data-bs-toggle="tab" href="#archivos" role="tab ">Archivos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="fotos-tab" data-bs-toggle="tab" href="#fotos" role="tab">Galeria Fotos</a>
        </li>
    </ul>

    <div class="tab-content mt-3" id="plantelTabContent">
        <!-- Ficha Base -->
        <div class="tab-pane fade show active" id="ficha-base" role="tabpanel">
            <h5 class="text-primary">Información General del Plantel</h5>
            <div class="row mt-3">
                <div class="col-md-4"><strong>Nombre Oficial:</strong> {{ $plantel->nombre_escuela }}</div>
                <div class="col-md-4"><strong>Nivel Educativo:</strong> {{ $plantel->nivel_educativo }}</div>
                <div class="col-md-4"><strong>Turno:</strong> {{ $plantel->turno }}</div>
                <div class="col-md-4"><strong>Sostenimiento:</strong> {{ $plantel->sostenimiento }}</div>
            </div>
            <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Editar Ficha Base
            </a>
        </div>

        <!-- Espacios / Áreas -->
        <div class="tab-pane fade" id="espacios" role="tabpanel">
            <h5 class="text-primary">Registrar nuevo espacio</h5>
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
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-plus-circle"></i> Agregar
                    </button>
                </div>
            </form>

            @if ($plantel->espacios->isEmpty())
            <p>No hay espacios registrados para este plantel</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Nombre del Espacio</th>
                            <th>Cantidad</th>
                            <th>Estado de Conservación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plantel->espacios as $espacio)
                        <tr>
                            <td>{{ $espacio->nombre_espacio }}</td>
                            <td>{{ $espacio->cantidad }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', strtolower($espacio->estado_conservacion))) }}</td>

                            <td>
                                <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este espacio?')">
                                        Eliminar
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
        <div class="tab-pane fade" id="servicios" role="tabpanel">
            <div class="row mt-3">
                <div class="col-12 mb-3">
                    <h5>Hidrosanitaria</h5>
                    <div class="row">
                        <div class="col-md-6"><strong>Fuente de Agua:</strong> {{ $hidrosanitario->fuente_agua ?? 'No disponible' }}</div>
                        <div class="col-md-6"><strong>Tipo Drenaje:</strong> {{ $hidrosanitario->tipo_drenaje ?? 'No disponible' }}</div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <h5>Servicios Básicos</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Contrato Electricidad:</strong>
                            {{
                                $servicio?->electricidad_contrato === 1 ? 'Sí' :
                                ($servicio?->electricidad_contrato === 0 ? 'No' : 'No disponible')
                            }}
                        </div>
                        <div class="col-md-4">
                            <strong>Telefonía Fija:</strong>
                            {{
                                $servicio?->telefonia_fija === 1 ? 'Sí' :
                                ($servicio?->telefonia_fija === 0 ? 'No' : 'No disponible')
                            }}
                        </div>
                        <div class="col-md-4">
                            <strong>Acceso a Internet:</strong>
                            {{
                                $servicio?->internet_acceso === 1 ? 'Sí' :
                                ($servicio?->internet_acceso === 0 ? 'No' : 'No disponible')
                            }}
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('infraestructura.editar_completa', $plantel->cct) }}" class="btn btn-primary mt-3">
                <i class="fas fa-edit"></i> Editar Infraestructura Completa
            </a>
        </div>

        <!-- Protección Civil -->
        <div class="tab-pane fade" id="proteccion_civil" role="tabpanel">
            <h5 class="mt-3">Protección Civil</h5>

            @php
            $detalle = $plantel->detalleProteccionCivil;
            @endphp

            @if($detalle)
            <div class="row">
                <div class="col-md-6 mb-2"><strong>CCT:</strong> {{ $detalle->cct }}</div>
                <div class="col-md-6 mb-2"><strong>Programa Interno PC:</strong> {{ $detalle->programa_interno_pc ? 'Sí' : 'No' }}</div>
                <div class="col-md-6 mb-2"><strong>Fecha Programa Interno:</strong> {{ $detalle->programa_interno_pc_fecha }}</div>
                <div class="col-md-6 mb-2"><strong>Alarma Sísmica:</strong> {{ $detalle->alarma_sismica ? 'Sí' : 'No' }}</div>
                <div class="col-md-6 mb-2"><strong>Alarma Funcional:</strong> {{ $detalle->alarma_sismica_funcional ? 'Sí' : 'No' }}</div>
                <div class="col-md-6 mb-2"><strong>Señalética Estado:</strong> {{ ucwords(str_replace('_', ' ', strtolower($detalle->senaletica_estado))) }}</div>
                <div class="col-md-6 mb-2"><strong>Extintores (Cantidad):</strong> {{ $detalle->extintores_cantidad }}</div>
                <div class="col-md-6 mb-2"><strong>Extintores Vigentes:</strong> {{ $detalle->extintores_vigente ? 'Sí' : 'No' }}</div>
                <div class="col-md-6 mb-2"><strong>Brigadas Conformadas:</strong> {{ $detalle->brigadas_conformadas ? 'Sí' : 'No' }}</div>
            </div>
            @else
            <p>No hay información de Protección Civil disponible para este plantel.</p>
            @endif

            <a href="{{ route('planteles.editar_proteccion_civil', $plantel->id) }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Editar Protección Civil
            </a>
        </div>
        <!--Archivos-->
        <div class="tab-pane fade" id="archivos" role="tabpanel">
            <form action="{{route('archivos.store', ['id'=> $plantel->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cct" value="{{$plantel->cct}}">
                <input type="hidden" name="id_plantel" value="{{$plantel->id}}">
                <div class="mb-3">
                    <label for="archivo" class="form-label">Seleccionar archivo</label>
                    <input type="file" class="form-control" name="archivo" required>
                </div>
                <div class="mb-3">
                    <label for="tipo_documento" class="form-label">Tipo documento</label>
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
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Subir Archivo</button>
            </form>
            <table class="table">
                <thread>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo de documento</th>
                        <th>Descripción</th>
                        <th>Subido</th>
                        <th>Acción</th>
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
                                <button type="submit" class="btn btn-danger btn-sm"">
                                    <i class=" fas fa-trash-alt"></i>Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--Fotos-->
        <div class="tab-pane fade" id="fotos" role="tabpanel">
            <h4>Subir foto al plantel(CCT: {{ $plantel -> cct }})</h4>
            @if (session('foto_subida'))
            <div class="alert alert-success">
                Foto subida correctamente:
                <img src="{{ session('foto_subida') }}" alt="Foto subida" class="img-thumbnail mt-2" width="200">
            </div>
            @endif
            <form action="{{ route('galeria.store', $plantel ->cct) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="foto" class="form-label">Seleccionar foto</label>
                    <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Subir Foto</button>
            </form>
            <div class="row">
                @forelse ($fotos as $foto)
                <div class="col-md-3 mb-4 position-relative">
                    <div class="card">
                        <form action="{{ route('galeria.destroy', $foto->id) }}" method="POST" class="position-absolute top-0 end-0 m-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta foto?')">
                                &times;
                            </button>
                        </form>

                        <img src="{{ Storage::url($foto->ruta_foto) }}" class="img-fluid">
                        <div class="card-body">
                            <p class="card-text">{{ $foto->descripcion_foto ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">No hay fotos subidas para este plantel.</p>
                @endforelse

            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tipoSelect = document.getElementById("tipo_documento");
            const otroTipoContainer = document.getElementById("otro_tipo_container");

            tipoSelect.addEventListener("change", function() {
                if (this.value === "Otro") {
                    otroTipoContainer.classList.remove("d-none");
                    document.getElementById("otro_tipo").setAttribute("required", true);
                } else {
                    otroTipoContainer.classList.add("d-none");
                    document.getElementById("otro_tipo").removeAttribute("required");
                }
            });
        });
    </script>

    @endsection