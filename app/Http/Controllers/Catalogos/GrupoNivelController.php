<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Grupo;
use App\Models\Catalogos\GrupoNivel;
use App\Models\Catalogos\Nivel;
use App\Models\Views\viGrupoNivelCiclo;
use App\Traits\GrupoAttributes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GrupoNivelController extends Controller{


    use AuthorizesRequests, GrupoAttributes;

    public function GruposEnNivel($combo1_id, $combo2_id){

        $ciclo_predeterminado_id = Ciclo::select('id')->where('predeterminado', 1)->first()->id;

        if ($combo1_id === null || (int)$combo1_id === 0) {
            $combo1_id = Ciclo::select('id')->where('predeterminado', 1)->first()->id;
        }
        if ($combo2_id === null || (int)$combo2_id === 0) {
            $combo2_id = Nivel::select('id')->first()->id;
        }

        $niveles = Nivel::get(['id', 'clave_nivel', 'nivel'])
            ->sortBy('clave_nivel')
            ->pluck('formattedNivel', 'id')
            ->toArray();

        $ciclos = Ciclo::get(['id', 'ciclo', 'fecha_inicio', 'fecha_fin', 'predeterminado'])
            ->where('predeterminado', true)
            ->sortBy('ciclo')
            ->pluck('ciclo', 'id')
            ->toArray();


        $asignados = viGrupoNivelCiclo::query()
            ->where('ciclo_id', (int)$combo1_id)
            ->where('nivel_id', (int)$combo2_id)
            ->select('grupo', 'grupo_id')
            ->pluck('grupo', 'grupo_id')
            ->toArray();

//        dd($asignados);

        return Inertia::render('Asignaciones/OriginCOneCTwoRecipient', [
            'combo1' => $ciclos,
            'combo2' => $niveles,
            'titulo' => 'Asignaciones de Grupos a Niveles',
            'leftDisponibles' => Grupo::query()->pluck('grupo', 'id')->toArray(),
            'rightAsignados' => $asignados,
            'urlAsignados' => 'grupos.en.nivel',
            'urlAdd' => 'grupos.agregar.de.nivel',
            'urlDelete' => 'grupos.quitar.de.nivel',
        ]);

    }

    public function agregarItem(Request $request){

        foreach ($request->ids as $item) {

            $gn = GrupoNivel::select('id')
                ->where('grupo_id', $item)
                ->where('nivel_id', $request->combo2_id)
                ->where('ciclo_id', $request->combo1_id)
                ->first();

            if ($gn === null) {
                try {
                    GrupoNivel::create([
                        'grupo_id'  => $item,
                        'nivel_id'  => $request->combo2_id,
                        'ciclo_id'  => $request->combo1_id,
                        'is_visible' => true,
                    ]);
                } catch (\Throwable $th) {
                    dd($th);
                    //continue;
                }

            }


        }

        return redirect()->back()->with('success', 'Grupo agregado exitosamente');

    }

    public function quitarItem(Request $request){

        foreach ($request->ids as $item) {
            $gn = GrupoNivel::select('id')
                ->where('grupo_id', $item)
                ->where('nivel_id', $request->combo2_id)
                ->where('ciclo_id', $request->combo1_id)
                ->first();
            if ($gn !== null) {
                $gn->forceDelete();
            }
        }

        return redirect()->back()->with('success', 'Grupo eliminado exitosamente');

    }



}
