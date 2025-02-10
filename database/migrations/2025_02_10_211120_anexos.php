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
        Schema::create("anexos", function (Blueprint $table) {
            $table->id();
            $table->string("fileName", 255);
            $table->string("filePath", 255);
            $table->string("fileType", 255);
            $table->string("fileSize", 255);
            $table->string("fileExtension", 255);
            $table->string("fileDescription", 255);
            $table->string("fileStatus", 255);
            $table->unsignedBigInteger("evaluacionResultId");
            $table->timestamps();
            $table->foreign("evaluacionResultId")->references("id")->on("evaluacion_result");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("anexos");
    }
};
