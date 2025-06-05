<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\EmisorFiscal;
use App\Models\Catalogos\RegistroFiscal;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportEmisoresFiscalesSeeder extends Seeder
{
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
        $file = 'public/csv/_vi_cat_emisores_fiscales.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                if ($dupla[0] !== 'Undefined' ) {

                    $arr = str_getcsv($dupla[0]);

                    $item = EmisorFiscal::create([
                        'rfc' => $arr[1] ?? '',
                        'razon_social' => utf8_encode(trim($arr[2])) ?? '',
                        'razon_social_cfdi_40' => utf8_encode(trim($arr[3])) ?? '',
                        'calle' => utf8_encode(trim($arr[4])) ?? '',
                        'num_ext' => $arr[5] ?? '',
                        'num_int' => $arr[6] ?? '',
                        'colonia' => utf8_encode(trim($arr[7])) ?? '',
                        'localidad' => utf8_encode(trim($arr[8])) ?? '',
                        'estado' => $arr[9] ?? '',
                        'pais' => $arr[10] ?? '',
                        'codigo_postal' => $arr[11] ?? '',
                        'serie' => utf8_encode(trim($arr[12])) ?? '',
                        'tipo_comprobante' => (int) ($arr[13] ?? 0),
                        'is_iva' => (int) ($arr[14] ?? 0) === 1,
                        'estatus_emisor_fiscal' =>  (int) ($arr[15] ?? 0) === 1,
                        'old_emisorfiscal_id' => (int)trim($arr[0]),
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
