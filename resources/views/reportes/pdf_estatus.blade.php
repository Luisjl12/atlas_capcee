<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte Estatus de Planteles</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background: #e8e8e8;
        }
    </style>
</head>

<body>
    <h2>Reporte de Estatus de Planteles</h2>
    <table>
        <thead>
            <tr>
                <th>CCT</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($planteles as $p)
            <tr>
                <td>{{ $p->cct }}</td>
                <td>{{ $p->nombre_escuela }}</td>
                <td>
                    {{ ($p->nombre_municipio ?? '').', '.($p->nombre_localidad ?? '').', '.$p->domicilio_calle_numero.' '.$p->domicilio_colonia.' CP '.$p->domicilio_cp }}
                </td>
                <td>{{str_replace('_', ' ',  $p->estatus_plantel )}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>