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
        Schema::table('samples', function($table) {
            $table->char('section', 1)->nullable()->after('image');
            $table->integer('to_m')->nullable()->after('image');
            $table->integer('to_km')->nullable()->after('image');
            $table->integer('from_m')->nullable()->after('image');
            $table->integer('from_km')->nullable()->after('image');
        });
        Schema::table('surveys', function($table) {
            $table->integer('amount')->nullable()->after('image');
            $table->integer('severity')->nullable()->after('image');
            $table->integer('type')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('samples', function($table) {
            $table->dropColumn('from_km');
            $table->dropColumn('from_m');
            $table->dropColumn('to_km');
            $table->dropColumn('to_m');
            $table->dropColumn('section');
        });
        Schema::table('surveys', function($table) {
            $table->dropColumn('type');
            $table->dropColumn('severity');
            $table->dropColumn('amount');
        });
    }
};
