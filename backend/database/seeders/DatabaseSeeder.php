<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
<<<<<<< HEAD
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DemoUserSeeder::class,
            //MedicalRecordSeeder::class,
        ]);
    }
=======
    use WithoutModelEvents;
        public function run(): void
{
    $this->call([
        RoleSeeder::class,
        MedicalRecordSeeder::class,
    ]);
}
    /**
     * Seed the application's database.
     */
    /**public function run(): void
    {   $this->call(RoleSeeder::class);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }*/
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
}
