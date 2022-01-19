<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register( Request $request, Response $response ) {
        $filds = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $filds['name'],
            'email' => $filds['email'],
            'password' => bcrypt($filds['password'])
        ]);

        $token = $user->createToken('AcompanyToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login( Request $request, Response $response ) {
        $filds = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        /// check the email
        $user = User::where('email', $filds['email'])->first();

        /// check the password
        if( !$user || !Hash::check($filds['password'], $user->password)){
            return response( [
                'message' => 'Credenciais invalídas!',
            ], 401);
        }

        $token = $user->createToken('AcompanyToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout( Request $request ){

        auth()->user()->tokens()->delete();

        return [
            'message' => 'logged out'
        ];
    }
}
