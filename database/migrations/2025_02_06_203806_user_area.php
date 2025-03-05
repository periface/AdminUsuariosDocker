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
        //
        Schema::create('user_area', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('areaId');
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('areaId')->references('id')->on('area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('user_area');
    }
};
