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
        Schema::create('mastersahams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tossa_id')->constrained('tossas')->onDelete('cascade');
            $table->integer('sahamtersedia')->nullable();
            $table->integer('sahamterjual')->nullable();
            $table->integer('totallot')->nullable();
            $table->integer('persentase')->nullable()->default(0);
            $table->decimal('harga', 15, 2)->nullable()->default(0);
            $table->decimal('total', 15, 2)->nullable()->default(0);
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
        Schema::dropIfExists('mastersahams');
    }
};
