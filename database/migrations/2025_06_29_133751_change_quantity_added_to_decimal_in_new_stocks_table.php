<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('new_stocks', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn('quantity_added');
        });

        Schema::table('new_stocks', function (Blueprint $table) {
            // Tambahkan kolom baru dengan tipe decimal
            $table->decimal('quantity_added', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('new_stocks', function (Blueprint $table) {
            $table->dropColumn('quantity_added');
        });

        Schema::table('new_stocks', function (Blueprint $table) {
            $table->integer('quantity_added')->default(0);
        });
    }
};
