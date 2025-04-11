<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegimenFiscal extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'regimenes_fiscales';

    protected $fillable = [
        'id',
        'clave_regimen_fiscal',
        'regimen_fiscal',
        'old_regimen_fiscal_id',
    ];

    protected $appends = ['formatted_regimen_fiscal'];


    public function getFormattedRegimenFiscalAttribute(){

        return $this->clave_regimen_fiscal . ' - ' . $this->regimen_fiscal;

    }


}
