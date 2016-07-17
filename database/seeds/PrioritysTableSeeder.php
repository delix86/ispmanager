<?php

use Illuminate\Database\Seeder;

class PrioritysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Priority::create(['name' => 'низкий']);
        \App\Priority::create(['name' => 'нормальный']);
        \App\Priority::create(['name' => 'высокий']);
        \App\Priority::create(['name' => 'супер высокий']);
    }
}
