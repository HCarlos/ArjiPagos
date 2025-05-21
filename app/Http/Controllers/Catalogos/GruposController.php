<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\GrupoRequest;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Grupo;
use App\Models\Catalogos\GrupoNivel;
use App\Models\Catalogos\Nivel;
use App\Models\Catalogos\RegimenFiscal;
use App\Models\Views\viGrupoNivelCiclo;
use App\Traits\GrupoAttributes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class GruposController extends Controller{

    use AuthorizesRequests, GrupoAttributes;

    public function index(Request $request): Response{

        $filters =$request->input('search');
        $ciclo_predeterminado_id = Ciclo::select('id')->where('predeterminado', 1)->first()->id;

        if ($filters) {

            $grupos = viGrupoNivelCiclo::with(['nivel_relacionado', 'ciclo_relacionado', 'grupo_relacionado'])
                ->where('ciclo_id', $ciclo_predeterminado_id)
                ->when($filters, fn($q) => $q->filter($filters))
                ->orderBy('clave_grupo')
                ->select(Grupo::getSelectItemsAttribute())
                ->get();
        }else{

            $grupos = viGrupoNivelCiclo::with(['nivel_relacionado', 'ciclo_relacionado', 'grupo_relacionado'])
                ->where('ciclo_id', $ciclo_predeterminado_id)
                ->orderBy('clave_grupo')
                ->select(Grupo::getSelectItemsAttribute())
                ->get();

        }

        foreach ($grupos as $grupo) {
           $grupo->alumnos = $grupo->grupo_relacionado->alumnos()->select(['users.id', 'ap_paterno', 'ap_materno', 'nombre'])->wherePivot('ciclo_id', $ciclo_predeterminado_id)->get() ?? count() ?? '';
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

        return Inertia::render('Catalogos/Grupos/GruposList', [
            'grupos' => $grupos,
            'totalGrupos' => Grupo::count(),
            'titulo' => 'Catlogo de Grupos',
            'niveles' => $niveles,
            'ciclos' => $ciclos
        ]);

    }

    public function store(GrupoRequest $request){
        $Item = $request->managed(null);
        return redirect()->back()->with('success', 'Grupo creado exitosamente');
    }

    public function update(GrupoRequest $request){
        $Item = $request->managed();
        return redirect()->back()->with('success', 'Grupo actualizado exitosamente');
    }

    public function destroy($gruId){
        $Item = GrupoNivel::find($gruId);
        if ($Item !== null) {
            $Item->forceDelete();
            return redirect()->back()->with('success', 'Grupo eliminado exitosamente');
        }
    }

}
