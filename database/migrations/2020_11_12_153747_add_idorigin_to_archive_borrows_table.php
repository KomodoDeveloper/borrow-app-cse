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
        Schema::table('archive_borrows', function (Blueprint $table) {
            $table->integer('origin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archive_borrows', function (Blueprint $table) {
            $table->dropColumn('origin_id');

        });
    }
};
