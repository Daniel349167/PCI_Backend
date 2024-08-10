<?php

namespace App\Http\Controllers\Api;

use App\Models\Damage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DamageMeasurementController extends Controller
{
    public function getSummaryBySampleId($sampleId)
    {
        $damages = Damage::where('sample_id', $sampleId)
            ->select('type', 'severity', DB::raw('SUM(amount) as metrado'))
            ->groupBy('type', 'severity')
            ->orderBy('type', 'asc')  // Asegúrate de que esté ordenado por tipo de fallo (type)
            ->orderBy('severity', 'asc')  // (Opcional) también puedes ordenar por severidad
            ->get();

        $summary = [];
        $counter = 1;

        foreach ($damages as $damage) {
            $summary[] = [
                'id' => $counter,
                'tipo_falla' => $damage->type,
                'unidad' => 'm2',
                'severidad' => $this->mapSeverity($damage->severity),
                'metrado' => $damage->metrado,
            ];
            $counter++;
        }

        return response()->json($summary);
    }

    private function mapSeverity($severity)
    {
        // Mapear la severidad a un valor más comprensible
        $severities = [
            1 => 'L', // Low
            2 => 'M', // Medium
            3 => 'H', // High
        ];

        return $severities[$severity] ?? 'N/A';
    }
}
