<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminRoleSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Role definitions
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator Sistem'],
            ['name' => 'guru', 'description' => 'Guru'],
            ['name' => 'murid', 'description' => 'Murid'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(
                ['name' => $r['name']],
                ['description' => $r['description']]
            );
        }

        // Accounts
        $accounts = [
            // Admin
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role_name' => 'admin',
                'password' => 'password',
                'is_verified' => true,
            ],
            // Teacher
            [
                'name' => 'Guru',
                'email' => 'guru@example.com',
                'role_name' => 'guru',
                'password' => 'password',
                'is_verified' => true,
            ],
            // Student
            [
                'name' => 'Murid',
                'email' => 'murid@example.com',
                'role_name' => 'murid',
                'password' => 'password',
                'is_verified' => true,
            ],
        ];

        foreach ($accounts as $acc) {
            $role = Role::where('name', $acc['role_name'])->first();
            if (!$role) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $acc['email']],
                [
                    'name' => $acc['name'],
                    'role_id' => $role->id,
                    'password' => Hash::make($acc['password']),
                    'email_verified_at' => $acc['is_verified'] ? now() : null,
                ]
            );
        }
    }
}

