<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ModuleSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin account
        User::firstOrCreate(
            ['email' => 'admin@aclc.edu.ph'],
            [
                'name'      => 'Clinic Admin',
                'password'  => Hash::make('password123'),
                'role'      => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );

        // Default staff account
        User::firstOrCreate(
            ['email' => 'staff@aclc.edu.ph'],
            [
                'name'      => 'Clinic Staff',
                'password'  => Hash::make('password123'),
                'role'      => User::ROLE_STAFF,
                'is_active' => true,
            ]
        );

        // Default categories
        Category::firstOrCreate(['name' => 'Over-the-counter'], ['description' => 'OTC medications']);
        Category::firstOrCreate(['name' => 'First Aid'],        ['description' => 'First aid supplies']);

        // Default module settings
        foreach (['categories', 'medications', 'requests', 'reports'] as $module) {
            ModuleSetting::firstOrCreate(
                ['module' => $module],
                ['is_active' => true]
            );
        }
    }
}
