@extends('layouts.app')

@section('title', 'Editar Protección Civil')

@section('content')
@php
$detalle = $plantel->detalleProteccionCivil;
@endphp
<div class="container mt-4">
    <h4><i class="fas fa-shield-alt"></i> Protección Civil - {{ $plantel->nombre_escuela ?? 'Plantel desconocido' }}</h4>

    <form action="{{ route ('detalle_proteccion_civil.store', $plantel->cct) }}" method="POST" class="mt-3">

        @csrf
        <div class="row">
            <div class="col-md-6 mb-2">
                <label>Programa Interno PC</label>
                <select name="programa_interno_pc" class="form-control">
                    <option value="1" {{ $detalle?->programa_interno_pc ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$detalle?->programa_interno_pc ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="col-md-6 mb-2">
                <label>Fecha del Programa Interno</label>
                <input type="date" name="programa_interno_pc_fecha" class="form-control" value="{{ $detalle?->programa_interno_pc_fecha }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>¿Cuenta con Alarma Sísmica?</label>
                <select name="alarma_sismica" class="form-control">
                    <option value="1" {{ $detalle?->alarma_sismica ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$detalle?->alarma_sismica ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="col-md-6 mb-2">
                <label>¿Está Funcional la Alarma?</label>
                <select name="alarma_sismica_funcional" class="form-control">
                    <option value="1" {{ $detalle?->alarma_sismica_funcional ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$detalle?->alarma_sismica_funcional ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="col-md-6 mb-2">
                <label>Señalética Estado</label>
                <select name="senaletica_estado" id="senaletica_estado" class="form-control" required>
                    @foreach ($estadosProteccionCivil as $valor => $texto)
                    <option value="{{ $valor }}" {{ old('senaletica_estado', $plantel->detalleProteccionCivil->senaletica_estado ?? '') == $valor ? 'selected' : '' }}>
                        {{ $texto }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-2">
                <label>Cantidad de Extintores</label>
                <input type="number" name="extintores_cantidad" class="form-control" value="{{ $detalle?->extintores_cantidad }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>¿Extintores Vigentes?</label>
                <select name="extintores_vigente" class="form-control">
                    <option value="1" {{ $detalle?->extintores_vigente ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$detalle?->extintores_vigente ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="col-md-6 mb-2">
                <label>¿Brigadas Conformadas?</label>
                <select name="brigadas_conformadas" class="form-control">
                    <option value="1" {{ $detalle?->brigadas_conformadas ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$detalle?->brigadas_conformadas ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="cct" value="{{ $plantel->cct }}">

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>

        <a href="{{ route('planteles.show', $plantel->id) }}" class="btn btn-secondary mt-3">
            Cancelar
        </a>
    </form>
</div>
@endsection