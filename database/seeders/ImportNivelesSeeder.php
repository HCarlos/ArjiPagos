<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Nivel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportNivelesSeeder extends Seeder{


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

        $file = 'public/csv/_vi_cat_niveles.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ( $dupla[0] !== 'Undefined' &&  isset($dupla[0]) )  {

                    $arr = str_getcsv($dupla[0]);

                    $item = [
                        'clave_nivel'          => strtoupper(trim($arr[1])),
                        'nivel'                => strtoupper(trim($arr[2])),
                        'clave_registro_nivel' => strtoupper(trim($arr[3])),
                        'nivel_oficial'        => strtoupper(trim($arr[4])),
                        'nivel_fiscal'         => strtoupper(trim($arr[5])),
                        'prefijo_evaluacion'   => strtoupper(trim($arr[6])),
                        'numero_evaluaciones'  => strtoupper(trim($arr[7])),
                        'fecha_actas'          => strtoupper(trim($arr[8])),
                        'old_nivel_id'         => (int) $arr[0],
                    ];




                    Nivel::create($item);


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
