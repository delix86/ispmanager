<?php

use Illuminate\Database\Seeder;

class LogtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Logtype::create(['name' => 'create_task']);
        \App\Logtype::create(['name' => 'pause_task']);
        \App\Logtype::create(['name' => 'close_task']);
        \App\Logtype::create(['name' => 'delete_task']);
        \App\Logtype::create(['name' => 'comment_task']);
        \App\Logtype::create(['name' => 'change_user']);
    }
}
