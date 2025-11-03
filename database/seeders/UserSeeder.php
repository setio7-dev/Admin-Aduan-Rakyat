<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::insert([
            [
                "fullname" => "Admin",
                "username" => "admin123",
                "password" => Hash::make("admin123"),
                "role" => "admin"
            ],
            [
                "fullname" => "User",
                "username" => "user123",
                "password" => Hash::make("user123"),
                "role" => "resident"
            ],
        ]);
    }
}
