<?php

namespace App\Http\Controllers\Catalogos;

use App\Classes\FuncionesController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\FamiliaRequest;
use App\Models\Catalogos\Familia;
use App\Models\Catalogos\FamiliaRegistrofiscal;
use App\Models\Catalogos\FamiliaUserRole;
use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FamiliaRegistroFiscalController extends Controller{

    use AuthorizesRequests;

    public function index(Familia $familia){

        $familias = FamiliaRegistrofiscal::with(['familias', 'registrosfiscales'])
            ->where('familia_id', $familia->id)
            ->orderBy('id')
            ->get();

        $fam =  $familias->map(fn($f) => [
            'id' => $f->id,
            'familia_id' => $f->familia_id,
            'familia' => $f->familia->familia,
            'registrofiscal_id' => $f->registrofiscal_id,
            'rfc' => $f->registrofiscal->rfc,
            'razon_social_cfdi_40' => $f->registrofiscal->razon_social_cfdi_40,
            'predeterminado' => $f->predeterminado,
        ]);


        return Inertia::render('Catalogos/Familias/FamiliaRegFisList', [
            'familiaRegFis' => $fam,
            'totalFamiliasRegFis' => 1,
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
