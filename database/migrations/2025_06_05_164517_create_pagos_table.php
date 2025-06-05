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

        Schema::create('pagos', function (Blueprint $table) use ($tableNamesCatalogos){
            $table->id();
            $table->unsignedInteger('nivel_id')->nullable(false)->index();
            $table->unsignedInteger('emisorfiscal_id')->nullable(false)->index();
            $table->unsignedInteger('concepto_id')->nullable(false)->index();
            $table->decimal('importe',12,4)->default(0);
            $table->unsignedSmallInteger('dia_limite')->default(0);
            $table->unsignedSmallInteger('dia_de_pago')->default(0);
            $table->boolean('tiene_descuento_beca')->default(false);
            $table->boolean('tiene_descuento')->default(false);
            $table->boolean('acepta_pagos_diversos')->default(false);
            $table->boolean('aplica_al_siguiente_nivel')->default(false);
            $table->boolean('aplica_a')->default(false);
            $table->boolean('tiene_descuento_por_promocion')->default(false);
            $table->boolean('es_facturable')->default(false);
            $table->boolean('se_puede_ver_en_pantalla')->default(false);
            $table->unsignedInteger('orden_prioridad')->default(0);
            $table->unsignedInteger('prodservsat_id')->nullable(false)->index();
            $table->unsignedInteger('unidadmedidasat_id')->nullable(false)->index();
            $table->string('tagpagos',50)->default('');
            $table->boolean('estatus_pagos')->default(true);
            $table->integer('old_pago_id')->default(0)->index();
            $table->integer('empresa_id')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nivel_id')
                ->references('id')
                ->on($tableNamesCatalogos['niveles'])
                ->onDelete('cascade');

            $table->foreign('emisorfiscal_id')
                ->references('id')
                ->on($tableNamesCatalogos['emisoresfiscales'])
                ->onDelete('cascade');

            $table->foreign('concepto_id')
                ->references('id')
                ->on($tableNamesCatalogos['conceptosdepagos'])
                ->onDelete('cascade');

            $table->foreign('prodservsat_id')
                ->references('id')
                ->on($tableNamesCatalogos['prodservsat'])
                ->onDelete('cascade');

            $table->foreign('unidadmedidasat_id')
                ->references('id')
                ->on($tableNamesCatalogos['unidadmedidasat'])
                ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('pagos');
    }
};
