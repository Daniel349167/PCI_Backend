<?php

namespace App\Http\Controllers\Api;

use App\Models\Sample;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(string $id)
    {
        $samples = Sample::where('project_id', $id)->orderBy('id', 'asc')->get();
        return $samples;
    }

    public function read($id) {
        $sample = Sample::where('id', $id)->first();
        return $sample;
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

    public function update(Request $request, string $id) {
        $sample = Sample::find($id);
        $sample->update($request->all());
    }

    public function destroy(Sample $sample)
    {
        $sample->delete();
    }
}
