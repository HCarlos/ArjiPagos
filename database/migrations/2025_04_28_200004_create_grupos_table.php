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

        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('clave_grupo', 10)->default('')->unique();
            $table->string('grupo', 250)->default('');
            $table->string('grupo_oficial', 50)->default('');
            $table->string('grupo_periodo', 50)->default('');
            $table->string('grupo_periodo_ciclo', 50)->default('');
            $table->string('nivel', 50)->default('');
            $table->string('grado', 50)->default('');
            $table->string('grado_pai', 50)->default('');
            $table->string('num', 50)->default('');
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_bloqueado')->default(false);
            $table->boolean('is_activo_en_caja')->default(true);
            $table->boolean('is_ver_boleta_interna')->default(false);
            $table->boolean('is_ver_boleta_oficial')->default(false);
            $table->boolean('is_grupo_pai')->default(false);
            $table->boolean('status_grupo')->default(true);
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('grupo_siguiente_id')->default(0)->index();
            $table->integer('old_grupo_id')->default(0)->index();
            $table->unique(['clave_grupo', 'empresa_id']);
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('alumnos_grupos', function (Blueprint $table) use ($tableNamesCatalogos){
            $table->id();
            $table->integer('ciclo_id')->default(0)->index();
            $table->integer('grupo_id')->default(0)->index();
            $table->integer('alumno_user_id')->default(0)->index();
            $table->integer('num_lista')->default(0);
            $table->boolean('is_ver_boleta_interna')->default(false);
            $table->string('clave_nivel', 10)->default('');
            $table->boolean('status_grualu')->default(true);
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('old_alumno_grupo_id')->default(0)->index();
            $table->integer('old_ciclo_id')->default(0)->index();
            $table->integer('old_grupo_id')->default(0)->index();
            $table->integer('old_alumno_user_id')->default(0)->index();
            $table->index(['ciclo_id', 'grupo_id']); // Para búsquedas rápidas
            $table->unique(['ciclo_id', 'grupo_id', 'alumno_user_id']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('grupo_id')
                ->references('id')
                ->on($tableNamesCatalogos['grupos'])
                ->onDelete('cascade');

            $table->foreign('ciclo_id')
                ->references('id')
                ->on($tableNamesCatalogos['ciclos'])
                ->onDelete('cascade');

            $table->foreign('alumno_user_id')
                ->references('id')
                ->on($tableNamesCatalogos['users'])
                ->onDelete('cascade');


        });

        Schema::create('grupos_niveles', function (Blueprint $table) use ($tableNamesCatalogos){
            $table->id();
            $table->integer('ciclo_id')->default(0)->index();
            $table->integer('nivel_id')->default(0)->index();
            $table->integer('grupo_id')->default(0)->index();
            $table->boolean('is_visible')->default(false);
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('old_ciclo_id')->default(0)->index();
            $table->integer('old_nivel_id')->default(0)->index();
            $table->integer('old_grupo_id')->default(0)->index();
            $table->integer('old_grupo_nivel_id')->default(0)->index();
            $table->unique(['ciclo_id', 'nivel_id', 'grupo_id']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('grupo_id')
                ->references('id')
                ->on($tableNamesCatalogos['grupos'])
                ->onDelete('cascade');

            $table->foreign('ciclo_id')
                ->references('id')
                ->on($tableNamesCatalogos['ciclos'])
                ->onDelete('cascade');

            $table->foreign('nivel_id')
                ->references('id')
                ->on($tableNamesCatalogos['niveles'])
                ->onDelete('cascade');


        });


        }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('alumnos_grupos');
        Schema::dropIfExists('grupos_niveles');
        Schema::dropIfExists('grupos');
    }
};
