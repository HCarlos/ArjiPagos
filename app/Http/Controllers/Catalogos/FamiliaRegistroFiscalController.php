<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\FamiliaRegFisRequest;
use App\Models\Catalogos\Familia;
use App\Models\Catalogos\FamiliaRegistrofiscal;
use App\Models\Catalogos\RegistroFiscal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class FamiliaRegistroFiscalController extends Controller{

    use AuthorizesRequests;

    public function index(Familia $familia){

        $registrosfiscales = RegistroFiscal::query()
            ->orderBy('rfc', 'asc')
            ->get()->map(fn($r) => [
                'id' => $r->id,
                'rfc' => $r->rfc,
                'razon_social_cfdi_40' => $r->razon_social_cfdi_40,
                'razon_social' => $r->razon_social,
            ]);


        $familias = FamiliaRegistrofiscal::with(['familias', 'registrosfiscales'])
            ->where('familia_id', $familia->id)
            ->orderBy('id')
            ->get();

        if ( $familias->count() > 0 ) {
            $fam =  $familias->map(fn($f) => [
                'id' => $f->id,
                'familia_id' => $f->familia_id,
                'familia' => $f->familia->familia,
                'registrofiscal_id' => $f->role_id,
                'rfc' => $f->registrofiscal->rfc,
                'razon_social_cfdi_40' => $f->registrofiscal->razon_social_cfdi_40,
                'razon_social' => $f->registrofiscal->razon_social,
                'predeterminado' => $f->predeterminado,
                ]);

        }else{
            $fam =  [];
        }

        return Inertia::render('Catalogos/Familias/FamiliaRegFisList', [
            'familiaRegFis' => $fam,
            'totalFamiliasRegFis' => $familias->count(),
            'familia' => $familia,
            'registrosfiscales' => $registrosfiscales
        ]);

    }

    public function store(FamiliaRegFisRequest $request){

        $Item = $request->managed(null);
        return redirect()->back()->with('success', 'Registro Fiscal agregado exitosamente');
    }

    public function destroy($famRegFisId){
        $item = FamiliaRegistrofiscal::find($famRegFisId);
        if ($item) {
            $item->forceDelete(); // <-- ¡Usa delete() en lugar de softDelete()!
            return redirect()->back()->with('success', 'Registro Fiscal eliminado exitosamente');
        }
        return redirect()->back()->with('error', 'Registro Fiscal no encontrado'); // Manejo si no existe
    }


}
