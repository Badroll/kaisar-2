<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone_number' => '123',
            'password' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $admin->roles()->attach(
            DB::table('ms_roles')->where('name', 'admin')->first()->id
        );

        $resha = User::create([
            'id' => Str::uuid(),
            'name' => 'resha',
            'email' => 'admin2@gmail.com',
            'phone_number' => '321',
            'password' => 'admin2',
        ]);

        $resha->roles()->attach([
            DB::table('ms_roles')->where('name', 'admin')->first()->id,
            DB::table('ms_roles')->where('name', 'trainer')->first()->id,
            DB::table('ms_roles')->where('name', 'student')->first()->id,
            DB::table('ms_roles')->where('name', 'talent')->first()->id,
        ]);
    }
}
