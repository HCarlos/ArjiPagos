<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\PagoCat;
use Illuminate\Foundation\Http\FormRequest;

class PagoCatRequest extends FormRequest{


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool{
        return true;
    }

    public function messages(): array{
        return [
            'importe.required' => 'El importe es obligatorio.',
            'importe.numeric' => 'El importe debe ser un número.',
            'importe.gt' => 'El importe debe ser mayor que cero.',
        ];
    }

    public function rules(){
        return [
            'nivel_id' => ['required','min:1'],
            'emisorfiscal_id' => ['required','min:1'],
            'concepto_id' => ['required','min:1'],
            'importe' => ['required', 'numeric', 'gt:0'],
            'prodservsat_id' => ['required','min:1'],
            'unidadmedidasat_id' => ['required','min:1'],
        ];
    }

    public function managed () {

//        dd($this->all());

        $item = [
            'nivel_id' => $this->nivel_id,
            'emisorfiscal_id' => $this->emisorfiscal_id,
            'concepto_id' => $this->concepto_id,
            'importe' => $this->importe,
            'dia_limite' => $this->dia_limite,
            'dia_de_pago' => $this->dia_de_pago,
            'tiene_descuento_beca' => $this->tiene_descuento_beca,
            'tiene_descuento' =>  $this->tiene_descuento,
            'acepta_pagos_diversos' =>  $this->acepta_pagos_diversos,
            'aplica_al_siguiente_nivel' =>  $this->aplica_al_siguiente_nivel,
            'aplica_a' =>  $this->aplica_a,
            'tiene_descuento_por_promocion' => $this->tiene_descuento_por_promocion,
            'es_facturable' => $this->es_facturable,
            'se_puede_ver_en_pantalla' => $this->se_puede_ver_en_pantalla,
            'orden_prioridad' =>  $this->orden_prioridad,
            'prodservsat_id' =>  $this->prodservsat_id,
            'unidadmedidasat_id' =>  $this->unidadmedidasat_id,
            'tagpagos' =>  $this->tagpagos ?? '',
        ];

        if (  (int)($this->id) <= 0) {
            $PagoCat = PagoCat::create($item);
        }else{
            $PagoCat = PagoCat::find($this->id);
            $PagoCat->update($item);
        }
        return $PagoCat;
    }



}
