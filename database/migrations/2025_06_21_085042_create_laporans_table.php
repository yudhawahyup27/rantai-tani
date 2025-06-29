<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('tossa_id')->constrained('tossas')->onDelete('cascade');
            $table->decimal('labaSayur', 12, 2)->default(0);
            $table->decimal('labaBuah', 12, 2)->default(0);
            $table->decimal('labaGaringan', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('passiveIncome', 12, 2)->default(0); // corrected casing
            $table->decimal('totalLabaBahanBaku', 12, 2)->default(0); // corrected typo
            $table->decimal('totalLabaKeseluruhan', 12, 2)->default(0); // corrected typo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};
