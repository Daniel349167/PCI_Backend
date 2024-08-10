<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DamageSeeder extends Seeder
{
    public function run()
    {
        for ($sampleId = 1; $sampleId <= 10; $sampleId++) {
            // Generar un conjunto de tipos y severidades comunes
            $commonTypes = array_rand(array_flip(range(1, 19)), rand(2, 4));  // Selecciona entre 2 y 4 tipos comunes
            $commonSeverities = array_rand(array_flip(range(1, 3)), rand(1, 2));  // Selecciona entre 1 y 2 severidades comunes

            // Si solo se selecciona un tipo o severidad, array_rand devuelve un solo valor. Envolvemos en un array para garantizar la consistencia.
            if (!is_array($commonTypes)) {
                $commonTypes = [$commonTypes];
            }

            if (!is_array($commonSeverities)) {
                $commonSeverities = [$commonSeverities];
            }

            $numberOfDamages = rand(16, 26);

            for ($i = 1; $i <= $numberOfDamages; $i++) {
                $type = in_array($i, range(1, 5)) ? $commonTypes[array_rand($commonTypes)] : rand(1, 19);
                $severity = in_array($i, range(1, 5)) ? $commonSeverities[array_rand($commonSeverities)] : rand(1, 3);

                DB::table('damages')->insert([
                    'sample_id' => $sampleId,
                    'number' => $i,
                    'time' => now(),
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'amount' => rand(1, 100),
                    'severity' => $severity,
                    'type' => $type,
                ]);
            }
        }
    }
}
