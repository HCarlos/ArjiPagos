<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadMedidaSAT extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'unidadmedidasat';

    protected $fillable = [
        'id', 'clave', 'descripcion', 'status_unidadmedida', 'empresa_id',
    ];

    protected $appends = ['unidad_medida'];
    public function getUnidadMedidaAttribute(){
        return $this->clave . ' - ' . $this->descripcion;
    }



}
