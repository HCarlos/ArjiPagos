<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\ConceptoDePago;
use App\Models\Catalogos\EmisorFiscal;
use App\Models\Catalogos\Nivel;
use App\Models\Catalogos\PagosCat;
use App\Models\Catalogos\ProductosYServiciosSAT;
use App\Models\Catalogos\UnidadMedidaSAT;
use Decimal\Decimal;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportConfiPagosSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{

        @ini_set( 'upload_max_size' , '32768M' );
        @ini_set( 'post_max_size', '32768M');
        @ini_set( 'max_execution_time', '256000000' );
        @ini_set('memory_limit', '-1');
        header('Content-Type: text/html; charset=UTF-8'); // Colócalo al inicio del archivo

        $F = new FuncionesController();

        /* ************************************************************************************************
                //SUBIMOS DEPENDENCIAS
        ************************************************************************************************** */
//        $file = 'public/csv/_viAlumnos.csv';
        $file = 'public/csv/_vi_cat_pagos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ($dupla[0] !== 'Undefined' ) {

                    $arr = str_getcsv($dupla[0]);

                    $clave_nivel = (int) ($arr[1] ?? 1);
                    $Niv = Nivel::select('id')->where('clave_nivel',$clave_nivel)->first();
                    $ef = (int) ($arr[2] ?? 1);
                    $Ef = EmisorFiscal::select('id')->where('old_emisorfiscal_id',$ef)->first();
                    $cp = (int) ($arr[3] ?? 1);
                    $Ep = ConceptoDePago::select('id')->where('old_concepto_id',$cp)->first();
                    $pss = (int) ($arr[16] ?? 1);
                    $pss = $pss === 0 ? 1 : $pss;
                    $Pss = ProductosYServiciosSAT::select('id')->where('old_prodserv_id',$pss)->first();

                    $item = PagosCat::create([
                        'nivel_id' => $Niv->id ?? 1,
                        'emisorfiscal_id' => $Ef->id ?? 1,
                        'concepto_id' => $Ep->id ?? 1,
                        'importe' => (float) ($arr[4] ?? 0.00),
                        'dia_limite' => (int) ($arr[5] ?? 10),
                        'dia_de_pago' => (int) ($arr[6] ?? 1),
                        'tiene_descuento_beca' => (int) ($arr[7] ?? 1) === 1,
                        'tiene_descuento' => (int) ($arr[8] ?? 1) === 1,
                        'acepta_pagos_diversos' => (int) ($arr[9] ?? 1) === 1,
                        'aplica_al_siguiente_nivel' => (int) ($arr[10] ?? 1) === 1,
                        'aplica_a' => (int) ($arr[11] ?? 1) === 1,
                        'tiene_descuento_por_promocion' => (int) ($arr[12] ?? 1) === 1,
                        'es_facturable' => (int) ($arr[13] ?? 1) === 1,
                        'se_puede_ver_en_pantalla' => (int) ($arr[14] ?? 1) === 1,
                        'orden_prioridad' => (int) ($arr[15] ?? 0),
                        'prodservsat_id' => $Pss->id ?? 1,
                        'unidadmedidasat_id' => (int)($arr[17]) === 0 ? 2 : (int)($arr[17]),
                        'tagpagos' => trim($arr[18] ?? ''),
                        'old_pago_id' => (int) ($arr[0] ?? 1),
                    ]);

                }

            }catch (QueryException $e){
                Log::alert("Error en :: ".$arr[1]." - ".$e->getMessage());
                continue;
            }catch (Exception $e){
                Log::alert("Error en Nivel ".$Niv->id." - ".$e->getMessage());
                continue;
            }
        }

    }


}
