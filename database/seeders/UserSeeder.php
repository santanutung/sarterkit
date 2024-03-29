<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'role_id' =>  Role::where('slug', 'admin')->first()->id,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'deletable' => false,
                'status' => true
            ]
        );
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'role_id' => Role::where('slug', 'user')->first()->id,
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'status' => false
            ]
        );
    }
}
