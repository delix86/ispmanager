<?php

use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Position::create(['name' => 'Администратор']);
        \App\Position::create(['name' => 'Тех. Поддержка']);
        \App\Position::create(['name' => 'Бригадир']);
        \App\Position::create(['name' => 'Монтажник']);
    }
}
