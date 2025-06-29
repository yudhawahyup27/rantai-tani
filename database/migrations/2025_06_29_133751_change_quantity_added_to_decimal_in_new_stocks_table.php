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
        Schema::table('new_stocks', function (Blueprint $table) {
            $table->decimal('quantity_added', 10, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('new_stocks', function (Blueprint $table) {
            $table->integer('quantity_added')->change();
        });
    }
};
