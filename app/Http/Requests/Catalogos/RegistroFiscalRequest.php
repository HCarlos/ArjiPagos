<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\RegistroFiscal;
use Illuminate\Foundation\Http\FormRequest;

class RegistroFiscalRequest extends FormRequest
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
            'rfc' => [
                'min:12',
                'max:20',
                'unique:registros_fiscales,rfc,'.$this->id
            ],
            'razon_social' => 'required|string|max:250',
            'razon_social_cfdi_40' => 'required|string|max:250',
            'calle' => 'required|string|max:250',
            'num_ext' => 'required|string|max:25',
            'colonia' => 'required|string|max:250',
            'localidad' => 'required|string|max:250',
            'estado' => 'required|string|max:30',
            'codigo_postal' => 'required|string|max:10',
            'regimen_fiscal_id' => 'required|min:1',
        ];
    }

    public function managed () {

        $item = [
            'rfc'                  => strtoupper(trim($this->rfc)),
            'razon_social'         => strtoupper(trim($this->razon_social)),
            'razon_social_cfdi_40' => strtoupper(trim($this->razon_social_cfdi_40)),
            'calle'                => strtoupper(trim($this->calle)),
            'num_ext'              => strtoupper(trim($this->num_ext)),
            'num_int'              => strtoupper(trim($this->num_int ?? '')),
            'colonia'              => strtoupper(trim($this->colonia)),
            'localidad'            => strtoupper(trim($this->localidad)),
            'estado'               => strtoupper(trim($this->estado)),
            'pais'                 => strtoupper(trim($this->pais)),
            'codigo_postal'        => strtoupper(trim($this->codigo_postal)),
            'regimen_fiscal_id'    => (int) $this->regimen_fiscal_id,
            'es_extranjero'        => (bool)$this->es_extranjero,
        ];

        if (  (int)($this->id) <= 0) {
            $RegFis = RegistroFiscal::create($item);
        }else{
            $RegFis = RegistroFiscal::find($this->id);
            $RegFis->update($item);
        }
        return $RegFis;
    }


}
