<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\LoginRequest;

class LoginController extends Controller
{
    /**
     * Handle user login request.
     */
    public function store(LoginRequest $request): JsonResponse 
    {
        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Get the authenticated user
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'api_token' => $token, // Include the token in the response
            ], 200);
        }

        // Authentication failed
        return response()->json([
            'message' => 'Invalid login credentials',
        ], 401);
    }
}
