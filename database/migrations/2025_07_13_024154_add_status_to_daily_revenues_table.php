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
        Schema::table('daily_revenues', function (Blueprint $table) {

            $table->string('status')->default('belum dibayar')->after('id_user'); // Adding status column with default value 'pending'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_revenues', function (Blueprint $table) {
            $table->dropColumn('status'); // Dropping the status column
        });
    }
};
