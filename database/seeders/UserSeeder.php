<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
            'name' => 'Noshad',
            'username' => 'noshad',
            'email' => 'noshad@example.com',
            'password' => bcrypt('password'),
        ]);

         User::create([
            'name' => 'test',
            'username' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
