<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Familia;
use App\Models\Catalogos\RegistroFiscal;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportFamiliasSeeder extends Seeder{


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
        $file = 'public/csv/_vi_cat_familias.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ( $dupla[0] !== 'Undefined' &&  isset($dupla[0]) )  {

                    $arr = str_getcsv($dupla[0]);

                    Familia::create([
                        'familia' => utf8_encode(trim($arr[1])) ?? '',
                        'observaciones_control_escolar' => utf8_encode(trim($arr[2])) ?? '',
                        'observaciones_pagos' => utf8_encode(trim($arr[3])) ?? '',
                        'convenios' => utf8_encode(trim($arr[4])) ?? '',
                        'email_pagos' => '',
                        'email_facturas' => '',
                        'email_control_escolar' => strtolower(trim($arr[5])) ?? '',
                        'email_convenios' => '',
                        'old_familia_id' => (int)($arr[0] ?? ''),
                    ]);


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
