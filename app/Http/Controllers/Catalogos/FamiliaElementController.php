<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\FamiliaElementRequest;
use App\Models\Catalogos\Familia;
use App\Models\Catalogos\FamiliaUserRole;
use App\Models\User;
use App\Models\User\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class FamiliaElementController extends Controller{

    use AuthorizesRequests;

    public function index(Familia $familia){

        $users = User::with(['user_adress', 'user_data_extend', 'user_alumno'])
                    ->orderBy('ap_paterno', 'asc')
                    ->orderBy('ap_materno', 'asc')
                    ->orderBy('nombre', 'asc')
                    ->get()->map(fn($u) => [
                        'id' => $u->id,
                        'full_name' => $u->full_name,
                    ]);

        $roles = Role::query()
            ->orderBy('id', 'asc')
            ->get()->map(fn($r) => [
                'id' => $r->id,
                'role' => $r->name,
            ]);


        $familias = FamiliaUserRole::with(['familias', 'users', 'roles'])
            ->where('familia_id', $familia->id)
            ->orderBy('id')
            ->get();

        if ( $familias->count() > 0 ) {
            $fam =  $familias->map(fn($f) => [
                'id' => $f->id,
                'familia_id' => $f->familia_id,
                'familia' => $f->familia->familia,
                'user_id' => $f->user_id,
                'usuario' => $f->user->full_name,
                'role_id' => $f->role_id,
                'role' => $f->role->name,
            ]);

        }else{
            $fam =  [];
        }

        return Inertia::render('Catalogos/Familias/FamiliaElementsList', [
            'familiaElements' => $fam,
            'totalFamiliasElements' => 1,
            'familia' => $familia,
            'users' => $users,
            'roles' => $roles
        ]);

    }

    public function store(FamiliaElementRequest $request){
        $Item = $request->managed(null);
        return redirect()->back()->with('success', 'Usuario creado exitosamente');
    }

    public function destroy($famEleId){
        $item = FamiliaUserRole::find($famEleId);
        if ($item) {
            $item->forceDelete(); // <-- ¡Usa delete() en lugar de softDelete()!
            return redirect()->back()->with('success', 'Elemento eliminado exitosamente');
        }
        return redirect()->back()->with('error', 'Elemento no encontrado'); // Manejo si no existe
    }


}
