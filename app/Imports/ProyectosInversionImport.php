<?php

namespace App\Imports;

use App\Models\ProyectoInversion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProyectosInversionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return ProyectoInversion::updateOrCreate(
            ['folio_ppi'        => $row['folio_ppi']],
            [
            'municipio'        => $row['municipio'],
            'nombre_proyecto'  => $row['nombre_del_proyecto'],
            'monto_inversion'  => $this->parseDecimal($row['monto_inversion']),
            'inicio'           => $this->parseDate($row['inicio']),
            'termino'          => $this->parseDate($row['termino']),
            'inicio_dif'       => $this->parseDate($row['inicio_dif']),
            'termino_dif'      => $this->parseDate($row['termino_dif']),
            'av_fin_prog'      => $row['av_fin_prog'],
            'av_fin_real'      => $row['av_fin_real'],
            'av_fis_prog'      => $row['av_fis_prog'],
            'av_fis_real'      => $row['av_fis_real'],
            'empresa'          => $row['empresa'],
            'no_contrato'      => $row['no_contrato'],
            'monto_contratado' => $this->parseDecimal($row['monto_contratado']),
            'plazo_ejec_dias'  => $row['plazo_ejec_dias'],
            'estatus_general'  => $row['estatus_general'],
            'estatus_finanzas' => $row['estatus_finanzas'],
            'obs_finanzas'     => $row['obs_finanzas'],
            'estatus_admin'    => $row['estatus_admin'],
            'obs_admin'        => $row['obs_admin'],
            'notificacion'     => $row['notificacion'],
            'usuario_notif'    => $row['usuario_notif'],
            'fecha_notif'      => $this->parseDate($row['fecha_notif']),
            'latitud'  => $this->parseCoordinate($row['latitud']),
            'longitud' => $this->parseCoordinate($row['longitud']),
        ]);
    }

    private function parseDecimal($value)
    {
        $clean = str_replace(['$', ','], '', $value);
        return is_numeric($clean) ? $clean : null;
    }

    private function parseDate($value)
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }
        return $value;
    }

    private function parseCoordinate($value)
    {
        $clean = str_replace(['$', ',', '=', '--'], '', $value);

        if (is_numeric($clean)) {
            $num = floatval($clean);
            if ($num >= -180 && $num <= 180) {
                return $num;
            }
        }
        return null; 
    }


}
