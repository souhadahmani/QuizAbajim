<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'full_name' => 'إدارة أباجم',
            'email' => 'admin@abajim.com',
            'password' => Hash::make('admin123'),
            'role_id' => 2, // Remplacez par l'ID du rôle 'admin' dans la table roles
            'role_name' => 'admin', // Si cette colonne existe
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}