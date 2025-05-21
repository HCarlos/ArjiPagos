<?php

namespace App\Models\Catalogos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoNivel extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'grupos_niveles';

    protected $fillable = [
        'id',
        'ciclo_id', 'nivel_id', 'grupo_id',
        'is_visible', 'empresa_id',
        'old_ciclo_id','old_nivel_id','old_grupo_id',
        'old_grupo_nivel_id'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function ciclo(){
        return $this->hasOne(Ciclo::class, 'id', 'ciclo_id');
    }

    public function nivel(){
        return $this->hasOne(Nivel::class, 'id', 'nivel_id');
    }

    public function grupo(){
        return $this->hasOne(Grupo::class, 'id', 'grupo_id');
    }

}
