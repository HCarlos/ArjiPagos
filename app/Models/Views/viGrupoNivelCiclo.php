<?php

namespace App\Models\Views;

use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Grupo;
use App\Models\Catalogos\Nivel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class viGrupoNivelCiclo extends Model{

    use HasFactory;

    protected $table = '_vi_grupo_niveles_ciclos';
    protected $guarded = [];


    public function scopeFilter($query, $search){
        return $query->whereRaw('UPPER(unaccent(grupo)) LIKE UPPER(unaccent(?))', ["%{$search}%"])
            ->orWhereRaw('UPPER(unaccent(clave_grupo)) LIKE UPPER(unaccent(?))', ["%{$search}%"]);
    }


    // Relaciones (si necesitas acceder a ellas desde la vista)
    public function ciclo_relacionado(){
        return $this->belongsTo(Ciclo::class, 'ciclo_id');
    }

    public function nivel_relacionado(){
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }

    public function grupo_relacionado(){
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    // Bloquear operaciones de escritura
    public function save(array $options = []) {}
    public function update(array $attributes = [], array $options = []) {}
    public function delete() {}

}
