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
    public function run(): void{


        $this->call(RolesAndPermissionsSeeder::class);

        $this->call(ImportUsersAlumnosSeeder::class);

        $this->call(ImportUsersSeeder::class);

        $this->call(ImportRegimenesFiscalesSeeder::class);

        $this->call(ImportRegistrosFiscalesSeeder::class);

        $this->call(ImportFamiliasSeeder::class);

        $this->call(ImportFamiliasUsersRolesFromParentsSeeder::class);

        $this->call(ImportFamiliasUsersRolesFromAlumnosSeeder::class);

        $this->call(ImportFamiliasUsersRolesFromParentsSeeder::class);

        $this->call(ImportFamiliasRegistrosfiscalesSeeder::class);

        $this->call(ImportCiclosSeeder::class);

        $this->call(ImportNivelesSeeder::class);

        $this->call(ImportGruposSeeder::class);

        $this->call(ImportAlumnosGruposSeeder::class);

        $this->call(ImportGruposNivelesSeeder::class);

        $this->call(ImportUsoCFDISeeder::class);

    }
}
