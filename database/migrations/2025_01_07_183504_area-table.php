<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function __construct()
    {
        $this->withinTransaction = false;
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('secretaria', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('siglas');
            $table->string('type')->default('SECRETARIA');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        Schema::create('area', function (BluePrint $table) {
            $table->id();
            $table->string('nombre', 65);
            $table->unsignedBigInteger('responsableId')->nullable();
            $table->string('siglas', 25);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('secretariaId');
            $table->timestamps();
            $table->foreign('responsableId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('secretariaId')->references('id')->on('secretaria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area');
        Schema::dropIfExists('secretaria');
    }
};
