<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Role::create(['name'=>'User', 'short_name'=>'User', 'color'=> '#393B51']);
        Role::create(['name' =>'Administrator', 'short_name' => 'Admin', 'color' => '#ED474A']);
        Role::create(['name' => 'Data Analyst', 'short_name'=>'DA', 'color' => '#7C9EB2']);
    }
}
