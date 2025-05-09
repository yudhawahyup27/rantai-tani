<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Tossa;
use App\Models\Shift;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['role' => 'administrator'],
            ['role' => 'mitra'],
            ['role' => 'investor'],
        ]);

        Tossa::insert([
            ['name' => 'Tossa 1'],
            ['name' => 'Tossa 2'],
            ['name' => 'Tossa 3'],
        ]);

        Shift::insert([
            ['name' => 'Pagi',],
            ['name' => 'Sore'],
        ]);

    }
}
