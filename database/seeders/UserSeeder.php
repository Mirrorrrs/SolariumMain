<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $org = Organization::create([
            "namespace" => "itlkpfu",
            "name"      => "IT-лицей КФУ",
        ]);
        $role = Role::create([
            "name"        => "staff",
            "human_name"  => "Staff",
            "description" => "Platform administrators",
        ]);
        User::create([
            "username"        => "admin",
            "password"        => \Hash::make("admin"),
            "organization_id" => $org->id,
            "role_id"         => $role->id,
        ]);
    }
}
