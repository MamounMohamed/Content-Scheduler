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
        User::factory()->create(['email' => 'mamoun@test.com','password'=>'mamoun@123456','name'=>'Mamoun Mohammed']);
        $this->call(PostPlatformSeeder::class);
    }
}
