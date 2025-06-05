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

        Schema::create('emisoresfiscales', function (Blueprint $table) {
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
            $table->string("serie",10)->default('A');
            $table->unsignedInteger("tipo_comprobante")->default(0);
            $table->boolean('is_iva')->default(false);
            $table->boolean('estatus_emisor_fiscal')->default(true);
            $table->integer('old_emisorfiscal_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('emisoresfiscales');
    }
};
