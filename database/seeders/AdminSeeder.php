<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{

    public function run()
    {
        User::create([
            'name' => 'Ahmad',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('ahmad'),
            'role' => 'admin',
        ]);
    }
}
