<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\NivelRequest;
use App\Models\Catalogos\Nivel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NivelesController extends Controller{


    use AuthorizesRequests;

    public function index(Request $request): Response
    {

        $filters =$request->input('search');

        if ($filters) {

            $niveles = Nivel::query()
                ->where('nivel', 'like', '%' . strtoupper(trim($filters)) . '%')
                ->orderBy('nivel')
                ->paginate(10);

            return Inertia::render('Catalogos/Niveles/NivelesList', [
                'niveles' => $niveles,
                'totalNiveles' => Nivel::count(),
            ]);
        }



        $niveles = Nivel::query()
            ->orderBy('id')
            ->paginate(10);

        $niv =  $niveles->map(fn($f) => [
            'id' => $f->id,
            'nivel' => $f->nivel,
        ]);

        return Inertia::render('Catalogos/Niveles/NivelesList', [
            'niveles' => $niveles,
            'totalNiveles' => Nivel::count(),
        ]);

    }

    public function store(NivelRequest $request){
        $Item = $request->managed(null);
        return redirect()->back()->with('success', 'Nivel creado exitosamente');
    }

    public function update(NivelRequest $request){
        $Item = $request->managed();
        return redirect()->back()->with('success', 'Nivel actualizado exitosamente');
    }


    public function destroy($nivId){
        $Item = Nivel::find($nivId);
        if ($Item !== null) {
            $Item->forceDelete();
            return redirect()->back()->with('success', 'Nivel eliminado exitosamente');
        }
    }



}
