<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\PrivateUserResource;
use App\Models\User;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->only('email', 'name', 'password'));
        return new PrivateUserResource($user);
    }
}
