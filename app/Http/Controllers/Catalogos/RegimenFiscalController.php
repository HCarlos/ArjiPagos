<?php

namespace App\Http\Controllers\Catalogos;

use App\Classes\FuncionesController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\RegimenFiscalRequest;
use App\Models\Catalogos\RegimenFiscal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegimenFiscalController extends Controller{

    use AuthorizesRequests;

//    public function __construct(){
//        $this->authorizeResource(User::class);
//    }

    public function index(Request $request): Response
    {

        $filters =$request->input('search');

        if ($filters) {

            $regimenes_fiscales = RegimenFiscal::query()
                ->orWhere('clave_regimen_fiscal', 'like', '%' . $filters . '%')
                ->orWhere('regimen_fiscal', 'like', '%' . $filters . '%')
                ->orderBy('clave_regimen_fiscal')
                ->get();

            return Inertia::render('Catalogos/RegimenesFiscales/RegimenesFiscalesList', [
                'regimenes_fiscales' => $regimenes_fiscales,
                'totalRegFis' => RegimenFiscal::count(),
            ]);
        }



        $regimenes_fiscales = RegimenFiscal::query()
            ->orderBy('clave_regimen_fiscal')
            ->get();

//        dd($regimenes_fiscales);

        return Inertia::render('Catalogos/RegimenesFiscales/RegimenesFiscalesList', [
            'regimenes_fiscales' => $regimenes_fiscales,
            'totalRegFis' => RegimenFiscal::count(),
        ]);

    }

    public function store(RegimenFiscalRequest $request){
        $RegFis = $request->managed(null);
        return redirect()->back()->with('success', 'Usuario creado exitosamente');
    }

    public function update(RegimenFiscalRequest $request, RegimenFiscal $RegFis){
        $RegFis = $request->managed($RegFis);
        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
    }


    public function destroy($regifisId){
        $regfis = RegimenFiscal::find($regifisId);
        $regfis->delete();
        return redirect()->back()->with('success', 'Regimen Fiscal eliminado exitosamente');
    }



}
