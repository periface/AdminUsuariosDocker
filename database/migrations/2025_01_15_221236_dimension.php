<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("dimension", function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("descripcion");
            $table->boolean("status")->default(true);
            $table->unsignedBigInteger("secretariaId");
            $table->foreign("secretariaId")->references("id")->on("secretaria")->onDelete("cascade");
            $table->string("secretaria");
            $table->timestamps();
        });

        Schema::create("indicador_categoria", function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("descripcion");
            $table->boolean("status")->default(true);
            $table->unsignedBigInteger("secretariaId");
            $table->foreign("secretariaId")->references("id")->on("secretaria")->onDelete("cascade");
            $table->string("secretaria");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("dimension");
    }
};

