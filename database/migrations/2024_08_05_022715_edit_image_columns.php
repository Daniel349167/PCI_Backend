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
        Schema::table('projects', function (Blueprint $table) {
            $table->longtext('image')->nullable()->change();
        });
        Schema::table('samples', function (Blueprint $table) {
            $table->longtext('image')->nullable()->change();
        });
        Schema::table('damages', function (Blueprint $table) {
            $table->longtext('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });
        Schema::table('samples', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });
        Schema::table('damages', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });
    }
};
