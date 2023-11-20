<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ["name" => "Admin", "slug" => "admin", "alias" => "Steven Samson"],
            ["name" => "Accountant", "slug" => "accountant", "alias" => "Andrew Michael"],
            ["name" => "Loan Officer", "slug" => "loanofficer", "alias" => "Mary Francis"],
            ["name" => "Director(Finance)", "slug" => "directorfinance", "alias" => "Vanesa William"],
            ["name" => "Director(Legal)", "slug" => "directorlegal", "alias" => "Donald Kennedy"],
            ["name" => "Operations manager", "slug" => "operationsmanager", "alias" => "David Kelvin"],
        ];

        foreach ($roles as $key => $role) {

            if (!Role::where("name", $role["name"])->exists()) {
                Role::create([
                    "name" => $role["name"],
                ]);
            }

            $createdRole = Role::where("name", $role["name"])->first();

            $user = User::firstOrCreate([
                "email" => $role["slug"] . "@foward.co.tz",
            ], [
                "password" => "password",
                "name" => $role["alias"],
                "email_verified_at" => now(),
            ]);

            $user->assignRole($createdRole);
        }
    }
}
