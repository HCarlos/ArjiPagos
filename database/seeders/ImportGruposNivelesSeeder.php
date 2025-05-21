<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Grupo;
use App\Models\Catalogos\Nivel;
use App\Models\User;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportGruposNivelesSeeder extends Seeder{

    public function run(): void{

        @ini_set( 'upload_max_size' , '32768M' );
        @ini_set( 'post_max_size', '32768M');
        @ini_set( 'max_execution_time', '256000000' );
        @ini_set('memory_limit', '-1');

        $F = new FuncionesController();

        /* ************************************************************************************************
                //SUBIMOS DEPENDENCIAS
        ************************************************************************************************** */
        $file = 'public/csv/_vi_nivel_grupos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                $arr = str_getcsv($dupla[0]);

//                dd($arr);

                $old_grupo_nivel_id = (int) trim($arr[0]);
                $old_ciclo_id        = (int) trim($arr[1]);
                $old_nivel_id        = (int) trim($arr[2]);
                $old_grupo_id        = (int) trim($arr[3]);
                $is_visible   = (int) trim($arr[4]) === 1;

                if ($old_ciclo_id > 0 && $old_grupo_id > 0 && $old_nivel_id > 0) {

                    $cic = Ciclo::query()
                        ->where('old_ciclo_id', $old_ciclo_id)
                        ->first();

                    $niv = Nivel::query()
                        ->where('old_nivel_id', $old_nivel_id)
                        ->first();

                    $grupo = Grupo::query()
                        ->where('old_grupo_id', $old_grupo_id)
                        ->first();

//                    dd($grupo);

                    if ($grupo !== null && $niv !== null && $cic !== null) {

                        $grupo->niveles()->attach($niv,[
                            'ciclo_id'           => $cic->id,
                            'is_visible'         => $is_visible,
                            'old_grupo_nivel_id' => $old_grupo_nivel_id,
                            'old_ciclo_id'       => $old_ciclo_id,
                            'old_grupo_id'       => $old_grupo_id,
                            'old_nivel_id'       => $old_nivel_id,
                        ]);

                    }

                }
            }catch (QueryException $e){
                Log::alert("Error en :: old_grupo_nivel_id ID :: ".$old_grupo_nivel_id." - ".$e->getMessage());
                continue;
            }catch (Exception $e){
                Log::alert("Error en old_grupo_nivel_id ID ".$old_grupo_nivel_id." - ".$e->getMessage());
                continue;
            }
        }

    }

}
