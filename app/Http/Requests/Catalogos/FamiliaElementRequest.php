<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\Familia;
use App\Models\Catalogos\FamiliaRegistrofiscal;
use App\Models\Catalogos\FamiliaUserRole;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

class FamiliaElementRequest extends FormRequest
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
            'familia_id' => 'required|min:1',
            'user_id' => 'required|min:1',
            'role_id' => 'required|min:1',
        ];
    }

    public function managed () {

        $familia_id = (int)($this->familia_id);
        $user_id    = (int)($this->user_id);
        $role_id    = (int)($this->role_id);

        $item = [
            'familia_id' => $familia_id,
            'user_id'    => $user_id,
            'role_id'    => $role_id,
        ];
        try {
            if ($familia_id > 0 && $user_id > 0 && $role_id > 0) {
                return FamiliaUserRole::create($item);
            }
        }catch (Exception $e) {
            return null;
        }

    }


}
