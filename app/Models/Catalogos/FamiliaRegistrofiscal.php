<?php

namespace App\Models\Catalogos;

use App\Models\User\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamiliaRegistrofiscal extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'familia_registrofiscal';

    protected $fillable = [
        'id',
        'familia_id',
        'registrofiscal_id',
        'predeterminado',
    ];

    protected $casts = [
        'predeterminado' => 'boolean',
    ];

    public function familia(){
        return $this->belongsTo(Familia::class);
    }
    public function familias(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Familia::class, 'familia_registrofiscal', 'registrofiscal_id', 'familia_id')
            ->withPivot( 'predeterminado');
    }

    public function registrofiscal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RegistroFiscal::class);
    }
    public function registrosfiscales(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(RegistroFiscal::class, 'familia_registrofiscal', 'familia_id', 'registrofiscal_id')
            ->withPivot('predeterminado');
    }

}
