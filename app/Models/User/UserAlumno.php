<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserAlumno extends Model{
    use Notifiable, SoftDeletes;

    protected $guard_name = 'web'; // or whatever guard you want to use
    protected $table = 'user_alumno';

    protected $fillable = [
        'id','user_id',
        'matricula_interna','matricula_oficial','email_alumno',
        'enfermedades','reacciones_alergicas','tipo_sangre',
        'beca_sep','beca_arji','beca_sp','beca_bach',
        'es_baja','motivo_baja',
        'fecha_ingreso','observaciones',
    ];
    protected $casts = [
        'es_baja' => 'boolean',
        'fecha_ingreso' => 'date:Y-m-d',
    ];


    protected $hidden = ['deleted_at','created_at','updated_at'];


}
