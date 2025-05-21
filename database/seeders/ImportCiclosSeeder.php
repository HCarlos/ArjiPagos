<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Familia;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportCiclosSeeder extends Seeder{


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
                //BODY
        ************************************************************************************************** */

        $file = 'public/csv/_vi_cat_ciclos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ( $dupla[0] !== 'Undefined' &&  isset($dupla[0]) )  {

                    $arr = str_getcsv($dupla[0]);

                    $item = [
                        'ciclo'             => strtoupper(trim($arr[1])),
                        'fecha_inicio'      => $arr[2] ?? now()->format('Y-m-d'),
                        'fecha_fin'         => $arr[3] ?? now()->format('Y-m-d'),
                        'ciclo_anterior_id' => (int) ($arr[5] ?? 0),
                        'ano_anterior'      => (int) ($arr[6] ?? 0),
                        'ano_siguiente'     => (int) ($arr[7] ?? 0),
                        'predeterminado'    => (int) ($arr[4] ?? 0) === 1,
                        'old_ciclo_id'      => (int) $arr[0],
                    ];


                    Ciclo::create($item);


                }

            }catch (QueryException $e){
                Log::alert("Error en :: ".$arr[1]." - ".$e->getMessage());
                continue;
            }catch (Exception $e){
                Log::alert("Error en ".$arr[1]." - ".$e->getMessage());
                continue;
            }
        }



    }



}
