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
        Schema::create("evaluacion", function (Blueprint $table) {
            $table->id();
            $table->decimal("rendimiento")->nullable();
            $table->boolean("meta_alcanzada")->nullable();
            $table->unsignedBigInteger("areaId");
            $table->unsignedBigInteger("indicadorId");
            $table->string("frecuencia_medicion");
            $table->string("meta");
            $table->date("fecha_fin");
            $table->date("fecha_inicio");
            $table->unsignedBigInteger("usuarioId");
            $table->string("evaluable_formula");
            $table->string("non_evaluable_formula");
            $table->string("formula_literal");
            $table->string("descripcion");
            $table->boolean("finalizado")->default(false);
            $table->unsignedBigInteger("finalizado_por")->nullable();
            $table->date("finalizado_en")->nullable();
            $table->foreign("areaId")->references("id")->on("area")->onDelete("cascade");
            $table->foreign("indicadorId")->references("id")->on("indicador")->onDelete("cascade");
            $table->foreign("finalizado_por")->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
        });
        Schema::create("evaluacion_result", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("resultNumber");
            $table->unsignedBigInteger("evaluacionId");
            $table->decimal("resultado")->nullable();
            $table->string("status")->default("capturado"); // capturado, aprobado, rechazado
            $table->unsignedBigInteger("aprobadoPorId")->nullable();
            $table->date("fecha");
            $table->foreign("evaluacionId")->references("id")->on("evaluacion")->onDelete("cascade");
            $table->foreign("aprobadoPorId")->references("id")->on("users")->onDelete("cascade");
            $table->string("used_formula")->nullable();
            $table->string("motivo")->nullable();
            $table->timestamps();
        });
        Schema::create("variable_valor", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("valor");
            $table->unsignedBigInteger("meta_esperada");
            $table->date("fecha");
            $table->string("status")->default("capturado"); // capturado, aprobado, rechazado
            $table->unsignedBigInteger("evaluacionId");
            $table->unsignedBigInteger("variableId");
            $table->unsignedBigInteger("usuarioId");
            $table->unsignedBigInteger("evaluacionResultId")->nullable();
            $table->foreign("evaluacionResultId")->references("id")->on("evaluacion_result")->onDelete("cascade");
            $table->foreign("evaluacionId")->references("id")->on("evaluacion")->onDelete("cascade");
            $table->foreign("variableId")->references("id")->on("variable")->onDelete("cascade");
            $table->foreign("usuarioId")->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("variable_valor");
        Schema::dropIfExists("evaluacion");
    }
};
