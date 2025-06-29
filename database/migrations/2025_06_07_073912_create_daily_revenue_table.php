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
     Schema::create('daily_revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tossa_id');
            $table->unsignedBigInteger('product_id');
            $table->date('date');
            $table->string('shift');

            $table->integer('stock_start')->default(0);
            $table->integer('sold_quantity')->default(0);
            $table->integer('stock_end')->default(0);

            $table->bigInteger('start_value')->default(0);  // total harga awal stok
            $table->bigInteger('sold_value')->default(0);   // total harga barang terjual
            $table->bigInteger('end_value')->default(0);    // total harga stok sisa

            $table->bigInteger('revenue')->default(0);      // omset (bisa sama dengan sold_value)

            $table->timestamps();

            // Foreign keys
            $table->foreign('tossa_id')->references('id')->on('tossas')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Index untuk pencarian cepat
            $table->index(['tossa_id', 'date', 'shift', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_revenues');
    }
};
