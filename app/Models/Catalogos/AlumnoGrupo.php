<?php

namespace App\Models\Catalogos;

use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumnoGrupo extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'alumnos_grupos';

    protected $fillable = [
        'id',
        'ciclo_id','grupo_id', 'alumno_user_id',
        'num_lista', 'is_ver_boleta_interna', 'clave_nivel','old_alumno_grupo_id','old_ciclo_id','old_grupo_id', 'old_alumno_user_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'ciclo_id' => 'integer',
        'grupo_id' => 'integer',
        'alumno_user_id' => 'integer',
        'num_lista' => 'integer',
        'is_ver_boleta_interna' => 'boolean',
        'clave_nivel' => 'string',
    ];


    public function ciclo(){
        return $this->hasOne(Ciclo::class, 'id', 'ciclo_id');
    }
    public function ciclos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Ciclo::class, 'alumnos_grupos', 'grupo_id', 'ciclo_id')
            ->withPivot( ['num_lista', 'is_ver_boleta_interna', 'clave_nivel','old_alumno_grupo_id','old_ciclo_id','old_grupo_id', 'old_alumno_user_id']);
    }

    public function alumno(){
        return $this->hasOne(User::class, 'id', 'alumno_user_id');
    }

    public function alumnos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(User::class, 'alumnos_grupos', 'alumno_user_id', 'grupo_id')
            ->withPivot( ['ciclo_id','alumno_user_id','num_lista', 'is_ver_boleta_interna', 'clave_nivel','old_alumno_grupo_id','old_ciclo_id','old_grupo_id', 'old_alumno_user_id']);
    }

    public function grupo(){
        return $this->hasOne(Grupo::class, 'id', 'grupo_id');
    }
    public function grupos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Grupo::class, 'alumnos_grupos', 'user_id', 'grupo_id')
            ->withPivot( ['num_lista', 'is_ver_boleta_interna', 'clave_nivel','old_alumno_grupo_id','old_ciclo_id','old_grupo_id', 'old_alumno_user_id']);
    }




}
