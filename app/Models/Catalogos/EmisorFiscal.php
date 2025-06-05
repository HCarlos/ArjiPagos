<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmisorFiscal extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'emisoresfiscales';

    protected $fillable = [
        'id',
        'rfc',
        'razon_social',
        'razon_social_cfdi_40',
        'calle',
        'num_ext',
        'num_int',
        'colonia',
        'localidad',
        'estado',
        'pais',
        'codigo_postal',
        'serie',
        'tipo_comprobante',
        'is_iva',
        'estatus_emisor_fiscal',
        'old_emisorfiscal_id',
    ];

    protected $casts = [
        'is_iva' => 'boolean',
    ];



}
