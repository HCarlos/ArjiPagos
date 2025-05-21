<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{

        $tableNamesCatalogos = config('arjiapp.table_names.catalogos');

        Schema::create('ciclos', function (Blueprint $table) {
            $table->id();
            $table->string('ciclo', 250)->default('');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('predeterminado')->default(false);
            $table->integer('ciclo_anterior_id')->default(0)->index();
            $table->integer('ano_anterior')->default(0)->index();
            $table->integer('ano_siguiente')->default(0)->index();
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('status_ciclo')->default(1)->index()->comment('1:activo, 0:inactivo');
            $table->integer('old_ciclo_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('niveles', function (Blueprint $table) {
            $table->id();
            $table->string('clave_nivel', 2)->default('');
            $table->string('nivel', 250)->default('');
            $table->string('clave_registro_nivel', 250)->default('');
            $table->string('nivel_oficial', 250)->default('');
            $table->string('nivel_fiscal', 250)->default('');
            $table->string('prefijo_evaluacion', 10)->default('');
            $table->smallInteger('numero_evaluaciones')->default(3);
            $table->string('fecha_actas', 15)->default('');
            $table->integer('status_nivel')->default(1)->index()->comment('1:activo, 0:inactivo');
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('old_nivel_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });





    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('ciclos');
        Schema::dropIfExists('niveles');
    }
};
