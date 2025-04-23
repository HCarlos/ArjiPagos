<?php

namespace App\Models\Catalogos;

use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Familia extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'familias';

    protected $fillable = [
        'id',
        'familia',
        'observaciones_control_escolar',
        'observaciones_pagos',
        'convenios',
        'email_pagos',
        'email_facturas',
        'email_control_escolar',
        'email_convenios',
        'old_familia_id',
    ];

    public function registrofiscal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RegistroFiscal::class);
    }
    public function registrosfiscales(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(RegistroFiscal::class, 'familia_registrofiscal', 'familia_id', 'registrofiscal_id')
            ->withPivot('predeterminado');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(User::class, 'familia_user_role', 'familia_id', 'user_id')
            ->withPivot('role_id', 'tutor_id', 'vivecon_id', 'es_menor');
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Role::class, 'familia_user_role', 'familia_id', 'role_id')
            ->withPivot( 'user_id', 'tutor_id', 'vivecon_id', 'es_menor');
    }




}
