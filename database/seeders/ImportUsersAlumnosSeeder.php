<?php

namespace Database\Seeders;

use App\Classes\FuncionesController;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ImportUsersAlumnosSeeder extends Seeder
{
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
        $file = 'public/csv/_vi_Alumnos.csv';
        $json_data = file_get_contents($file);
        $json_data = preg_split( "/\n/", $json_data );

        for ($x = 1, $xMax = count($json_data); $x < $xMax; $x++){
            try{

                $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
                $arr = str_getcsv($dupla[0]);

//                dd($arr);

                $username = strtolower(trim($arr[36]));
                $password = bcrypt($username);
                $email = $username."@mail.com";
                $ap_paterno = strtoupper(trim($arr[2])) === "." ? "" : strtoupper(trim($arr[2]));
                $ap_materno = strtoupper(trim($arr[3])) === "." ? "" : strtoupper(trim($arr[3]));
                $nombre = strtoupper(trim($arr[4])) === "." ? "" : strtoupper(trim($arr[4]));
                $curp = strtoupper(trim($arr[21]));
                $emails = strtolower(trim($arr[9]));
                $celulares = "";
                $telefonos = "";
                if (substr(trim($arr[11]), 0, 4) == "0000" || $arr[11] == "") {
                    $fecha_nacimiento = Carbon::now('America/Mexico_City')->format('Y-m-d');
                }else{
                    $fecha_nacimiento = trim($arr[11]);
                }

                if (substr(trim($arr[13]), 0, 4) == "0000" || $arr[13] == "") {
                    $fecha_ingreso = Carbon::now('America/Mexico_City')->format('Y-m-d');
                }else{
                    $fecha_ingreso = trim($arr[13]);
                }

                $genero = (int) trim($arr[19]);

                if (trim($arr[1]) === "NULL" || trim($arr[1]) === "") {
                    $old_user_id = 0;
                    $username = substr($ap_paterno,0,2).substr($ap_materno,0,2).substr($nombre,0,2).str(random_int(1000,9999));
                    $password = bcrypt($username);
                    $email = $username."@mail.com";
                }else{
                    $old_user_id = (int) trim($arr[1]);
                }

                $old_persona_id = 0;
                $old_alumno_id = (int) trim($arr[0]);

                $user = User::create([
                'username'         => $username,
                'email'            => $email,
                'password'         => $password,
                'nombre'           => $nombre,
                'ap_paterno'       => $ap_paterno,
                'ap_materno'       => $ap_materno,
                'curp'             => $curp,
                'emails'           => $emails,
                'celulares'        => $celulares,
                'telefonos'        => $telefonos,
                'fecha_nacimiento' => $fecha_nacimiento,
                'genero'           => $genero,
                'old_user_id'      => $old_user_id,
                'old_persona_id'   => $old_persona_id,
                'old_alumno_id'    => $old_alumno_id,
                ]);

                //para alumnos
                $user->roles()->attach(12);

                $user->permisos()->attach(7);

                $user->user_alumno()->create([
                    'matricula_interna' => trim($arr[7]),
                    'matricula_oficial' => trim($arr[8]),
                    'email_alumno' => trim($arr[9]),
                    'enfermedades' => trim($arr[28]),
                    'reacciones_alergicas' => trim($arr[29]),
                    'tipo_sangre' => trim($arr[27]),
                    'beca_sep' => (float) trim($arr[15]),
                    'beca_arji' => (float) trim($arr[16]),
                    'beca_sp' => (float) trim($arr[17]),
                    'beca_bach' => (float) trim($arr[18]),
                    'es_baja' => !((int)trim($arr[24]) === 0),
                    'motivo_baja' => trim($arr[26]) === 'NULL' ? '' : trim($arr[26]),
                    'fecha_ingreso' => $fecha_ingreso,
                    'observaciones' =>  trim($arr[38]),
                ]);

                $user->user_adress()->create([
                    'calle' => '',
                    'num_ext' => '',
                    'num_int' => '',
                    'colonia' => '',
                    'delegacion' => '',
                    'municipio' => '',
                    'estado' => '',
                    'pais' => '',
                    'cp' => '',
                ]);

                $user->user_data_extend()->create([
                    'ocupacion' => '',
                    'profesion' => '',
                    'lugar_trabajo' => '',
                    'lugar_nacimiento' => trim($arr[10]),
                    'nacionalidad' => trim($arr[20]),
                ]);
                $F->validImage($user,'profile','profile/');


            }catch (QueryException $e){
                Log::alert("Error en :: ".$username." - ".$e->getMessage());
                continue;
            }catch (Exception $e){
                Log::alert("Error en ".$username." - ".$e->getMessage());
//                throw new \RuntimeException($e);
                continue;
//                exit;
            }
        }



    }



}
