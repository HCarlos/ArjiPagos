<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoCat extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'pagos';

    protected $fillable = [
        'nivel_id',
        'emisorfiscal_id',
        'concepto_id',
        'importe',
        'dia_limite',
        'dia_de_pago',
        'tiene_descuento_beca',
        'tiene_descuento',
        'acepta_pagos_diversos',
        'aplica_al_siguiente_nivel',
        'aplica_a',
        'tiene_descuento_por_promocion',
        'es_facturable',
        'se_puede_ver_en_pantalla',
        'orden_prioridad',
        'prodservsat_id',
        'unidadmedidasat_id',
        'tagpagos',
        'estatus_pagos',
        'old_pago_id',
        'empresa_id',
    ];

    protected $casts = [
        'tiene_descuento_beca' => 'boolean',
        'tiene_descuento' => 'boolean',
        'acepta_pagos_diversos' => 'boolean',
        'aplica_al_siguiente_nivel' => 'boolean',
        'aplica_a' => 'boolean',
        'tiene_descuento_por_promocion' => 'boolean',
        'es_facturable' => 'boolean',
        'se_puede_ver_en_pantalla' => 'boolean',
        'tagpagos' => 'string',
        'importe' => 'decimal:4',
    ];


    protected $appends = ['importe_formatted'];
    // Accessor para formatear el importe
    public function getImporteFormattedAttribute(){
        // number_format(valor, decimales, separador_decimal, separador_miles)
        return number_format($this->importe, 2, '.', ',');
    }

    // Si necesitas el valor sin decimales extras (76000.00 en vez de 76000.0000)
    // puedes también añadir un mutador para cuando se guarda el dato.
     public function setImporteAttribute($value){
         $this->attributes['importe'] = round($value, 2); // Redondea a 2 decimales antes de guardar
     }
    public function nivel(){
        return $this->belongsTo(Nivel::class);
    }

    public function niveles(){
        return $this->HasMany(Nivel::class);
    }

    public function concepto(){
        return $this->belongsTo(ConceptoDePago::class);
    }

    public function conceptos(){
        return $this->HasMany(ConceptoDePago::class);
    }

    public function emisorfiscal(){
        return $this->belongsTo(EmisorFiscal::class);
    }

    public function emisoresfiscales(){
        return $this->HasMany(EmisorFiscal::class);
    }

    public function prodservsat(){
        return $this->hasOne(ProductosYServiciosSAT::class);
    }

    public function prodservsats(){
        return $this->HasMany(ProductosYServiciosSAT::class);
    }

    public function unidadmedidasat(){
        return $this->belongsTo(UnidadMedidaSAT::class);
    }

    public function unidadmedidasats(){
        return $this->HasMany(UnidadMedidaSAT::class);
    }




}
