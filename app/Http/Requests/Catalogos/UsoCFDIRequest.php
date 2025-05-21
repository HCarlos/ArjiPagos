<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\UsoCFDI;
use Illuminate\Foundation\Http\FormRequest;

class UsoCFDIRequest extends FormRequest
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
            'clave_usocfdi' => [
                'min:1',
                'max:10',
                'unique:usocfdi,clave_usocfdi,'.$this->id
            ],
            'usocfdi' => 'required|string|max:250',
        ];
    }

    public function managed () {

        $item = [
            'clave_usocfdi' => trim($this->clave_usocfdi),
            'usocfdi'       => trim($this->usocfdi),
        ];

        if (  (int)($this->id) <= 0) {
            $UsoCFDI = UsoCFDI::create($item);
        }else{
            $UsoCFDI = UsoCFDI::find($this->id);
            $UsoCFDI->update($item);
        }
        return $UsoCFDI;
    }


}
