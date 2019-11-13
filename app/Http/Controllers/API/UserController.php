<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Users;
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
                'token' => $user->createToken('MyApp')->accessToken,
                'user' => $user
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /*
     * Logout api
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }

        return response()->json([
            'status' => true
        ], 200);
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

    public function search(Request $request)
    {
        $filter = '%' . $request->search . '%';
        $users = Users::whereRaw("first_name LIKE '$filter' OR surname LIKE '$filter' OR phone LIKE '$filter'")->get();

        return response()->json($users, 200);
    }
}
