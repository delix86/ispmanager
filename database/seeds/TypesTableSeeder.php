<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Type::create(['name' => 'подключение']);
        \App\Type::create(['name' => 'ремонт']);
        \App\Type::create(['name' => 'задача']);
    }
}
