<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Planteles por Municipio</title>
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
            padding: 5px;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Reporte de Planteles por Municipio</h2>

    <table>
        <thead>
            <tr>
                <th>Municipio</th>
                <th>Localidad</th>
                <th>Total</th>
                <th>Nombres de los Planteles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $fila)
            <tr>
                <td>{{ $fila->municipio }}</td>
                <td>{{ $fila->localidad }}</td>
                <td>{{ $fila->total_planteles }}</td>
                <td>
                    @foreach(explode(',', $fila->nombres_planteles) as $np)
                    • {{ trim($np) }} <br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>TOTAL GENERAL</strong></td>
                <td colspan="2"><strong>{{ $totalGeneral }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>