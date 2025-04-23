<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{

        $tableNamesCatalogos = config('arjiapp.table_names.catalogos');
        $tableNames = config('arjiapp.table_names.registros_fiscales');


        Schema::create('regimenes_fiscales', function (Blueprint $table) {
            $table->id();
            $table->string("clave_regimen_fiscal",10)->unique();
            $table->string("regimen_fiscal",250)->default('');
            $table->integer('old_regimen_fiscal_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('registros_fiscales', function (Blueprint $table)  use ($tableNamesCatalogos){
            $table->id();
            $table->string("rfc",20)->unique();
            $table->string("razon_social",250)->default('');
            $table->string("razon_social_cfdi_40",250)->default('');
            $table->string("calle",250)->default('');
            $table->string("num_ext",25)->default('');
            $table->string("num_int",25)->default('');
            $table->string("colonia",250)->default('');
            $table->string("localidad",250)->default('');
            $table->string("estado",100)->default('');
            $table->string("pais",100)->default('México');
            $table->string("codigo_postal",10)->default('');
            $table->unsignedInteger("regimen_fiscal_id")->index();
            $table->string("email1",250)->default('');
            $table->string("email2",250)->default('');
            $table->boolean('es_extranjero')->default(false);
            $table->integer('old_regfis_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('regimen_fiscal_id')
                ->references('id')
                ->on($tableNamesCatalogos['regimenes_fiscales'])
                ->onDelete('cascade');

        });

        DB::statement("ALTER DATABASE dbarji set default_text_search_config = 'spanish'");
        DB::statement("ALTER TABLE registros_fiscales ADD COLUMN searchtext_regfis TSVECTOR");
        DB::statement("UPDATE registros_fiscales SET searchtext_regfis = to_tsvector('spanish', coalesce(trim(rfc),'') || ' ' || coalesce(trim(razon_social),'') || ' ' || coalesce(trim(calle),'') || ' ' || coalesce(trim(colonia),'')  || ' ' || coalesce(trim(localidad),'') )");
        DB::statement("CREATE INDEX regfis_searchtext_gin ON registros_fiscales USING GIN(searchtext_regfis)");
        DB::statement("CREATE TRIGGER ts_searchtext_regfis1 BEFORE INSERT OR UPDATE ON registros_fiscales FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext_regfis', 'pg_catalog.spanish', 'rfc', 'razon_social', 'calle', 'colonia', 'localidad')");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regimenes_fiscales');
        Schema::dropIfExists('registros_fiscales');
    }
};
