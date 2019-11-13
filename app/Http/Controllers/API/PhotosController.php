<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Photos;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

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
                    $name = md5(uniqid('_', true)) . '.' . $file->getClientOriginalExtension();

                    # save to DB
                    $photo = Photos::create(['url' => 'storage/'.$name]);

                    $request->file('photo')->move("storage", $name);

                    return response()->json([
                        'data' => $photo
                    ], 200);
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                    return response()->json([
                        'error' => true
                    ], 400);
                }
            } else {
                return response()->json([
                    'error' => true
                ], 422);
            }
        } else {
            return response()->json([
                'error' => true
            ], 400);
        }
    }

    public function update()
    {

    }
}
