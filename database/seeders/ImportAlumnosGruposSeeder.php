<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Ciclo;
use App\Models\Catalogos\Familia;
use App\Models\Catalogos\Grupo;
use App\Models\User;
use App\Models\User\Role;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportAlumnosGruposSeeder extends Seeder{

    public function run(): void{

        @ini_set( 'upload_max_size' , '32768M' );
        @ini_set( 'post_max_size', '32768M');
        @ini_set( 'max_execution_time', '256000000' );
        @ini_set('memory_limit', '-1');

        $F = new FuncionesController();

        /* ************************************************************************************************
                //SUBIMOS DEPENDENCIAS
        ************************************************************************************************** */
        $file = 'public/csv/_vi_grupo_alumnos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                $arr = str_getcsv($dupla[0]);

//                dd($arr);

                $old_alumno_grupo_id = (int) trim($arr[0]);
                $old_ciclo_id        = (int) trim($arr[1]);
                $old_grupo_id        = (int) trim($arr[2]);
                $old_alumno_id       = (int) trim($arr[3]);
                $num_lista           = (int) trim($arr[4]);
                $is_visible_boleta   = (int) trim($arr[5]) === 1;
                $clave_nivel         = (int) trim($arr[6]);

                if ($old_ciclo_id > 0 && $old_grupo_id > 0 && $old_alumno_id > 0) {

                    $cic = Ciclo::query()
                        ->where('old_ciclo_id', $old_ciclo_id)
                        ->first();

                    $user = User::query()
                        ->where('old_alumno_id', $old_alumno_id)
                        ->first();

                    $grupo = Grupo::query()
                        ->where('old_grupo_id', $old_grupo_id)
                        ->first();

//                    dd($grupo);

                    if ($grupo !== null && $user !== null && $cic !== null) {
                        $grupo->alumnos()->attach($user,[
                            'ciclo_id'              => $cic->id,
                            'num_lista'             => $num_lista,
                            'is_ver_boleta_interna' => $is_visible_boleta,
                            'clave_nivel'           => $clave_nivel,
                            'old_alumno_grupo_id'   => $old_alumno_grupo_id,
                            'old_ciclo_id'          => $old_ciclo_id,
                            'old_grupo_id'          => $old_grupo_id,
                            'old_alumno_user_id'    => $old_alumno_id
                        ]);

                    }

                }
            }catch (QueryException $e){
                Log::alert("Error en :: IdGruAlu ID :: ".$old_alumno_grupo_id." - ".$e->getMessage());
                continue;
            }catch (Exception $e){
                Log::alert("Error en IdGruAlu ID ".$old_alumno_grupo_id." - ".$e->getMessage());
                continue;
            }
        }

    }


}
