<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\ConceptoDePagoRequest;
use App\Models\Catalogos\ConceptoDePago;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConceptosDePagoController extends Controller{

    use AuthorizesRequests;

    public function __construct(){ }

    public function middleware(): array{
        return ['auth',];
    }

    public function index(Request $request): Response{

        $filters =$request->input('search');

        if ($filters) {

            $ConceptosDePagos = ConceptoDePago::query()
                ->orWhere('concepto', 'like', '%' . $filters . '%')
                ->orderBy('id')
                ->get();

            return Inertia::render('Catalogos/ConceptosDePago/ConceptosDePagoList', [
                'conceptosdepago' => $ConceptosDePagos,
                'totalConceptosDePagos' => ConceptoDePago::count(),
            ]);
        }



        $ConceptosDePagos = ConceptoDePago::query()
            ->orderBy('id')
            ->get();

//        dd($ConceptosDePagos);

        return Inertia::render('Catalogos/ConceptosDePago/ConceptosDePagoList', [
            'conceptosdepago' => $ConceptosDePagos,
            'totalConceptosDePagos' => ConceptoDePago::count(),
        ]);

    }

    public function store(ConceptoDePagoRequest $request){
        $RegFis = $request->managed(null);
        return redirect()->back()->with('success', 'Item creado exitosamente');
    }

    public function update(ConceptoDePagoRequest $request, ConceptoDePago $ConceptosDePago){
        $RegFis = $request->managed($ConceptosDePago);
        return redirect()->back()->with('success', 'Item actualizado exitosamente');
    }


    public function destroy($usocfdiId){
        $usocfdi = ConceptoDePago::find($usocfdiId);
        $usocfdi->delete();
        return redirect()->back()->with('success', 'Item eliminado exitosamente');
    }



}
