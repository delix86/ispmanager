<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\State::create(['name' => 'открыта']); // admin, support
        \App\State::create(['name' => 'приостановлена']); // admin, support
        \App\State::create(['name' => 'в работе']); // user, admin, support
        \App\State::create(['name' => 'выполнена']); // user, admin, support
        \App\State::create(['name' => 'не выполнена']); // user, admin, support
        \App\State::create(['name' => 'отменена']); // admin, support
        \App\State::create(['name' => 'выполнена и закрыта']); // admin, support
        \App\State::create(['name' => 'не выполнена и закрыта']); // admin, support
        \App\State::create(['name' => 'удалена']); // admin, support
    }
}
