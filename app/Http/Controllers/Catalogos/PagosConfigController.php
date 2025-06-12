<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\PagoCatRequest;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\ConceptoDePago;
use App\Models\Catalogos\EmisorFiscal;
use App\Models\Catalogos\Nivel;
use App\Models\Catalogos\PagoCat;
use App\Models\Catalogos\ProductosYServiciosSAT;
use App\Models\Catalogos\UnidadMedidaSAT;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PagosConfigController extends Controller{

    use AuthorizesRequests;

    public function __construct(){ }

    public function middleware(): array{
        return ['auth',];
    }


    public function index(Request $request): Response{

        $filters =$request->input('search');

        $ciclo_predeterminado_id = Ciclo::select('id')->where('predeterminado', 1)->first()->id;

//        $niveles = Nivel::get(['id', 'clave_nivel', 'nivel'])
//            ->sortBy('clave_nivel');
//
//        $EmiFis = EmisorFiscal::get(['id', 'rfc', 'razon_social', 'razon_social_cfdi_40','estatus_emisor_fiscal'])
//            ->where('estatus_emisor_fiscal', true)
//            ->sortBy('rfc');
//
//        $ConPag = ConceptoDePago::get(['id', 'concepto', 'status_concepto'])
//            ->where('status_concepto', true)
//            ->sortBy('concepto');
//
//        $ProdServSAT = ProductosYServiciosSAT::get(['id', 'clave','descripcion', 'status_prodserv'])
//            ->where('status_prodserv', true)
//            ->sortBy('producto_servicio');
//
//        $UniMedSAT = UnidadMedidaSAT::get(['id', 'clave','descripcion', 'status_unidadmedida'])
//            ->where('status_unidadmedida', true)
//            ->sortBy('unidad_medida');


        $niveles = Nivel::select('id', 'clave_nivel', 'nivel')
            ->orderBy('clave_nivel')
            ->get(); // .get() devuelve una Collection, que se convierte a array en JSON

        $ConPag = ConceptoDePago::select('id', 'concepto')
            ->orderBy('concepto')
            ->get();

        $EmiFis = EmisorFiscal::select('id', 'razon_social_cfdi_40')
            ->get();

        $ProdServSAT = ProductosYServiciosSAT::select('id', 'clave', 'descripcion')
            ->orderBy('clave')
            ->get('id', 'producto_servicio');

        $UniMedSAT = UnidadMedidaSAT::select('id', 'clave', 'descripcion')
            ->orderBy('clave')
            ->get('id','unidad_medida');


//        dd($UniMedSAT);


        if ($filters) {

            $PagoCats = PagoCat::query()
                ->whereHas('concepto', function ($q) use ($filters) {
                    return $q->where('concepto', 'like', '%' . $filters . '%');
                    })
                ->orderBy('id')
                ->get();

            return Inertia::render('Catalogos/PagosConfig/PagoConfigList', [
                'pagoscat' => $PagoCats,
                'totalPagoCats' => PagoCat::count(),
                'niveles' => $niveles,
                'emisoresfiscales' => $EmiFis,
                'conceptos' => $ConPag,
                'prodservsats' => $ProdServSAT,
                'unidades' => $UniMedSAT,

            ]);
        }



        $PagoCats = PagoCat::with([
            'concepto:id,concepto',
            'nivel:id,clave_nivel,nivel'])
            ->orderBy('id')
            ->get();

//        dd($PagoCats[0]->importe_formatted);

        return Inertia::render('Catalogos/PagosConfig/PagoConfigList', [
            'pagoscat' => $PagoCats,
            'totalPagoCats' => PagoCat::count(),
            'niveles' => $niveles,
            'emisoresfiscales' => $EmiFis,
            'conceptos' => $ConPag,
            'prodservsats' => $ProdServSAT,
            'unidades' => $UniMedSAT,
        ]);

    }

    public function store(PagoCatRequest $request){
        $RegFis = $request->managed(null);
        return redirect()->back()->with('success', 'Item creado exitosamente');
    }

    public function update(PagoCatRequest $request, PagoCat $PagoCat){
        $RegFis = $request->managed($PagoCat);
        return redirect()->back()->with('success', 'Item actualizado exitosamente');
    }


    public function destroy($usocfdiId){
        $usocfdi = PagoCat::find($usocfdiId);
        $usocfdi->delete();
        return redirect()->back()->with('success', 'Item eliminado exitosamente');
    }


}
