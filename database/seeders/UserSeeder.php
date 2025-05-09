<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id_tossa' => null,
                'id_shift' => null,
                'id_role' => 1,
                'username' => 'superadmin',
                'password' => Hash::make('supplychain#$1521!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_tossa' => null,
                'id_shift' => null,
                'id_role' => 3,
                'username' => 'yudha',
                'password' => Hash::make('yudha123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_tossa' => null,
                'id_shift' => null,
                'id_role' => 1,
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
