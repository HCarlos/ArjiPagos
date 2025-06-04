<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConceptoDePago extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'conceptosdepagos';

    protected $fillable = [
        'id', 'concepto', 'concepto_unico', 'tag', 'status_concepto', 'empresa_id',
        'old_concepto_id',
    ];


}
