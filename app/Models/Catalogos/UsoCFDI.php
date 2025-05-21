<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsoCFDI extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'usocfdi';

    protected $fillable = [
        'id',
        'clave_usocfdi',
        'usocfdi',
        'old_usocfdi_id',
    ];

    protected $appends = ['formatted_usocfdi'];
    public function getFormattedUsoCFDIAttribute(){

        return $this->clave_usocfdi . ' - ' . $this->usocfdi;

    }



}
