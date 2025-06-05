<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagosCat extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'pagos';

    protected $fillable = [
        'nivel_id',
        'emisorfiscal_id',
        'concepto_id',
        'importe',
        'dia_limite',
        'dia_de_pago',
        'tiene_descuento_beca',
        'tiene_descuento',
        'acepta_pagos_diversos',
        'aplica_al_siguiente_nivel',
        'aplica_a',
        'tiene_descuento_por_promocion',
        'es_facturable',
        'se_puede_ver_en_pantalla',
        'orden_prioridad',
        'prodservsat_id',
        'unidadmedidasat_id',
        'tagpagos',
        'estatus_pagos',
        'old_pago_id',
        'empresa_id',
    ];

    protected $casts = [
        'tiene_descuento_beca' => 'boolean',
        'tiene_descuento' => 'boolean',
        'acepta_pagos_diversos' => 'boolean',
        'aplica_al_siguiente_nivel' => 'boolean',
        'aplica_a' => 'boolean',
        'tiene_descuento_por_promocion' => 'boolean',
        'es_facturable' => 'boolean',
        'se_puede_ver_en_pantalla' => 'boolean',
    ];



}
