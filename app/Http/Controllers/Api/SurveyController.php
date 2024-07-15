<?php

namespace App\Http\Controllers\Api;

use App\Models\Survey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(string $id)
    {
        $surveys = Survey::where('sample_id', $id)->get();
        return $surveys;
    }

    public function store(Request $request, string $id)
    {
        Survey::create([
            "sample_id" => $id,
            "number" => $request['number'],
            "time" => $request['time'],
            "image" => $request['image']
        ]);
    }

    public function destroy(Survey $surveys)
    {
        $surveys->delete();
    }
}
