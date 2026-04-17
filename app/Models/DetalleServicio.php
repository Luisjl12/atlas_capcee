<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class DetalleServicio extends Model implements Auditable
{
    use AuditableTrait;

    protected $table = 'detalle_servicios';
    protected $fillable = [
        'cct',
        'electricidad_contrato',
        'telefonia_fija',
        'internet_acceso',
        'gas_tipo',
        'internet_tipo',
        'observaciones',
        'tiene_computadoras',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }

    public function transformAudit(array $data): array
    {
        $tags = [];

        $campos = [
            'electricidad_contrato',
            'telefonia_fija',
            'internet_acceso',
            'gas_tipo',
            'internet_tipo',
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
