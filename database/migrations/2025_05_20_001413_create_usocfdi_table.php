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
        Schema::create('usocfdi', function (Blueprint $table) {
            $table->id();
            $table->string("clave_usocfdi",25)->unique();
            $table->string("usocfdi",250)->default("");
            $table->integer('old_usocfdi_id')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('usocfdi');
    }
};
