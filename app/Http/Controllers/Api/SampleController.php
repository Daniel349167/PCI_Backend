<?php

namespace App\Http\Controllers\Api;

use App\Models\Sample;
use App\Models\Damage;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(string $id)
    {
        $samples = Sample::where('project_id', $id)->orderBy('id', 'asc')->get();
        foreach($samples as $sample)
            $sample->image = SampleController::image($sample);
        return $samples;
    }

    public function read($id) {
        $sample = Sample::where('id', $id)->first();
        $project = Project::where('id', $sample->project_id)->first();
        $sample->project = $project->name;
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

    public static function image($sample) {
        $damages = Damage::where('sample_id', $sample->id)->orderBy('id', 'asc')->get();
        foreach($damages as $damage)
            if($damage)
                return $damage->image;
        return null;
    }
}
