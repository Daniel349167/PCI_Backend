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
            $table->dateTime('deleted_at')->nullable()->after('image');
        });
        Schema::table('damages', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
        Schema::table('damages', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
    }
};
