<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Models\Sample;
use App\Models\Damage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Api\SampleController;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('user_id', Auth::user()->id)->where('deleted_at', null)->orderBy('id', 'asc')->get();
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
            "user_id" => Auth::user()->id,
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

    public function update(Request $request, string $id) {
        $project = Project::find($id);
        $project->update($request->all());
    }

    public function image($project) {
        $samples = Sample::where('project_id', $project->id)->orderBy('id', 'asc')->get();
        foreach($samples as $sample)
            if($sample)
                return SampleController::image($sample);
        return null; 
    }

    public function delete($id)
    {
        $project = Project::find($id);
        foreach(Sample::where('project_id', $id)->get() as $sample) {
            foreach(Damage::where('sample_id', $sample->id)->get() as $damage)
                $damage->delete();
            $sample->delete();
        }
        $project->delete();
    }
    public function lazy_delete($id)
    {
        $project = Project::find($id);
        $project->deleted_at = now();
        $project->save();
    }

    public function updateProjectName(Request $request, $id)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Buscar el proyecto por su ID
        $project = Project::find($id);

        // Verificar que el proyecto exista
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Actualizar el nombre del proyecto
        $project->name = $validatedData['name'];
        $project->save();

        // Retornar respuesta exitosa
        return response()->json(['message' => 'Project name updated successfully'], 200);
    }
}
