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
        Schema::table('equipments', function (Blueprint $table) {
            $table->string('product_year')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('expiration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('product_year');
            $table->dropColumn('purchase_date');
            $table->dropColumn('expiration_date');
        });
    }
};
