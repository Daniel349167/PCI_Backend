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

    public function store(Request $request)
    {
        Project::create([
            "user_id" => auth()->user()->id,
            "name" => $request['name'],
            "time" => $request['time'],
            "image" => $request['image']
        ]);
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
