<?php

namespace App\Http\Controllers\Api;

use App\Models\Sample;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(string $id)
    {
        $samples = Sample::where('project_id', $id)->get();
        return $samples;
    }

    public function store(Request $request, string $id)
    {
        Sample::create([
            "project_id" => $id,
            "number" => $request['number'],
            "time" => $request['time'],
            "image" => $request['image']
        ]);
    }

    public function destroy(Sample $sample)
    {
        $sample->delete();
    }
}
