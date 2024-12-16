<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('archive_borrows', function (Blueprint $table) {
            $table->id();
            $table->string('a_first_name_borrower')->nullable();
            $table->string('a_surname_borrower')->nullable();
            $table->string('a_email_borrower');
            $table->integer('a_equipment_id');
            $table->date('a_start_date');
            $table->date('a_end_date');
            $table->string('a_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_borrows');
    }
};
