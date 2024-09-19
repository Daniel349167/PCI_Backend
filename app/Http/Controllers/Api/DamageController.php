<?php

namespace App\Http\Controllers\Api;

use App\Models\Damage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DamageController extends Controller
{
    public function index($id)
    {
        $damages = Damage::select('id', 'number', 'time', 'type', 'severity', 'amount')
            ->where('sample_id', $id)->where('deleted_at', null)->orderBy('id', 'asc')->get();
        return $damages;
    }

    public function read($id) {
        $damage = Damage::select('id', 'number', 'time', 'type', 'severity', 'amount')
            ->where('id', $id)->first();
        return $damage;
    }

    public function getImage($id) {
        $image = Damage::select('image')
            ->where('id', $id)->first();
        return $image;
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

    public function update(Request $request, string $id) {
        $damage = Damage::find($id);
        $damage->update($request->all());
    }

    public function delete($id)
    {
        $damage = Damage::find($id);
        $damage->delete();
    }
    public function lazy_delete($id)
    {
        $damage = Damage::find($id);
        $damage->deleted_at = now();
        $damage->save();
    }
}
