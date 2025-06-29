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
       Schema::create('new_stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
        $table->integer('quantity_added');
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
        Schema::dropIfExists('new_stocks');
    }
};
