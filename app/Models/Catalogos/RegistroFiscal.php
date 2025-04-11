<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroFiscal extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'registros_fiscales';

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
        'es_extranjero',
        'searchtext_regfis',
        'old_regfis_id',
        'regimen_fiscal_id',
        'email1',
        'email2',
    ];

    protected $casts = [
        'es_extranjero' => 'boolean',
    ];

    public function scopeSearch($query, $search){
        if (!$search || $search == "" || $search == null) return $query;
        return $query->whereRaw("searchtext_regfis @@ to_tsquery('spanish', ?)", [$search])
            ->orderByRaw("ts_rank(searchtext_regfis, to_tsquery('spanish', ?)) DESC", [$search]);
    }

    function regimen_fiscal(){
        return $this->hasOne(RegimenFiscal::class, 'id', 'regimen_fiscal_id');
    }



}
