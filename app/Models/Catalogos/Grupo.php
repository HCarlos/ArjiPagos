<?php

namespace App\Models\Catalogos;

use App\Models\User;
use App\Traits\GrupoAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model{

    use HasFactory, SoftDeletes, GrupoAttributes;
    protected $guard_name = 'web';
    protected $table = 'grupos';

    protected $fillable = [
        'id',
        'clave_grupo','grupo',
        'grupo_oficial', 'grupo_periodo', 'grupo_periodo_ciclo',
        'nivel','grado','grado_pai','is_visible','is_bloqueado','num',
        'is_activo_en_caja', 'is_ver_boleta_interna', 'is_ver_boleta_oficial','is_grupo_pai',
        'status_grupo', 'empresa_id', 'grupo_siguiente_id', 'old_grupo_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'is_visible' => 'boolean',
        'is_bloqueado' => 'boolean',
        'is_activo_en_caja' => 'boolean',
        'is_ver_boleta_interna' => 'boolean',
        'is_ver_boleta_oficial' => 'boolean',
        'is_grupo_pai' => 'boolean',
        'status_grupo' => 'boolean',
    ];

    public function scopeFilter($query, $search){
        return $query->whereRaw('UPPER(unaccent(grupo)) LIKE UPPER(unaccent(?))', ["%{$search}%"])
            ->orWhereRaw('UPPER(unaccent(clave_grupo)) LIKE UPPER(unaccent(?))', ["%{$search}%"]);
    }

    public function nivel(): BelongsTo{
        return $this->belongsTo(Nivel::class,'nivel_id' , 'grupo_id','grupos_niveles');
    }

    public function niveles(): BelongsToMany{

        return $this->belongsToMany(
            Nivel::class,
            'grupos_niveles', // Nombre de la tabla pivote
            'grupo_id',      // Clave foránea del modelo actual en la tabla pivote
            'nivel_id'      // Clave foránea del modelo relacionado en la tabla pivote
        )
        ->withPivot(['ciclo_id', 'is_visible', 'old_grupo_nivel_id', 'old_ciclo_id', 'old_nivel_id', 'old_grupo_id',]);
    }

    public function ciclo(): BelongsTo{
        return $this->belongsTo(Ciclo::class, 'ciclo_id');
    }

    public function ciclos(): BelongsToMany{
        return $this->belongsToMany(
            Ciclo::class,
            'grupos_niveles',
            'grupo_id',
            'ciclo_id'
        )->withPivot( ['is_visible', 'old_grupo_nivel_id', 'old_ciclo_id', 'old_nivel_id', 'old_grupo_id']);
    }

    public function alumno(){
        return $this->belongsTo(User::class);
    }

    public function alumnos(): BelongsToMany{

        return $this->belongsToMany(
            User::class, 'alumnos_grupos',
            'grupo_id',
            'alumno_user_id'
        )
        ->withPivot(['ciclo_id', 'num_lista', 'is_ver_boleta_interna', 'clave_nivel', 'old_alumno_grupo_id', 'old_ciclo_id', 'old_grupo_id', 'old_alumno_user_id']);

    }


}
