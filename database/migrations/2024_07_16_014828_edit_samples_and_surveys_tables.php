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
            $table->boolean('section')->nullable()->after('image');
            $table->string('to')->nullable()->after('image');
            $table->string('from')->nullable()->after('image');
        });
        Schema::table('surveys', function($table) {
            $table->integer('quantity')->nullable()->after('image');
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
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->dropColumn('section');
        });
        Schema::table('surveys', function($table) {
            $table->dropColumn('type');
            $table->dropColumn('severity');
            $table->dropColumn('quantity');
        });
    }
};
