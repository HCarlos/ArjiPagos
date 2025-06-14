<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductosYServiciosSAT extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'prodservsat';

    protected $fillable = [
        'id', 'clave', 'descripcion', 'status_prodserv', 'old_prodserv_id', 'empresa_id',
    ];

    protected $appends = ['producto_servicio'];
    public function getProductoServicioAttribute(){
        return $this->clave . ' - ' . $this->descripcion;
    }



}
