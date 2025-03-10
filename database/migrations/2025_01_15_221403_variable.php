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
        Schema::create("variable", function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("code");
            $table->unsignedBigInteger("indicadorId");
            $table->foreign("indicadorId")->references("id")->on("indicador")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("variable");
    }
};

