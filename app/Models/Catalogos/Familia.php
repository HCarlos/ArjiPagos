<?php

namespace App\Models\Catalogos;

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



}
