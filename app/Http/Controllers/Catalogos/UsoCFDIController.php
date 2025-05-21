<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\UsoCFDIRequest;
use App\Models\Catalogos\UsoCFDI;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsoCFDIController extends Controller{

    use AuthorizesRequests;

    public function __construct(){ }

    public function middleware(): array{
        return ['auth',];
    }

    public function index(Request $request): Response{

        $filters =$request->input('search');

        if ($filters) {

            $UsoCFDIs = UsoCFDI::query()
                ->orWhere('clave_usocfdi', 'like', '%' . $filters . '%')
                ->orWhere('usocfdi', 'like', '%' . $filters . '%')
                ->orderBy('clave_usocfdi')
                ->get();

            return Inertia::render('Catalogos/UsoCFDI/UsoCFDIList', [
                'usocfdis' => $UsoCFDIs,
                'totalUsoCFDIs' => UsoCFDI::count(),
            ]);
        }



        $UsoCFDIs = UsoCFDI::query()
            ->orderBy('clave_usocfdi')
            ->get();

//        dd($UsoCFDIs);

        return Inertia::render('Catalogos/UsoCFDI/UsoCFDIList', [
            'usocfdis' => $UsoCFDIs,
            'totalUsoCFDIs' => UsoCFDI::count(),
        ]);

    }

    public function store(UsoCFDIRequest $request){
        $RegFis = $request->managed(null);
        return redirect()->back()->with('success', 'Item creado exitosamente');
    }

    public function update(UsoCFDIRequest $request, UsoCFDI $UsoCFDI){
        $RegFis = $request->managed($UsoCFDI);
        return redirect()->back()->with('success', 'Item actualizado exitosamente');
    }


    public function destroy($usocfdiId){
        $usocfdi = UsoCFDI::find($usocfdiId);
        $usocfdi->delete();
        return redirect()->back()->with('success', 'Item eliminado exitosamente');
    }



}
