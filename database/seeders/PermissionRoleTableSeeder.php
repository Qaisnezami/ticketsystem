<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();

        $agent_permissions = Permission::where('title','like','ticket_%')->get();

        $admin = Role::where('title','Admin')->first();
        $admin->permissions()->sync($permissions->pluck('id'));

        $agent = Role::where('title','Agent')->first();

        $agent->permissions()->sync($agent_permissions->pluck('id'));



    }
}
