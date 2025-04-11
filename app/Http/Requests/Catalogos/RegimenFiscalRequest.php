<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\RegimenFiscal;
use Illuminate\Foundation\Http\FormRequest;

class RegimenFiscalRequest extends FormRequest
{
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
            'clave_regimen_fiscal' => [
                'min:3',
                'max:3',
                'unique:regimenes_fiscales,clave_regimen_fiscal,'.$this->id
            ],
            'regimen_fiscal' => 'required|string|max:250',
        ];
    }

    public function managed () {

        $item = [
            'clave_regimen_fiscal' => strtoupper(trim($this->clave_regimen_fiscal)),
            'regimen_fiscal'       => strtoupper(trim($this->regimen_fiscal)),
        ];

        if (  (int)($this->id) <= 0) {
            $RegFis = RegimenFiscal::create($item);
        }else{
            $RegFis = RegimenFiscal::find($this->id);
            $RegFis->update($item);
        }
        return $RegFis;
    }


}
