<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\Nivel;
use Illuminate\Foundation\Http\FormRequest;

class NivelRequest extends FormRequest{

    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nivel'                => 'required|string|max:250|unique:niveles,nivel,'.$this->id,'|min:1',
            'clave_nivel'          => 'required|string|max:2|unique:niveles,clave_nivel,'.$this->id,'|min:1',
            'clave_registro_nivel' => 'required|string|max:250|min:1',
        ];
    }

    public function managed () {

        $item = [
            'nivel'                => strtoupper(trim($this->nivel)),
            'clave_nivel'          => strtoupper(trim($this->clave_nivel)),
            'clave_registro_nivel' => strtoupper(trim($this->clave_registro_nivel)),
            'nivel_oficial'        => strtoupper(trim($this->nivel_oficial)),
            'nivel_fiscal'         => strtoupper(trim($this->nivel_fiscal)),
            'prefijo_evaluacion'   => strtoupper(trim($this->prefijo_evaluacion)),
            'numero_evaluaciones'  => strtoupper(trim($this->numero_evaluaciones)),
            'fecha_actas'          => strtoupper(trim($this->fecha_actas)),
        ];

//        dd($item);

        if (  (int)($this->id) <= 0) {
            $Item = Nivel::create($item);
        }else{
            $Item = Nivel::find($this->id);
            $Item->update($item);
        }
        return $Item;
    }




}
