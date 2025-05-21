<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\Ciclo;
use Illuminate\Foundation\Http\FormRequest;

class CicloRequest extends FormRequest{

    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'ciclo'        => 'required|string|max:250',
            'fecha_inicio' => 'required|date|before:fecha_fin',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
        ];
    }

    public function managed () {

        $predeterminado    = (bool)($this->predeterminado);

        if ($predeterminado) {
            Ciclo::where('predeterminado', true)->update(['predeterminado' => false]);
        }

        $item = [
            'ciclo'             => strtoupper(trim($this->ciclo)),
            'fecha_inicio'      => $this->fecha_inicio ?? now()->format('Y-m-d'),
            'fecha_fin'         => $this->fecha_fin ?? now()->format('Y-m-d'),
            'ciclo_anterior_id' => (int) ($this->ciclo_anterior_id ?? 0),
            'ano_anterior'      => (int) ($this->ano_anterior ?? 0),
            'ano_siguiente'     => (int) ($this->ano_siguiente ?? 0),
            'predeterminado'    => $this->predeterminado ?? false,
        ];

//        dd($item);

        if (  (int)($this->id) <= 0) {
            $Item = Ciclo::create($item);
        }else{
            $Item = Ciclo::find($this->id);
            $Item->update($item);
        }
        return $Item;
    }



}
