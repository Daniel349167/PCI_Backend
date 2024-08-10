<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\DamageMeasurementController;

class DeductedValuesController extends Controller
{

    public function getDeductedValues($projectId, $sampleId)
    {
        // Crear una instancia del controlador DamageMeasurementController
        $damageMeasurementController = new DamageMeasurementController();

        // Llamar directamente a la función getSummaryBySampleId
        $response = $damageMeasurementController->getSummaryBySampleId($projectId, $sampleId);

        if ($response->getStatusCode() !== 200) {
            return response()->json(['error' => 'Unable to fetch data from damage-measurement function'], 500);
        }

        $damages = $response->getData();

        // Obtener los datos del proyecto
        $project = Project::findOrFail($projectId);

        // Validar que anchoum y longitudum no sean cero
        if ($project->anchoum == 0 || $project->longitudum == 0) {
            return response()->json(['error' => 'The project width (anchoum) or length (longitudum) cannot be zero.'], 400);
        }

        $area = $project->anchoum * $project->longitudum;

        $deductedValues = [];
        $id = 1; // Inicializar el ID incremental

        foreach ($damages as $damage) {
            $density = ($damage->metrado / $area) * 100; // Calculando la densidad

            // Calcular el VD según las fórmulas específicas para cada tipo de daño
            $vd = $this->calculateVD($damage->tipo_falla, $damage->severidad, $density);

            $deductedValues[] = [
                'id' => $id++, // Agregar el ID incremental al principio
                'Daño' => $damage->tipo_falla,
                'Severidad' => $damage->severidad,
                'Total' => $damage->metrado,
                'Densidad' => round($density, 2), // Densidad en porcentaje, sin el símbolo
                'VD' => $vd,
            ];
        }

        return response()->json($deductedValues);
    }



    private function calculateVD($tipo_falla, $severidad, $densidad)
    {
        $vd = 0;

        switch ($tipo_falla) {
            case 1:
                $vd = $this->calculateType1($severidad, $densidad);
                break;
            case 2:
                $vd = $this->calculateType2($severidad, $densidad);
                break;
            case 3:
                $vd = $this->calculateType3($severidad, $densidad);
                break;
            case 4:
                $vd = $this->calculateType4($severidad, $densidad);
                break;
            case 5:
                $vd = $this->calculateType5($severidad, $densidad);
                break;
            case 6:
                $vd = $this->calculateType6($severidad, $densidad);
                break;
            case 7:
                $vd = $this->calculateType7($severidad, $densidad);
                break;
            case 8:
                $vd = $this->calculateType8($severidad, $densidad);
                break;
            case 9:
                $vd = $this->calculateType9($severidad, $densidad);
                break;
            case 10:
                $vd = $this->calculateType10($severidad, $densidad);
                break;
            case 11:
                $vd = $this->calculateType11($severidad, $densidad);
                break;
            case 12:
                $vd = $this->calculateType12($severidad, $densidad);
                break;
            case 13:
                $vd = $this->calculateType13($severidad, $densidad);
                break;
            case 14:
                $vd = $this->calculateType14($severidad, $densidad);
                break;
            case 15:
                $vd = $this->calculateType15($severidad, $densidad);
                break;
            case 16:
                $vd = $this->calculateType16($severidad, $densidad);
                break;
            case 17:
                $vd = $this->calculateType17($severidad, $densidad);
                break;
            case 18:
                $vd = $this->calculateType18($severidad, $densidad);
                break;
            case 19:
                $vd = $this->calculateType19($severidad, $densidad);
                break;
            default:
                $vd = 0; // Caso por defecto
        }

        return round($vd, 2);
    }

    // Implementación de las funciones para los tipos de falla del 1 al 10

    private function calculateType1($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return ($densidad < 5)
                ? -0.7096 * pow($densidad, 2) + 7.8986 * $densidad + 3.0091
                : 12.19 * log($densidad) + 4.2926;
        } elseif ($severidad == 'M') { // MEDIA
            return 10.448 * log($densidad) + 23.927;
        } elseif ($severidad == 'H') { // ALTA
            return 12.136 * log($densidad) + 33.144;
        }
        return 0;
    }

    private function calculateType2($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 0.001 * $densidad + 0.2982 * $densidad - 0.2072;
        } elseif ($severidad == 'M') { // MEDIA
            return 2.9776 * pow($densidad, 0.5825);
        } elseif ($severidad == 'H') { // ALTA
            return 6.2606 * pow($densidad, 0.5464);
        }
        return 0;
    }

    private function calculateType3($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 7e-05 * pow($densidad, 3) - 0.0123 * pow($densidad, 2) + 0.8459 * $densidad - 0.2271;
        } elseif ($severidad == 'M') { // MEDIA
            return ($densidad < 10)
                ? -0.1121 * pow($densidad, 2) + 2.7987 * $densidad - 0.7611
                : -0.0026 * pow($densidad, 2) + 0.5782 * $densidad + 10.9;
        } elseif ($severidad == 'H') { // ALTA
            return ($densidad < 10)
                ? -0.2071 * pow($densidad, 2) + 4.821 * $densidad + 0.4245
                : 18.653 * log($densidad) - 14.824;
        }
        return 0;
    }

    private function calculateType4($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return -0.0153 * pow($densidad, 2) + 1.7178 * $densidad + 0.4622;
        } elseif ($severidad == 'M') { // MEDIA
            return 12.526 * pow($densidad, 0.5223);
        } elseif ($severidad == 'H') { // ALTA
            return 31.673 * pow($densidad, 0.3394);
        }
        return 0;
    }

    private function calculateType5($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 1e-04 * pow($densidad, 3) - 0.0189 * pow($densidad, 2) + 1.2837 * $densidad + 1.0104;
        } elseif ($severidad == 'M') { // MEDIA
            return ($densidad < 10)
                ? 14.36 * pow($densidad, 0.4591)
                : 14.412 * log($densidad) + 5.6172;
        } elseif ($severidad == 'H') { // ALTA
            return 12.199 * log($densidad) + 35.188;
        }
        return 0;
    }

    private function calculateType6($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 0.0001 * pow($densidad, 3) - 0.0277 * pow($densidad, 2) + 1.824 * $densidad + 2.6706;
        } elseif ($severidad == 'M') { // MEDIA
            return -3e-06 * pow($densidad, 4) + 0.0008 * pow($densidad, 3) - 0.0715 * pow($densidad, 2) + 2.9916 * $densidad + 6.4597;
        } elseif ($severidad == 'H') { // ALTA
            return 20.426 * pow($densidad, 0.3067);
        }
        return 0;
    }

    private function calculateType7($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return -0.0033 * pow($densidad, 2) + 0.4323 * $densidad + 0.8895;
        } elseif ($severidad == 'M') { // MEDIA
            return 5.5876 * pow($densidad, 0.3967);
        } elseif ($severidad == 'H') { // ALTA
            return 0.0004 * pow($densidad, 3) - 0.0485 * pow($densidad, 2) + 2.1487 * $densidad + 6.2192;
        }
        return 0;
    }

    private function calculateType8($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 0.0022 * pow($densidad, 2) + 0.4777 * $densidad + 0.4833;
        } elseif ($severidad == 'M') { // MEDIA
            return ($densidad < 10)
                ? -0.0788 * pow($densidad, 2) + 2.2831 * $densidad + 0.0566
                : 12.119 * log($densidad) - 11.43;
        } elseif ($severidad == 'H') { // ALTA
            return -2e-06 * pow($densidad, 4) + 0.0006 * pow($densidad, 3) - 0.0649 * pow($densidad, 2) + 3.325 * $densidad + 2.513;
        }
        return 0;
    }

    private function calculateType9($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 0.0016 * pow($densidad, 2) + 0.3641 * $densidad + 1.768;
        } elseif ($severidad == 'M') { // MEDIA
            return 0.0013 * pow($densidad, 2) + 0.4305 * $densidad + 3.1813;
        } elseif ($severidad == 'H') { // ALTA
            return -0.002 * pow($densidad, 2) + 0.8902 * $densidad + 4.4505;
        }
        return 0;
    }

    private function calculateType10($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 5e-05 * pow($densidad, 3) - 0.0106 * pow($densidad, 2) + 0.8218 * $densidad - 0.3983;
        } elseif ($severidad == 'M') { // MEDIA
            return 0.0002 * pow($densidad, 3) - 0.0326 * pow($densidad, 2) + 1.8215 * $densidad + 0.8452;
        } elseif ($severidad == 'H') { // ALTA
            return 0.0003 * pow($densidad, 3) - 0.0515 * pow($densidad, 2) + 3.1857 * $densidad + 4.7164;
        }
        return 0;
    }

    private function calculateType11($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 0.0008 * pow($densidad, 3) - 0.0738 * pow($densidad, 2) + 2.3028 * $densidad - 0.1579;
        } elseif ($severidad == 'M') { // MEDIA
            return 9.6154 * pow($densidad, 0.4798);
        } elseif ($severidad == 'H') { // ALTA
            return 17.527 * pow($densidad, 0.4269);
        }
        return 0;
    }

    private function calculateType12($severidad, $densidad)
    {
        // Fórmula única para todas las severidades
        return -0.0009 * pow($densidad, 2) + 0.2946 * $densidad - 0.0869;
    }

    private function calculateType13($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return ($densidad < 0.6)
                ? 25.239 * pow($densidad, 1.0725)
                : 16.862 * log($densidad) + 17.612;
        } elseif ($severidad == 'M') { // MEDIA
            return ($densidad < 0.6)
                ? -5 * pow($densidad, 2) + 41.5 * $densidad + 0.9
                : 31.197 * pow($densidad, 0.4443);
        } elseif ($severidad == 'H') { // ALTA
            return 49.571 * pow($densidad, 0.3755);
        }
        return 0;
    }

    private function calculateType14($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return ($densidad < 10)
                ? 0.0444 * pow($densidad, 2) + 0.7333 * $densidad + 1.2222
                : 4.2931 * log($densidad) + 3.4863;
        } elseif ($severidad == 'M') { // MEDIA
            return ($densidad < 10)
                ? 13.996 * log($densidad) + 5.4155
                : -0.0133 * pow($densidad, 2) + 1.1 * $densidad + 29.333;
        } elseif ($severidad == 'H') { // ALTA
            return ($densidad < 10)
                ? -0.4056 * pow($densidad, 2) + 9.6833 * $densidad + 10.722
                : -0.0217 * pow($densidad, 2) + 1.65 * $densidad + 52.667;
        }
        return 0;
    }

    private function calculateType15($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return ($densidad < 10)
                ? -0.2704 * pow($densidad, 2) + 5.3421 * $densidad + 1.5163
                : 9.8415 * log($densidad) + 5.8391;
        } elseif ($severidad == 'M') { // MEDIA
            return 9.6356 * log($densidad) + 22.237;
        } elseif ($severidad == 'H') { // ALTA
            return 12.725 * log($densidad) + 30.94;
        }
        return 0;
    }

    private function calculateType16($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return ($densidad < 5)
                ? -0.3968 * pow($densidad, 2) + 4.881 * $densidad - 0.4841
                : 9.6162 * log($densidad) - 1.7613;
        } elseif ($severidad == 'M') { // MEDIA
            return 10.086 * pow($densidad, 0.5041);
        } elseif ($severidad == 'H') { // ALTA
            return 19.88 * pow($densidad, 0.3864);
        }
        return 0;
    }

    private function calculateType17($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return ($densidad < 5)
                ? -0.1956 * pow($densidad, 2) + 4.6583 * $densidad - 0.4018
                : 11.97 * log($densidad) - 0.9434;
        } elseif ($severidad == 'M') { // MEDIA
            return ($densidad < 5)
                ? -0.982 * pow($densidad, 2) + 11.074 * $densidad + 1.1747
                : 12.627 * log($densidad) + 13.264;
        } elseif ($severidad == 'H') { // ALTA
            return ($densidad < 5)
                ? -0.5713 * pow($densidad, 2) + 11.998 * $densidad + 4.9929
                : 9.8415 * log($densidad) + 45.839;
        }
        return 0;
    }

    private function calculateType18($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 5.2125 * log($densidad) + 0.97;
        } elseif ($severidad == 'M') { // MEDIA
            return 11.025 * log($densidad) + 10.342;
        } elseif ($severidad == 'H') { // ALTA
            return 10.341 * log($densidad) + 31.093;
        }
        return 0;
    }

    private function calculateType19($severidad, $densidad)
    {
        if ($severidad == 'L') { // BAJA
            return 0.0007 * pow($densidad, 2) + 0.1095 * $densidad - 0.1805;
        } elseif ($severidad == 'M') { // MEDIA
            return 4e-05 * pow($densidad, 3) - 0.0072 * pow($densidad, 2) + 0.4953 * $densidad - 0.5587;
        } elseif ($severidad == 'H') { // ALTA
            return 5e-05 * pow($densidad, 3) - 0.0129 * pow($densidad, 2) + 1.135 * $densidad + 1.5582;
        }
        return 0;
    }
}
