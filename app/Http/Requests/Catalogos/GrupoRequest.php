<?php

namespace App\Http\Requests\Catalogos;

use App\Models\Catalogos\Grupo;
use App\Models\Catalogos\GrupoNivel;
use Illuminate\Foundation\Http\FormRequest;

class GrupoRequest extends FormRequest{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(){
        return [
            'grupo' => [
                'required',
                'string',
                'min:1',
                'max:250',
                'unique:grupos,grupo,'.$this->grupo_id
            ],
            'clave_grupo' => [
                'required',
                'string',
                'min:1',
                'max:3',
                'unique:grupos,clave_grupo,'.$this->grupo_id
            ],
        ];
    }

    public function managed () {

//        dd($this->all());

        $is_visible            = (bool)($this->is_visible ?? false);
        $is_bloqueado          = (bool)($this->is_bloqueado ?? false);
        $is_activo_en_caja     = (bool)($this->is_activo_en_caja ?? false);
        $is_ver_boleta_interna = (bool)($this->is_ver_boleta_interna ?? false);
        $is_ver_boleta_oficial = (bool)($this->is_ver_boleta_oficial ?? false);
        $is_grupo_pai          = (bool)($this->is_grupo_pai ?? false);
        $status_grupo          = (bool)($this->status_grupo ?? false);

        $item = [
            'grupo'                 => strtoupper(trim($this->grupo ?? '')),
            'clave_grupo'           => strtoupper(trim($this->clave_grupo ?? '')),
            'grupo_oficial'         => strtoupper(trim($this->grupo_oficial ?? '')),
            'grupo_periodo'         => strtoupper(trim($this->grupo_periodo ?? '')),
            'grupo_periodo_ciclo'   => strtoupper(trim($this->grupo_periodo_ciclo ?? '')),
            'grado'                 => strtoupper(trim($this->grado ?? '')),
            'grado_pai'             => "",
            'num'                   => $this->num ?? 0,
            'grupo_siguiente_id'    => $this->grupo_siguiente_id,
            'is_visible'            => $is_visible,
            'is_bloqueado'          => $is_bloqueado,
            'is_activo_en_caja'     => $is_activo_en_caja,
            'is_ver_boleta_interna' => $is_ver_boleta_interna,
            'is_ver_boleta_oficial' => $is_ver_boleta_oficial,
            'is_grupo_pai'          => $is_grupo_pai,
            'status_grupo'          => $status_grupo,
        ];

        if (  (int)($this->grupo_id) <= 0) {
            $Item = Grupo::create($item);
        }else{
            $Item = Grupo::find($this->grupo_id);
            $Item->update($item);
        }
        return $Item;
    }



}
