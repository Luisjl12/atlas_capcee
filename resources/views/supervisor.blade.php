@extends('layouts.app')
<!--Vista del dashboard del supervisor-->
@section('content')
<h1>SUPERVISOR</h1>
<p>Bienvenido, {{ session('nombre_completo') }}</p>
@endsection