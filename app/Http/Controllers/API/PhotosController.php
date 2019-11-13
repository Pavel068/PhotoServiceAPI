<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Photos;
use App\Shares;
use App\Users;
use Illuminate\Http\Request;
use Validator;

class PhotosController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    protected function uploadPhoto($request)
    {
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                try {
                    $file = $request->file('photo');
                    $name = md5(uniqid('_', true)) . '.' . $file->getClientOriginalExtension();

                    $request->file('photo')->move("storage", $name);

                    return $name;
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function checkOwner($photo_id)
    {
        $photo = Photos::find($photo_id);
        return $photo->owner_id == $this->user->id;
    }

    public function index()
    {
        $photos = Photos::all();

        foreach ($photos as &$photo) {
            $photo['users'] = Shares::where('photo_id', '=', $photo['id'])->get()->pluck('user_to')->toArray();
        }
        unset($photo);

        return response()->json([
            'data' => $photos
        ], 200);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Photos::find($id)
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $name = $this->uploadPhoto($request);

        if ($name) {
            # save to DB
            $photo = Photos::create([
                'url' => 'storage/' . $name,
                'owner_id' => $this->user->id
            ]);

            return response()->json([
                'data' => $photo
            ], 200);
        } else {
            return response()->json([
                'error' => true
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $photo = Photos::find($id);

        if (!$this->checkOwner($id))
            return response()->json([
                'error' => true
            ], 403);

        if ($request->name)
            $photo->name = $request->name;

        if ($request->file('photo'))
            $photo->url = 'storage/' . $this->uploadPhoto($request);

        $photo->save();

        return response()->json($photo, 200);
    }

    public function delete($id)
    {
        if (!$this->checkOwner($id))
            return response()->json([
                'error' => true
            ], 403);

        Photos::find($id)->delete();

        return response()->json([
            'status' => true
        ], 204);
    }
}
