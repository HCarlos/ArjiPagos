<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\Familia;
use Illuminate\Foundation\Http\FormRequest;

class FamiliaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'familia' => 'required|string|max:250',
        ];
    }

    public function managed () {

        $item = [
            'familia'                       => strtoupper(trim($this->familia)),
            'observaciones_control_escolar' => strtoupper(trim($this->observaciones_control_escolar ?? '')),
            'observaciones_pagos'           => strtoupper(trim($this->observaciones_pagos ?? '')),
            'convenios'                     => strtoupper(trim($this->convenios ?? '')),
            'email_pagos'                   => strtoupper(trim($this->email_pagos ?? '')),
            'email_facturas'                => strtoupper(trim($this->email_facturas ?? '')),
            'email_control_escolar'         => strtoupper(trim($this->email_control_escolar ?? '')),
            'email_convenios'               => strtoupper(trim($this->email_convenios ?? '')),
        ];

//        dd($item);

        if (  (int)($this->id) <= 0) {
            $Item = Familia::create($item);
        }else{
            $Item = Familia::find($this->id);
            $Item->update($item);
        }
        return $Item;
    }


}
