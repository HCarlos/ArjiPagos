<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\ConceptoDePago;
use Illuminate\Foundation\Http\FormRequest;

class PagoCatRequest extends FormRequest{


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'concepto' => [
                'min:1',
                'max:10',
                'unique:conceptosdepagos,concepto,'.$this->id
            ],
        ];
    }

    public function managed () {

        $item = [
            'concepto'       => strtoupper(trim($this->concepto)),
            'concepto_unico' => strtoupper(trim($this->concepto_unico)),
            'tag'            => strtoupper(trim($this->tag)),
        ];

        if (  (int)($this->id) <= 0) {
            $ConceptoDePago = ConceptoDePago::create($item);
        }else{
            $ConceptoDePago = ConceptoDePago::find($this->id);
            $ConceptoDePago->update($item);
        }
        return $ConceptoDePago;
    }



}
