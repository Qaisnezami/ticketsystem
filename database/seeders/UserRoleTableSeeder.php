<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::where('title','Admin')->first();
        $role_agent = Role::where('title','Agent')->first();

        $admin = User::where('email','admin@me.com')->first();
        $agent = User::where('email','agent@me.com')->first();

        $admin->roles()->sync($role_admin->id);
        $agent->roles()->sync($role_agent->id);
    }
}
