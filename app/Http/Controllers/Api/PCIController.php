<?php

namespace App\Http\Controllers\Api;

use App\Models\Sample;
use App\Models\Damage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\DeductedValuesController;

class PCIController extends Controller
{
    public function calculatePCI($projectId)
    {
        // Obtener todos los samples con daños en el proyecto
        $samplesWithDamage = Damage::whereHas('sample', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->pluck('sample_id')->unique();

        // Obtener todas las muestras del proyecto que tienen daños
        $samples = Sample::whereIn('id', $samplesWithDamage)
            ->orderBy('from_km')
            ->orderBy('from_m')
            ->get();

        $deductedValuesController = new DeductedValuesController();
        $pciResults = [];
        $id = 1;

        foreach ($samples as $sample) {
            // Obtener los valores deducidos para la muestra actual
            $deductedValues = $deductedValuesController->getDeductedValues($projectId, $sample->id)->getData();

            // Obtener los VD (valores deducidos)
            $vdValues = array_column($deductedValues, 'VD');

            // Ordenar VD de mayor a menor
            rsort($vdValues);

            // Verificar cuántos VD son mayores a 2
            $vdGreaterThanTwo = array_filter($vdValues, fn($vd) => $vd > 2);

            if (count($vdGreaterThanTwo) == 0) {
                // Caso 1: Todos los VD son menores o iguales a 2
                $maxCDV = array_sum($vdValues);
            } elseif (count($vdGreaterThanTwo) == 1) {
                // Caso 2: Solo un VD es mayor a 2
                $maxCDV = array_sum($vdValues);
            } else {
                // Caso 3: Más de un VD es mayor a 2
                // Calcular HDV (el mayor VD)
                $hdv = $vdValues[0];

                // Calcular m usando la fórmula
                $m = 1 + (9 / 98) * (100 - $hdv);
                $m = min($m, 10); // m no puede ser mayor que 10

                // Ajustar m si es mayor que el número de valores deducidos
                $numValuesToConsider = min(ceil($m), count($vdValues));

                // Tomar los valores VD más grandes según m
                $correctedVdValues = array_slice($vdValues, 0, $numValuesToConsider);

                // Corregir el último valor solo si m es menor que el tamaño del array
                if ($numValuesToConsider > 1 && $m < count($vdValues)) {
                    $correctionFactor = $m - ($numValuesToConsider - 1);
                    $correctedVdValues[$numValuesToConsider - 1] *= $correctionFactor;
                }

                // Calcular totales para cada q
                $totals = $this->calculateCorrectedTotals($correctedVdValues);

                // Calcular CDV para cada q usando las fórmulas proporcionadas
                $cdvs = array_map(fn($total, $q) => $this->calculateCDV($total, $q), $totals, range(1, count($totals)));

                // Obtener el mayor CDV
                $maxCDV = max($cdvs);
            }

            // Calcular el PCI y redondearlo a 1 decimal
            $pci = round(100 - $maxCDV, 1);

            // Determinar la condición basada en el PCI
            $condition = $this->determineCondition($pci);

            // Agregar el resultado al array
            $pciResults[] = [
                'id' => $id++,
                'UM' => $sample->number,
                'Del' => $sample->from_km . '+' . $sample->from_m,
                'Al' => $sample->to_km . '+' . $sample->to_m,
                'PCI' => $pci,
                'Condición' => $condition,
            ];
        }

        // Ordenar el resultado por la columna 'UM' de menor a mayor
        usort($pciResults, function ($a, $b) {
            return $a['UM'] <=> $b['UM'];
        });

        return response()->json($pciResults);
    }

    private function calculateCorrectedTotals(array $correctedVdValues)
    {
        $totals = [];
        $n = count($correctedVdValues);

        for ($q = 1; $q <= $n; $q++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                if ($i < $q) {
                    $sum += $correctedVdValues[$i];
                } else {
                    $sum += 2;  // Convertir los valores restantes en 2
                }
            }
            $totals[] = $sum;
        }

        return $totals;
    }

    private function calculateCDV($total, $q)
    {
        switch ($q) {
            case 1:
                return $total;
            case 2:
                return -0.0013 * pow($total, 2) + 0.8295 * $total - 1.1339;
            case 3:
                return -0.0014 * pow($total, 2) + 0.8321 * $total - 6.1383;
            case 4:
                return -0.0014 * pow($total, 2) + 0.82 * $total - 11.14;
            case 5:
                return -0.0011 * pow($total, 2) + 0.7363 * $total - 10.397;
            case 6:
                return -0.0011 * pow($total, 2) + 0.7398 * $total - 14.483;
            case 7:
            case 8:
            case 9:
                return 47.501 * log($total) - 168.57;
            default:
                return 0;
        }
    }

    private function determineCondition($pci)
    {
        if ($pci >= 86) {
            return 'Excelente';
        } elseif ($pci >= 71) {
            return 'Muy bueno';
        } elseif ($pci >= 56) {
            return 'Bueno';
        } elseif ($pci >= 41) {
            return 'Regular';
        } elseif ($pci >= 26) {
            return 'Pobre';
        } elseif ($pci >= 11) {
            return 'Muy pobre';
        } else {
            return 'Fallado';
        }
    }
}
