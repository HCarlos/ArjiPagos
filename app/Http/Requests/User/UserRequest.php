<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
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

//        dd($this->user_alumno);

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

        $ualu = [
            'matricula_interna' => strtoupper(trim($this->user_alumno['matricula_interna'] ?? '')),
            'matricula_oficial'      => strtoupper(trim($this->user_alumno['matricula_oficial'] ?? '')),
            'email_alumno'      => strtoupper(trim($this->user_alumno['email_alumno'] ?? '')),
            'enfermedades'  => strtoupper(trim($this->user_alumno['enfermedades'] ?? '')),
            'reacciones_alergicas' => strtoupper(trim($this->user_alumno['reacciones_alergicas'] ?? '')),
            'tipo_sangre'      => strtoupper(trim($this->user_alumno['tipo_sangre'] ?? '')),
            'beca_sep'      => (float) $this->user_alumno['beca_sep'],
            'beca_arji'  => (float) $this->user_alumno['beca_arji'],
            'beca_sp'  => (float) $this->user_alumno['beca_sp'],
            'beca_bach'  => (float) $this->user_alumno['beca_bach'],
            'es_baja'  => (bool) $this->user_alumno['es_baja'] ,
            'motivo_baja'  => strtoupper(trim($this->user_alumno['motivo_baja'] ?? '')),
            'fecha_ingreso'  => Carbon::parse( $this->user_alumno['fecha_ingreso'])->format('Y-m-d') ?? now(),
            'observaciones'  => strtoupper(trim($this->user_alumno['observaciones'] ?? '')),
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

           $User->user_alumno()->create($ualu);
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
               $User->user_alumno()->update($ualu);
               $User->user_adress()->update($ua);
               $User->user_data_extend()->update($ude);
           }else{
               $User->user_alumno()->create($ualu);
               $User->user_adress()->create($ua);
               $User->user_data_extend()->create($ude);
           }

       }
       return $User;
    }



}
