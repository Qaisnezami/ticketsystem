<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@me.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'name' => 'Agent',
                'email' => 'agent@me.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
        Profile::insert([[
            'user_id' => 1,
            'phone' => '1234567890',
            'image' => '',
        ],
        [
            'user_id' => 2,
            'phone' => '1234567890',
            'image' => '',
        ]]
    );
    }
}
