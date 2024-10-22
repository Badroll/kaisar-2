<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basic = ClassModel::create([
            'id' => Str::uuid(),
            'name' => 'Basic',
            'day_duration' => 30,
            'num_of_praktek' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $ultimate = ClassModel::create([
            'id' => Str::uuid(),
            'name' => 'Ultimate',
            'day_duration' => 30,
            'num_of_praktek' => 12,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $private = ClassModel::create([
            'id' => Str::uuid(),
            'name' => 'Private',
            'day_duration' => 48,
            'num_of_praktek' => 18,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
