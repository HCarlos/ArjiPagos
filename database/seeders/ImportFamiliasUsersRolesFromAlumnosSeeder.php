<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\Catalogos\Familia;
use App\Models\User;
use App\Models\User\Role;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportFamiliasUsersRolesFromAlumnosSeeder extends Seeder{

    /**
     * Run the database seeds.
     */
    public function run(): void{

        @ini_set( 'upload_max_size' , '32768M' );
        @ini_set( 'post_max_size', '32768M');
        @ini_set( 'max_execution_time', '256000000' );
        @ini_set('memory_limit', '-1');

        $F = new FuncionesController();

        /* ************************************************************************************************
                //SUBIMOS DEPENDENCIAS
        ************************************************************************************************** */
        $file = 'public/csv/_vi_familia_alumnos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                $arr = str_getcsv($dupla[0]);

//                dd($arr);

                $old_familia_id = (int) trim($arr[1]);
                $old_alumno_id = (int) trim($arr[2]);
                $tutor_id = (int) trim($arr[3]);
                $vivecon_id = (int) trim($arr[3]);
                $es_menor = (int) trim($arr[4]) === 1;
                $role_id = 12;

                if ($old_familia_id > 0 && $old_alumno_id > 0 && $role_id > 0) {
                    $fam = Familia::query()
                        ->where('old_familia_id', $old_familia_id)
                        ->first();

                    $user = User::query()
                        ->where('old_alumno_id', $old_alumno_id)
                        ->first();

                    $role = Role::query()
                        ->where('id', $role_id)
                        ->first();

                    if ($role !== null && $user !== null && $fam !== null) {
                        $fam->users()->attach($user,[
                            'role_id' => $role->id,
                            'tutor_id' => $tutor_id,
                            'vivecon_id' => $vivecon_id,
                            'es_menor' => $es_menor,
                        ]);
                    }

                }
            }catch (QueryException $e){
                Log::alert("Error en :: FAM ID ".$fam->id." - ".$e->getMessage());
                continue;
            }catch (Exception $e){
                Log::alert("Error en Role ID ".$role_id." - ".$e->getMessage());
                continue;
            }
        }



    }



}
