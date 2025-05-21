<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\CicloRequest;
use App\Models\Catalogos\Ciclo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CiclosController extends Controller{

    use AuthorizesRequests;

    public function index(Request $request): Response
    {

        $filters =$request->input('search');

        if ($filters) {

            $ciclos = Ciclo::query()
                ->where('ciclo', 'like', '%' . strtoupper(trim($filters)) . '%')
                ->orderBy('ciclo')
                ->paginate(10);

            return Inertia::render('Catalogos/Ciclos/CiclosList', [
                'ciclos' => $ciclos,
                'totalCiclos' => Ciclo::count(),
            ]);
        }



        $ciclos = Ciclo::query()
            ->orderByDesc('id')
            ->paginate(10);

        $cic =  $ciclos->map(fn($f) => [
            'id' => $f->id,
            'ciclo' => $f->ciclo,
        ]);

        return Inertia::render('Catalogos/Ciclos/CiclosList', [
            'ciclos' => $ciclos, //['data' => $cic],
            'totalCiclos' => Ciclo::count(),
        ]);

    }

    public function store(CicloRequest $request){
        $Item = $request->managed(null);
        return redirect()->back()->with('success', 'Ciclo creado exitosamente');
    }

    public function update(CicloRequest $request){
        $Item = $request->managed();
        return redirect()->back()->with('success', 'Ciclo actualizado exitosamente');
    }


    public function destroy($cicId){
        $Item = Ciclo::find($cicId);
        if ($Item !== null) {
            $Item->forceDelete();
            return redirect()->back()->with('success', 'Ciclo eliminado exitosamente');
        }
    }



}
