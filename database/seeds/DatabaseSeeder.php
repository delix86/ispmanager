<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LogtypesTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(PrioritysTableSeeder::class);
        $this->call(RightsTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        //$this->call(UserTableSeeder::class);
    }
}
