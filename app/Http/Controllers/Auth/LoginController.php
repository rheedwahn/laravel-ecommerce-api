<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\PrivateUserResource;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if(!$token = auth()->attempt($request->only('email', 'password'))){
            return response()->json([
                'errors' => [
                    'email' => ['could not sign you in with those credentials']
                ]
            ], 422);
        }

        return (new PrivateUserResource($request->user()))
                ->additional([
                    'meta' => [
                        'token' => $token
                    ]
                ]);
    }
}
