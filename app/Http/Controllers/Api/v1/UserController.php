<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('auth.incorrect_password'),
                'status' => 0
            ]);
        }

        return response()->json([
            'message' => __('auth.user_login'),
            'data' => $user,
            'token' => $user->createToken($request->header('User-Agent') ?? $request->ip())->plainTextToken,
            'status' => 1
        ]);
    }
}
