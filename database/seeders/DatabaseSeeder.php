<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);


        $this->call(RolesAndPermissionsSeeder::class); // This will run the UsersTableSeeder command

        $this->call(ImportUsersAlumnosSeeder::class); // This will run the UsersTableSeeder command

        $this->call(ImportUsersSeeder::class); // This will run the UsersTableSeeder command

        $this->call(ImportRegimenesFiscalesSeeder::class); // This will run the UsersTableSeeder command

        $this->call(ImportRegistrosFiscalesSeeder::class); // This will run the UsersTableSeeder command

        $this->call(ImportFamiliasSeeder::class); // This will run the UsersTableSeeder command




    }
}
