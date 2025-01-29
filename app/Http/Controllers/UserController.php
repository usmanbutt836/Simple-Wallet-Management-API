<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function store(CreateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Create user and initialize wallet
            $user = User::createUser($request->validated());
            return response()->json([
                'message' => 'User created successfully!',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the user.'], 500);
        }
    }

    public function show($id)
    {
        // Validate if the user exists
        $user = User::with('wallet')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $user->balance = $user->wallet->balance;
        return response()->json([
            'user' => $user
        ], 200);

    }
}
