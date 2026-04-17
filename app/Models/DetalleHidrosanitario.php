<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class DetalleHidrosanitario extends Model implements Auditable
{
    use AuditableTrait;
    protected $table = 'detalle_hidrosanitario';
    protected $fillable = [
        'cct',
        'fuente_agua',
        'tipo_drenaje',
        'almacenamiento_agua',
        'sanitarios_hombres_wc',
        'sanitarios_hombres_lavabos',
        'sanitarios_mujeres_wc',
        'sanitarios_mujeres_lavabos',
        'observaciones',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }

    public function transformAudit(array $data): array
    {
        $tags = [];

        $campos = [
            'fuente_agua',
            'tipo_drenaje',
            'almacenamiento_agua',
            'sanitarios_hombres_wc',
            'sanitarios_hombres_lavabos',
            'sanitarios_mujeres_wc',
            'sanitarios_mujeres_lavabos',
            'observaciones',
        ];

        foreach ($campos as $campo) {
            if (
                isset($data['old_values'][$campo]) &&
                isset($data['new_values'][$campo]) &&
                $data['old_values'][$campo] != $data['new_values'][$campo]
            ) {
                $tags[] = 'Cambio ' . ucwords(str_replace('_', ' ', $campo));
            }
        }

        if (!empty($tags)) {
            $data['tags'] = json_encode($tags);
        }

        return $data;
    }
}