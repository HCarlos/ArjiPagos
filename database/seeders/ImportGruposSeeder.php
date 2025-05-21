<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Grupo;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportGruposSeeder extends Seeder {

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

        $file = 'public/csv/_vi_cat_grupos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ( $dupla[0] !== 'Undefined' &&  isset($dupla[0]) )  {

                    $arr = str_getcsv($dupla[0]);

//                    dd($arr);

                    $item = [

                        'clave_grupo'           => strtoupper(trim($arr[1])),
                        'grupo'                 => strtoupper(trim(utf8_decode($arr[2]))),
                        'grupo_oficial'         => strtoupper(trim(utf8_decode($arr[3]))),
                        'grupo_periodo'         => strtoupper(trim($arr[4])),
                        'grupo_periodo_ciclo'   => strtoupper(trim($arr[5])),
                        'nivel'                 => strtoupper(trim($arr[6])),
                        'grado'                 => strtoupper(trim($arr[7])),
                        'grado_pai'             => strtoupper(trim($arr[8])),
                        'num'                   => strtoupper(trim($arr[9])),
                        'is_visible'            => (int) trim($arr[10]) === 1,
                        'is_bloqueado'          => (int) trim($arr[11]) === 1,
                        'is_activo_en_caja'     => (int) trim($arr[12]) === 1,
                        'is_ver_boleta_interna' => (int) trim($arr[13]) === 1,
                        'is_ver_boleta_oficial' => (int) trim($arr[14]) === 1,
                        'is_grupo_pai'          => (int) trim($arr[15]) === 1,
                        'status_grupo'          => (int) trim($arr[16]) === 1,
                        'old_grupo_id'          => (int) trim($arr[0]) ,
                    ];

                    Grupo::create($item);


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
