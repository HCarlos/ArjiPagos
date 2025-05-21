<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\AlumnoGrupo;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Grupo;
use App\Models\Catalogos\GrupoNivel;
use App\Models\Catalogos\Nivel;
use App\Models\User;
use App\Models\Views\viGrupoNivelCiclo;
use App\Traits\GrupoAttributes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AlumnoGrupoController extends Controller{


    use AuthorizesRequests, GrupoAttributes;

    public function index($combo1_id, $combo2_id, $combo3_id){

        $ciclo_predeterminado_id = Ciclo::select('id')->where('predeterminado', 1)->first()->id;

        if ($combo1_id === null || (int)$combo1_id === 0) {
            $combo1_id = Ciclo::select('id')->where('predeterminado', 1)->first()->id;
        }
        if ($combo2_id === null || (int)$combo2_id === 0) {
            $combo2_id = Nivel::select('id')->first()->id;
        }
        if ($combo3_id === null || (int)$combo3_id === 0) {
            $combo3_id = Grupo::select('id')->first()->id;
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

        $grupos = Grupo::query()
            ->whereHas("niveles", function ($q) use ($combo1_id, $combo2_id) {
                $q->where('ciclo_id', $combo1_id)
                    ->where('nivel_id', $combo2_id);
            })
            ->pluck('grupo', 'id')
            ->toArray();

//        dd($grupos);


//        $asignados = User::select('ap_paterno', 'ap_materno', 'nombre', 'id')
//            ->whereHas("grupos", function ($q) use ($combo1_id) {
//                $q->where('ciclo_id', $combo1_id);
//            })
//            ->get() // Obtener la colección de modelos
//            ->pluck('full_name', 'id') // 'fullName' ahora funciona en la colección de modelos
//            ->toArray();

        $asignados = User::conGrupoCiclo($combo1_id,$combo3_id)
            ->select(['id', 'ap_paterno', 'ap_materno', 'nombre'])
            ->orderBy('ap_paterno')
            ->orderBy('ap_materno')
            ->orderBy('nombre')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'descripcion' => $user->full_name, // Usa el accessor
                ];
            })
            ->values() // Elimina keys originales y crea array indexado
            ->toArray();


//        dd($asignados);


        $users = User::alumno()
            ->select(['id', 'ap_paterno', 'ap_materno', 'nombre'])
            ->orderBy('ap_paterno')
            ->orderBy('ap_materno')
            ->orderBy('nombre')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'descripcion' => $user->full_name, // Usa el accessor
                ];
            })
            ->values() // Elimina keys originales y crea array indexado
            ->toArray();


//    dd($users);

        return Inertia::render('Asignaciones/OriginCOneCTwoCThreeRecipient', [
            'combo1'          => $ciclos,
            'combo2'          => $niveles,
            'combo3'          => $grupos,
            'titulo'          => 'Asignaciones de Alumnos a Grupo',
            'leftDisponibles' => $users,
            'rightAsignados'  => $asignados,
            'urlAsignados'    => 'alumnos.a.grupo',
            'urlAdd'          => 'alumnos.agregar.a.grupo',
            'urlDelete'       => 'alumnos.quitar.a.grupo',
        ]);

    }

    public function agregarItem(Request $request){

        foreach ($request->ids as $item) {

            $gn = AlumnoGrupo::select('id')
                ->where('ciclo_id', $request->combo1_id)
                ->where('grupo_id', $request->combo3_id)
                ->where('alumno_user_id', $item)
                ->first();

            if ($gn === null) {
                try {
                    AlumnoGrupo::create([
                        'ciclo_id'  => $request->combo1_id,
                        'grupo_id'  => $request->combo3_id,
                        'alumno_user_id'  => $item,
                        'num_lista' => 0,
                    ]);
                } catch (\Throwable $th) {
                    dd($th);
                    //continue;
                }

            }


        }

        return redirect()->back()->with('success', 'Alumno agregado exitosamente');

    }

    public function quitarItem(Request $request){

        foreach ($request->ids as $item) {

            $gn = AlumnoGrupo::select('id')
                ->where('ciclo_id', $request->combo1_id)
                ->where('grupo_id', $request->combo3_id)
                ->where('alumno_user_id', $item)
                ->first();

            if ($gn !== null) {
                $gn->forceDelete();
            }
        }

        return redirect()->back()->with('success', 'Alumno eliminado exitosamente');

    }



}
