<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email')
            + ['password' => \Hash::make($request->input('password')),'is_admin' => 1]);

        return response($user,Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!\Auth::attempt($credentials)) {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }

        return response([
            'error' => 'invalid credentials',
        ],Response::HTTP_UNAUTHORIZED);

        $user = \Auth::user();

        $jwt = $user->createToken('authToken')->plainTextToken;

        $cookie = cookie('jwt', $jwt, 60*24);

        return ([
            'message' => 'success',
        ])->withCookie($cookie);
    }

    public function user(){

    }
}
