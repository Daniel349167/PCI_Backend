<?php

namespace App\Http\Controllers\Api;

use App\Models\Damage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DamageController extends Controller
{
    public function index($id)
    {
        $damages = Damage::where('sample_id', $id)->get();
        return $damages;
    }

    public function read($id) {
        $damage = Damage::where('id', $id)->first();
        return $damage;
    }

    public function store(Request $request, $id)
    {
        Damage::create([
            "sample_id" => $id,
            "number" => $request['number'],
            "time" => $request['time'],
            "image" => $request['image']
        ]);
    }

    public function destroy(Damage $damages)
    {
        $damages->delete();
    }
}
