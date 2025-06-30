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
    Schema::table('products', function (Blueprint $table) {
        $table->text('catatan')->nullable()->after('image');
        $table->decimal('harga_rekomendasi', 15, 2)->nullable()->after('price_sell');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('catatan');
        $table->dropColumn('harga_rekomendasi');
    });
}

};
