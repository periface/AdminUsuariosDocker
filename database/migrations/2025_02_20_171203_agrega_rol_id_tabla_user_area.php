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
        Schema::table('user_area', function(Blueprint $table){
            $table->unsignedBigInteger('rolId');
            $table->foreign('rolId')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
