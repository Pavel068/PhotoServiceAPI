<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['phone' => request('phone'), 'password' => request('password')])) {
            $user = Auth::user();
            return response()->json([
                'user' => $user
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Register api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'surname' => 'required',
            'phone' => 'required|unique:users|min:11|max:11',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return response()->json([
            'id' => $user->id,
            'token' => $user->createToken('MyApp')->accessToken
        ], 201);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
