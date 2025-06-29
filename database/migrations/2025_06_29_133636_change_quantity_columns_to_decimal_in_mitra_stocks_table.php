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
        Schema::table('mitra_stocks', function (Blueprint $table) {
            $table->decimal('sold_quantity', 10, 2)->change();
            $table->decimal('stock_start', 10, 2)->change();
            $table->decimal('stock_end', 10, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('mitra_stocks', function (Blueprint $table) {
            $table->integer('sold_quantity')->change();
            $table->integer('stock_start')->change();
            $table->integer('stock_end')->change();
        });
    }
};
