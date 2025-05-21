<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nivel extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'niveles';

    protected $fillable = [
        'id',
        'clave_nivel','nivel',
        'clave_registro_nivel', 'nivel_oficial', 'nivel_fiscal',
        'prefijo_evaluacion',
        'numero_evaluaciones', 'fecha_actas', 'status_nivel',
        'empresa_id', 'old_nivel_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'clave_nivel' => 'string',
        'nivel' => 'string',
        'clave_registro_nivel' => 'string',
        'nivel_oficial' => 'string',
        'nivel_fiscal' => 'string',
        'prefijo_evaluacion' => 'string',
        'numero_evaluaciones' => 'integer',
        'fecha_actas' => 'string',
        'status_nivel' => 'string',
        'empresa_id' => 'integer',
        'old_nivel_id' => 'integer',
    ];

    protected $appends = ['formatted_nivel'];
    public function getFormattedNivelAttribute(){
        return $this->clave_nivel . ' - ' . $this->nivel;
    }


}
