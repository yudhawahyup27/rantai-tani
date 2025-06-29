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
        Schema::create('mitra_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tossa_id')->constrained('tossas')->onDelete('cascade'); // mitra
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('sold_quantity');
            $table->integer('stock_start');
            $table->integer('stock_end');
            $table->enum('shifts', ['pagi','sore']);
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
        Schema::dropIfExists('mitra_stocks');
    }
};
