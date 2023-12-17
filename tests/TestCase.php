<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            "name"=>"super_admin"
        ]);

        $user = User::firstOrCreate([
            "email" => "admin@foward.co.tz",
        ], [
            "password" => "password",
            "name" => "admin",
            "email_verified_at" => now(),
        ]);

        $user->assignRole($role);
        Artisan::call("db:seed", ["--class" => "ShieldSeeder"]);
        $this->actingAs($user);
    }
}
