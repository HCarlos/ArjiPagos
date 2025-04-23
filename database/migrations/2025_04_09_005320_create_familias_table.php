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

        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->string('familia', 250)->default('');
            $table->text('observaciones_control_escolar', 250)->default('');
            $table->text('observaciones_pagos', 250)->default('');
            $table->text('convenios', 250)->default('');
            $table->string('email_pagos', 250)->default('');
            $table->string('email_facturas', 250)->default('');
            $table->string('email_control_escolar', 250)->default('');
            $table->string('email_convenios', 250)->default('');
            $table->unsignedInteger('old_familia_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($tableNamesCatalogos['familia_user_role'], function (Blueprint $table) use ($tableNamesCatalogos){
            $table->increments('id');
            $table->unsignedInteger('familia_id')->default(0)->index();
            $table->unsignedInteger('user_id')->default(0)->index();
            $table->unsignedInteger('role_id')->default(0)->index();
            $table->unsignedInteger('tutor_id')->default(0)->index();
            $table->unsignedInteger('vivecon_id')->default(0)->index();
            $table->boolean('es_menor')->default(false)->index();
            $table->softDeletes();
            $table->timestamps();
            $table->unique([ 'familia_id','user_id','role_id']);

            $table->foreign('familia_id')
                ->references('id')
                ->on($tableNamesCatalogos['familias'])
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on($tableNamesCatalogos['users'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNamesCatalogos['roles'])
                ->onDelete('cascade');


        });

        Schema::create($tableNamesCatalogos['familia_registrofiscal'], function (Blueprint $table) use ($tableNamesCatalogos){
            $table->increments('id');
            $table->unsignedInteger('familia_id')->default(0)->index();
            $table->unsignedInteger('registrofiscal_id')->default(0)->index();
            $table->boolean('predeterminado')->default(false)->index();
            $table->softDeletes();
            $table->timestamps();
            $table->unique([ 'familia_id','registrofiscal_id']);

            $table->foreign('familia_id')
                ->references('id')
                ->on($tableNamesCatalogos['familias'])
                ->onDelete('cascade');

            $table->foreign('registrofiscal_id')
                ->references('id')
                ->on($tableNamesCatalogos['registros_fiscales'])
                ->onDelete('cascade');
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('familia_user_role');
        Schema::dropIfExists('familia_registrofiscal');
        Schema::dropIfExists('familias');
    }
};
