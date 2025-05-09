<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('satuans')->insert([
            [
            "nama_satuan" => 'Kg',
                    ],
            [
            "nama_satuan" => 'Unting',
                    ],
            [
            "nama_satuan" => 'Pak',
                    ],
            [
            "nama_satuan" => 'Pcs',
                    ],
    ]);
    }
}
