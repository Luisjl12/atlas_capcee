<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Infraestructura</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Reporte de Infraestructura</h2>
    <table>
        <thead>
            <tr>
                <th>CCT</th>
                <th>Nombre de la Escuela</th>
                <th>Fuente de Agua</th>
                <th>Tipo de Drenaje</th>
                <th>Contrato de Electricidad</th>
                <th>Telefonía Fija</th>
                <th>Acceso a Internet</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($infraestructura as $item)
            <tr>
                <td>{{ $item->cct }}</td>
                <td>{{ $item->nombre_escuela }}</td>
                <td>{{ $item->fuente_agua ?? 'No registrado' }}</td>
                <td>{{ $item->tipo_drenaje ?? 'No registrado' }}</td>
                <td>{{ $item->electricidad_contrato ? 'Sí' : 'No' }}</td>
                <td>{{ $item->telefonia_fija ? 'Sí' : 'No' }}</td>
                <td>{{ $item->internet_acceso ? 'Sí' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>