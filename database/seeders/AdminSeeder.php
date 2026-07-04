<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's default admin.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            [
                'email' => config('shop.admin.email'),
            ],
            [
                'name'     => config('shop.admin.name'),
                'password' => config('shop.admin.password'),
                'status'   => config('shop.admin.status'),
            ]
        );
    }
}