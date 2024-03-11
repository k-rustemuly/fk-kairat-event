<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MoonShine\MoonShineAuth;

class MoonshineAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = config('moonshine.auth.admin');
        MoonShineAuth::model()->query()->create([
            'email' => $admin['email'],
            'name' => $admin['name'],
            'password' => Hash::make($admin['password']),
        ]);
    }
}
