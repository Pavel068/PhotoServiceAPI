<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Photos;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Photos::all()
        ], 200);
    }

    public function show($id)
    {

    }

    public function create(Request $request)
    {
        if ($request->hasFile('photo')) {
            if($request->file('photo')->isValid()) {
                try {
                    $file = $request->file('photo');
                    $name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();

                    # save to DB
                    $tickes = Photos::create(['url' => 'storage/'.$name]);

                    $request->file('photo')->move("storage", $name);
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {

                }
            }
        }
    }

    public function update()
    {

    }
}
