<?php

namespace App\Http\Controllers\Catalogos;

use App\Classes\FuncionesController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\RegistroFiscalRequest;
use App\Models\Catalogos\RegimenFiscal;
use App\Models\Catalogos\RegistroFiscal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegistroFiscalController extends Controller{

    use AuthorizesRequests;

//    public function __construct(){
//        $this->authorizeResource(User::class);
//    }

    public function index(Request $request): Response
    {

        $filters =$request->input('search');

        if ($filters) {
            $F           = new FuncionesController();
            $filters      = strtolower($filters);
            $filters      = $F->str_sanitizer($filters);
            $tsString     = $F->string_to_tsQuery( strtoupper($filters),' & ');

            $registros = RegistroFiscal::query()
                ->search( $tsString )
                ->orderBy('id', 'desc')
                ->paginate();

            return Inertia::render('Catalogos/RegistrosFiscales/RegistrosFiscalesList', [
                'registros' => $registros,
                'totalRegFis' => RegistroFiscal::count(),
            ]);
        }



        $regimenes_fiscales = RegimenFiscal::get(['id', 'clave_regimen_fiscal', 'regimen_fiscal'])
            ->sortBy('clave_regimen_fiscal')
            ->pluck('formattedRegimenFiscal', 'id')
            ->toArray();

//        dd($regimenes_fiscales);

        $regfis = RegistroFiscal::
            with('regimen_fiscal')
            ->orderBy('id', 'desc')
            ->paginate(500);

//        dd($regfis);


        return Inertia::render('Catalogos/RegistrosFiscales/RegistrosFiscalesList', [
            'registros' => $regfis,
            'regimenes_fiscales' => $regimenes_fiscales,
            'totalRegFis' => RegistroFiscal::count(),
        ]);

    }

    public function store(RegistroFiscalRequest $request){
        $RegFis = $request->managed(null);
        return redirect()->back()->with('success', 'Usuario creado exitosamente');
    }

    public function update(RegistroFiscalRequest $request, RegistroFiscal $RegFis){
        $RegFis = $request->managed($RegFis);
        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
    }


    public function destroy($regfisId){
        $regfis = RegistroFiscal::find($regfisId);
        $regfis->delete();
        return redirect()->back()->with('success', 'Registro Fiscal eliminado exitosamente');
    }



}
