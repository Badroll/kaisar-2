<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => Str::uuid(), 'name' => 'admin', 'code' => 'ADMIN'],
            ['id' => Str::uuid(), 'name' => 'trainer', 'code' => 'TRAINER'],
            ['id' => Str::uuid(), 'name' => 'student', 'code' => 'STUDENT'],
            ['id' => Str::uuid(), 'name' => 'talent', 'code' => 'TALENT'],
        ];

        DB::table('ms_roles')->insert($roles);
    }
}
