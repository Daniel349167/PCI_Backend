<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('damages', function (Blueprint $table) {
            // Cambiar el tipo de dato de la columna 'amount' a decimal con 1 decimal
            $table->decimal('amount', 8, 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damages', function (Blueprint $table) {
            // Revertir el tipo de dato de la columna 'amount' a su tipo original 'numeric'
            $table->integer('amount')->nullable()->change();
        });
    }
};
