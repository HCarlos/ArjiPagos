<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciclo extends Model{
    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'ciclos';

    protected $fillable = [
        'id',
        'ciclo',
        'fecha_inicio:date[Y-m-d]', 'fecha_fin:date[Y-m-d]',
        'predeterminado',
        'ciclo_anterior_id', 'ano_anterior', 'ano_siguiente',
        'empresa_id', 'status_ciclo', 'old_ciclo_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'ciclo' => 'string',
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_fin' => 'date:Y-m-d',
        'predeterminado' => 'boolean',
        'ciclo_anterior_id' => 'integer',
        'ano_anterior' => 'string',
        'ano_siguiente' => 'string',
        'empresa_id' => 'integer',
        'status_ciclo' => 'string',
        'old_ciclo_id' => 'integer',
    ];

    protected $appends = ['formatted_ciclo'];
    public function getFormattedCicloAttribute(){
        return $this->ciclo . ' - ' . $this->fecha_inicio . ' - ' . $this->fecha_fin;
    }


    public function grupos(): BelongsToMany{
        return $this->belongsToMany(Grupo::class, 'alumnos_grupos', 'ciclo_id', 'grupo_id');
    }

}
