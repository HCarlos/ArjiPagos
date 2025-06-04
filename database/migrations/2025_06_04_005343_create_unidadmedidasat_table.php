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
        Schema::create('unidadmedidasat', function (Blueprint $table) {
            $table->id();
            $table->string('clave',50)->default('')->unique();
            $table->string('descripcion',50)->default('');
            $table->boolean('status_unidadmedida')->default(true);
            $table->integer('empresa_id')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('unidadmedidasat');
    }
};
