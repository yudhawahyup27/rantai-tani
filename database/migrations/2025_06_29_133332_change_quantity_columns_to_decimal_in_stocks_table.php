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
         Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->default(0)->change();
            $table->decimal('quantity_new', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
             Schema::table('stocks', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->change();
            $table->integer('quantity_new')->default(0)->change();
        });
    }
};
