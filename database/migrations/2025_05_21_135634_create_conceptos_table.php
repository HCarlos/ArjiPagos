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
        Schema::create('conceptosdepagos', function (Blueprint $table) {
            $table->id();
            $table->string("concepto",250)->default("")->unique();
            $table->string("concepto_unico",100)->default("");
            $table->string("tag",20)->default("");
            $table->boolean('status_concepto')->default(true);
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('old_concepto_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('conceptosdepagos');
    }
};
