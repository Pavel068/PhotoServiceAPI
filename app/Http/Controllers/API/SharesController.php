<?php

namespace App\Http\Controllers\API;

use App\Shares;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SharesController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    public function sharePhoto(Request $request, $id)
    {
        $photos = $request->photos;
        foreach ($photos as $photo) {
            Shares::create([
                'photo_id' => $photo,
                'user_from' => $this->user->id,
                'user_to' => $id
            ]);
        }

        return response()->json([
            'existing_photos' => Shares::where('user_from', '=', $this->user->id)->get()
        ], 201);
    }
}
