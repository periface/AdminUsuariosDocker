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
        Schema::table('roles', function (Blueprint $table) {
            $table->string('alias')->nullable();
            $table->text('description')->nullable();
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
