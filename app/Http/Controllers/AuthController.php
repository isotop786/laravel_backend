<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;


class AuthController extends Controller
{
    //register
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->only('first_name','last_name','email')
        +[
            'password' => Hash::make($request->input('password')),
            'is_admin' => 1
        ]
    );

        return response($user, Response::HTTP_CREATED);

    }

    // Login Method
    public function login(Request $request){
        if(!Auth::attempt($request->only('email','password')))
        {
            return response([
                'error' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        // generating token
        $jwt = $user->createToken('token')->plainTextToken;

        // persisting token to cookie;
        $cookie = cookie('jwt',$jwt, 60 * 24);



        return response([
            'message' => 'Login Success',
        ],200)->withCookie($cookie);
    }

    // Getting authenticated users
    public function user(Request $request)
    {
        return $request->user();
    }

    // Logout Method
    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Logout Success',
        ])->withCookie($cookie);
    }



}
