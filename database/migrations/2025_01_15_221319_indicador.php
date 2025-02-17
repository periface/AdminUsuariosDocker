<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::create("indicador", function (Blueprint $table) {
            $table->id();
            $table->string("clave")->unique();
            $table->unsignedBigInteger("dimensionId");
            $table->string("nombre");
            $table->text("descripcion");
            $table->string("unidad_medida");
            $table->text("metodo_calculo")->nullable();
            $table->string("evaluable_formula")->nullable();
            $table->string("non_evaluable_formula")->nullable();
            $table->string("sentido");
            $table->boolean('indicador_confirmado')->default(false);
            $table->boolean("status")->default(true);
            $table->foreign("dimensionId")->references("id")->on("dimension")->onDelete("cascade");
            $table->unsignedBigInteger("secretariaId");
            $table->foreign("secretariaId")->references("id")->on("secretaria")->onDelete("cascade");
            $table->string("secretaria");
            $table->string("medio_verificacion");
            $table->boolean("requiere_anexo")->default(false);
            $table->string("categoria")->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("indicador");
    }
};
