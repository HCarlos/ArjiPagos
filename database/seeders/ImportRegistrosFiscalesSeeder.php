<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\RegistroFiscal;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportRegistrosFiscalesSeeder extends Seeder{


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
        $file = 'public/csv/_vi_cat_registros_fiscales.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ($dupla[0] !== 'Undefined' ) {

                    $arr = str_getcsv($dupla[0]);


                    if ( !str_starts_with($arr[1], 'XA') && !str_starts_with($arr[1], 'XE')) {
                        $item = RegistroFiscal::create([
                            'rfc' => $arr[1] ?? '',
                            'razon_social' => utf8_encode(trim($arr[3])) ?? '',
                            'razon_social_cfdi_40' => utf8_encode(trim($arr[4])) ?? '',
                            'calle' => utf8_encode(trim($arr[5])) ?? '',
                            'num_ext' => $arr[6] ?? '',
                            'num_int' => $arr[7] ?? '',
                            'colonia' => utf8_encode(trim($arr[8])) ?? '',
                            'localidad' => utf8_encode(trim($arr[9])) ?? '',
                            'estado' => $arr[10] ?? '',
                            'codigo_postal' => $arr[12] ?? '',
                            'old_regfis_id' => (int)trim($arr[0]),
                            'regimen_fiscal_id' => (int) ($arr[18] ?? 0),
                            'email1' => strtolower(trim($arr[13])) ?? '',
                            'email2' => strtolower(trim($arr[14])) ?? '',
                        ]);
                    }


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
