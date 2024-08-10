<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SampleSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('samples')->insert([
                'project_id' => 1, // Asegúrate de tener un project_id válido en tu base de datos
                'number' => $i,
                'time' => now(),
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'section' => Str::random(1),  // Genera una letra aleatoria
                'to_m' => rand(100, 1000),
                'to_km' => rand(1, 10),
                'from_m' => rand(100, 1000),
                'from_km' => rand(1, 10),
            ]);
        }
    }
}
