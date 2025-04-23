<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\Familia;
use App\Models\Catalogos\FamiliaRegistrofiscal;
use App\Models\Catalogos\FamiliaUserRole;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

class FamiliaRegFisRequest extends FormRequest
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
            'familia_id'        => 'required|numeric|min:1',
            'registrofiscal_id' => 'required|numeric|min:1',
            'predeterminado'    => 'required',
        ];
    }

    public function managed () {

        $familia_id        = (int)($this->familia_id);
        $registrofiscal_id = (int)($this->registrofiscal_id);
        $predeterminado    = (int)($this->predeterminado);

        if ($predeterminado === 1) {
            FamiliaRegistrofiscal::where('familia_id', $familia_id)->update(['predeterminado' => 0]);
        }

        $item = [
            'familia_id'        => $familia_id,
            'registrofiscal_id' => $registrofiscal_id,
            'predeterminado'    => $predeterminado === 1,
        ];

        try {
            if ($familia_id > 0 && $registrofiscal_id > 0) {
                return FamiliaRegistrofiscal::create($item);
            }
        }catch (Exception $e) {
            return null;
        }

        return null;

    }


}
