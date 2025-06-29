<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mitra_stocks', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn(['sold_quantity', 'stock_start', 'stock_end']);
        });

        Schema::table('mitra_stocks', function (Blueprint $table) {
            // Tambah kolom baru dengan tipe decimal
            $table->decimal('sold_quantity', 10, 2)->default(0);
            $table->decimal('stock_start', 10, 2)->default(0);
            $table->decimal('stock_end', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('mitra_stocks', function (Blueprint $table) {
            $table->dropColumn(['sold_quantity', 'stock_start', 'stock_end']);
        });

        Schema::table('mitra_stocks', function (Blueprint $table) {
            $table->integer('sold_quantity')->default(0);
            $table->integer('stock_start')->default(0);
            $table->integer('stock_end')->default(0);
        });
    }
};
