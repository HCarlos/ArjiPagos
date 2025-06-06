<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\PagoCatRequest;
use App\Models\Catalogos\PagoCat;
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
            ]);
        }



        $PagoCats = PagoCat::query()
            ->orderBy('id')
            ->get();

//        dd($PagoCats);

        return Inertia::render('Catalogos/PagosConfig/PagoCatList', [
            'pagoscat' => $PagoCats,
            'totalPagoCats' => PagoCat::count(),
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
