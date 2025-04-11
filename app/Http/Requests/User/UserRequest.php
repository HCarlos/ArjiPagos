<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest{

    public function authorize()
    {
        return true; // Cambiar a lógica de autorización si es necesario
    }

    public function rules()
    {
        return [
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'unique:users,username,'.$this->id
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email,'.$this->id
            ],
            'password' => ['nullable', Password::min(8)],
            'curp' => [
                'required',
                'unique:users,curp,'.$this->id
            ],
            'genero' => 'required|in:0,1,2',
        ];
    }


    public function managed ($User) {

        $ude = [
            'lugar_nacimiento' => strtoupper(trim($this->user_data_extend['lugar_nacimiento'] ?? '')),
            'ocupacion'      => strtoupper(trim($this->user_data_extend['ocupacion'] ?? '')),
            'profesion'      => strtoupper(trim($this->user_data_extend['profesion'] ?? '')),
            'lugar_trabajo'  => strtoupper(trim($this->user_data_extend['lugar_trabajo'] ?? '')),
        ];

        $ua = [
            'calle' => strtoupper(trim($this->user_adress['calle'] ?? '')),
            'num_ext'      => strtoupper(trim($this->user_adress['num_ext'] ?? '')),
            'num_int'      => strtoupper(trim($this->user_adress['num_int'] ?? '')),
            'colonia'  => strtoupper(trim($this->user_adress['colonia'] ?? '')),
            'municipio' => strtoupper(trim($this->user_adress['municipio'] ?? '')),
            'estado'      => strtoupper(trim($this->user_adress['estado'] ?? '')),
            'pais'      => strtoupper(trim($this->user_adress['pais'] ?? 'México')),
            'cp'  => strtoupper(trim($this->user_adress['cp'] ?? '')),
        ];




        if ($this->id <= 0) {
           $item = [
               'username' => trim($this->username),
               'password' => bcrypt(trim($this->username)),
               'email' => trim($this->email) ?? '',
               'nombre' => strtoupper(trim($this->nombre)) ?? '',
               'ap_paterno' => strtoupper(trim($this->ap_paterno)) ?? '',
               'ap_materno' => strtoupper(trim($this->ap_materno)) ?? '',
               'curp' => strtoupper(trim($this->curp)) ?? '',
               'emails' => strtoupper(trim($this->emails)) ?? '',
               'celulares' => strtoupper(trim($this->celulares)) ?? '',
               'telefonos' => strtoupper(trim($this->telefonos)) ?? '',
               'fecha_nacimiento' => $this->fecha_nacimiento ?? now(),
               'genero' => (int) ($this->genero ?? 0),
           ];
           $User = User::create($item);

           $User->user_adress()->create($ua);
           $User->user_data_extend()->create($ude);

       }else{

           $item = [
               'nombre' => strtoupper(trim($this->nombre)) ?? '',
               'ap_paterno' => strtoupper(trim($this->ap_paterno)) ?? '',
               'ap_materno' => strtoupper(trim($this->ap_materno)) ?? '',
               'curp' => strtoupper(trim($this->curp)) ?? '',
               'emails' => strtoupper(trim($this->emails)) ?? '',
               'celulares' => strtoupper(trim($this->celulares)) ?? '',
               'telefonos' => strtoupper(trim($this->telefonos)) ?? '',
               'fecha_nacimiento' => $this->fecha_nacimiento ?? now(),
               'genero' => (int) ($this->genero ?? 0),
           ];

           $User->update($item);

           if ( $User->user_adress()->exists() ) {
//               dd($ude);
               $User->user_adress()->update($ua);
               $User->user_data_extend()->update($ude);
           }else{
               $User->user_adress()->create($ua);
               $User->user_data_extend()->create($ude);
           }

       }
       return $User;
    }



}
