<!--Vista del dashboard del director-->
@extends('layouts.app')
@section('content')
<h1>DIRECTOR</h1>
<p>Bienvenido, {{ session('nombre_completo') }}</p>
@endsection