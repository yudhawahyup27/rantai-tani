<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Hapus kolom integer lama
            $table->dropColumn(['quantity', 'quantity_new']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            // Tambahkan ulang dengan tipe decimal
            $table->decimal('quantity', 10, 2)->default(0)->after('product_id');
            $table->decimal('quantity_new', 10, 2)->default(0)->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Hapus kolom decimal
            $table->dropColumn(['quantity', 'quantity_new']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            // Tambahkan ulang sebagai integer (restore)
            $table->integer('quantity')->default(0)->after('product_id');
            $table->integer('quantity_new')->default(0)->after('quantity');
        });
    }
};
