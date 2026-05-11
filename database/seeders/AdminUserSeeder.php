<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@perfume.test'],
            [
                'name' => 'Store Admin',
                'password' => Hash::make('12345'),
                'email_verified_at' => now(),
                'locale' => 'en',
                'currency' => 'USD',
            ]
        );
        $admin->syncRoles(['super_admin']);
    }
}
