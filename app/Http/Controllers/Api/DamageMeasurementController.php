<?php

namespace App\Http\Controllers\Api;

use App\Models\Damage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DamageMeasurementController extends Controller
{
    public function getSummaryBySampleId($projectId, $sampleId)
    {
        $damages = Damage::where('sample_id', $sampleId)
            ->whereHas('sample', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->select('type', 'severity', DB::raw('SUM(amount) as metrado'))
            ->groupBy('type', 'severity')
            ->orderBy('type', 'asc')
            ->orderBy('severity', 'asc')
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
        $severities = [
            1 => 'L', // Low
            2 => 'M', // Medium
            3 => 'H', // High
        ];

        return $severities[$severity] ?? 'N/A';
    }
}
