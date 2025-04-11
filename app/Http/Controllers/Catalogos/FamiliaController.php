<?php

namespace App\Http\Controllers\Catalogos;

use App\Classes\FuncionesController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\FamiliaRequest;
use App\Models\Catalogos\Familia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FamiliaController extends Controller{

    use AuthorizesRequests;

//    public function __construct(){
//        $this->authorizeResource(User::class);
//    }

    public function index(Request $request): Response
    {

        $filters =$request->input('search');

        if ($filters) {

            $familias = Familia::query()
                ->where('familia', 'like', '%' . strtoupper(trim($filters)) . '%')
                ->orderBy('familia')
                ->paginate(250);

            return Inertia::render('Catalogos/Familias/FamiliasList', [
                'familias' => $familias,
                'totalFamilias' => Familia::count(),
            ]);
        }



        $familias = Familia::query()
            ->orderByDesc('id')
            ->paginate(250);

//        dd($familias);

        return Inertia::render('Catalogos/Familias/FamiliasList', [
            'familias' => $familias,
            'totalFamilias' => Familia::count(),
        ]);

    }

    public function store(FamiliaRequest $request){
        $Item = $request->managed(null);
        return redirect()->back()->with('success', 'Usuario creado exitosamente');
    }

    public function update(FamiliaRequest $request){
        $Item = $request->managed();
        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
    }


    public function destroy($famId){
        $regfis = Familia::find($famId);
        $regfis->delete();
        return redirect()->back()->with('success', 'Regimen Fiscal eliminado exitosamente');
    }



}
