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
    public function up()
    {
        Schema::create('laporan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tossa_id')->constrained('tossas')->onDelete('cascade');
            $table->foreignId('id_Pic')->constrained('users')->onDelete('cascade');
            $table->decimal('daganganBaru', 12, 2)->default(0);
            $table->decimal('gajikaryawan', 12, 2)->default(0);
            $table->decimal('pengeluaran', 12, 2)->default(0); // corrected casing
            $table->decimal('daganganlakuterjual', 12, 2)->default(0); // corrected typo
            $table->decimal('labakotor', 12, 2)->default(0);
            $table->decimal('ravenue', 12, 2)->default(0);
            $table->decimal('margin', 12, 2)->default(0);
            $table->decimal('grosMargin', 12, 2)->default(0);
            $table->decimal('sewaTossa', 12, 2)->default(0);
            $table->decimal('labaBersih', 12, 2)->default(0);
            $table->decimal('labaDibawa', 12, 2)->default(0);
            $table->decimal('rangelabakotor', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan_keuangans');
    }
};
