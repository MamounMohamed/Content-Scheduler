<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create(['email' => 'main_user@test.com','password'=>Hash::make('123456'),'name'=>'Mamoun Mohammed']);
        User::factory()->create(['email' => 'test_user@test.com','password'=>Hash::make('123456'),'name'=>'Other Account']);
        $this->call(PostPlatformSeeder::class);
    }
}
