<?php

namespace App\Http\Controllers\Catalogos;

use App\Classes\FuncionesController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\FamiliaRequest;
use App\Models\Catalogos\Familia;
use App\Models\Catalogos\FamiliaUserRole;
use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FamiliaElementController extends Controller{

    use AuthorizesRequests;

    public function index(Familia $familia){

        $familias = FamiliaUserRole::with(['familias', 'users', 'roles'])
            ->where('familia_id', $familia->id)
            ->orderBy('id')
            ->get();

        $fam =  $familias->map(fn($f) => [
            'id' => $f->id,
            'familia_id' => $f->familia_id,
            'familia' => $f->familia->familia,
            'user_id' => $f->user_id,
            'usuario' => $f->user->full_name,
            'role_id' => $f->role_id,
            'role' => $f->role->name,
        ]);

//        dd($fam);



        return Inertia::render('Catalogos/Familias/FamiliaElementsList', [
            'familiaElements' => $fam,
            'totalFamiliasElements' => 1,
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
        $Item = Familia::find($famId);
        if ($Item !== null) {
            $Item->delete();
            return redirect()->back()->with('success', 'Regimen Fiscal eliminado exitosamente');
        }
    }



}
