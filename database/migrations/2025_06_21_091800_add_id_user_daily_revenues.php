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
                if (!Schema::hasColumn('daily_revenues', 'id_user')) {
            $table->foreignId('id_user')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');
        } else {
            // Add FK only if column exists but constraint doesn't
            $table->foreign('id_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        }
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
           $table->dropForeign(['id_user']);
        });
    }
};
