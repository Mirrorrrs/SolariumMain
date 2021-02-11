<?php

namespace Database\Seeders;

use App\Models\Group;
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
            "namespace" => "it",
            "name"      => "IT-лицей КФУ",
        ]);
        User::create([
            "username"        => "admin",
            "password"        => \Hash::make("admin"),
            "organization_id" => $org->id,
            "role"         => 1,
        ]);

        Group::create([
            "name"=>"student",
            "organization_id" => $org->id,
            "human_name"=>"Fuckin i=diot",
            "permissions"=>1<<collect(config("permissions"))->get("student"),
            "description"=>"Fuckin i=diot"
        ]);
    }
}
