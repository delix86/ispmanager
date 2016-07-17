<?php

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Right::create(['name' => 'admin']);
        \App\Right::create(['name' => 'support']);
        \App\Right::create(['name' => 'worker']);
    }
}
