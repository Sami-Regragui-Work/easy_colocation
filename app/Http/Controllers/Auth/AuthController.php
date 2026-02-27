<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => User::count() === 0,
        ]);

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);

        if ($user->isBanned())
            throw ValidationException::withMessages([
                'email' => ['Account is banned.'],
            ]);

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('activeColocations')
        ]);
    }
}
