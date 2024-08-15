<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Models\Sample;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\SampleController;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('user_id', auth()->user()->id)->orderBy('id', 'asc')->get();
        foreach($projects as $project)
            $project->image = ProjectController::image($project);
        return $projects;
    }

    public function read($id) {
        $project = Project::where('id', $id)->first();
        return $project;
    }

    public function store(Request $request)
    {
        $project = Project::create([
            "user_id" => auth()->user()->id,
            "name" => $request['name'],
            "time" => $request['time'],
            "image" => $request['image'],
            "longitudum" => $request['longitudum'],
            "anchoum" => $request['anchoum'],
            "longitudcarretera" => $request['longitudcarretera'],
        ]);
        $i = 0;
        $L = $request['longitudcarretera']*1000;
        $l = $request['longitudum'];
        for($x = 0; $x<$L; $x+=$l){
            $i++;
            if(($x+$l) > $L)
                $l -= $x + $l - $L;
            $last_sample = Sample::create([
                'project_id' => $project->id,
                'number' => $i,
                'time' => $project->time,
                'from_km' => (int)($x/1000),
                'from_m' => $x%1000,
                'to_km' => (int)(($x+$l)/1000),
                'to_m' => ($x+$l)%1000,
            ]);
        }
    }

    public function destroy(Project $project)
    {
        $project->delete();
    }

    public function image($project) {
        $samples = Sample::where('project_id', $project->id)->orderBy('id', 'asc')->get();
        foreach($samples as $sample)
            if($sample)
                return SampleController::image($sample);
        return null; 
    }
}
