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
        Schema::create('takeovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade'); // investor lama
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // pengirim saham
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade'); // penerima saham
            $table->foreignId('tossa_id')->constrained()->onDelete('cascade');
            $table->integer('perlot'); // jumlah lot yang ditransfer
            $table->integer('harga_takeover'); // harga per lot
            $table->integer('total'); // total harga = perlot * harga_takeover
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
        Schema::dropIfExists('takeovers');
    }
};
