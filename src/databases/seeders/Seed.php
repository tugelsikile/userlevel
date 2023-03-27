<?php

namespace Tugelsikile\UserLevel\databases\seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Tugelsikile\UserLevel\databases\seeders\MenuSeeder;
use Tugelsikile\UserLevel\databases\seeders\PrivilegeSeeder;

class Seed extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // \App\Models\User::factory(10)->create();
        $this->call([
            MenuSeeder::class,
            UserLevelSeeder::class,
            PrivilegeSeeder::class
        ]);
        Model::reguard();
    }
}
