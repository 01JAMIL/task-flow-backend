<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getAllUsers(Request $request)
    {
        return response()->json([
            'status' => "OK",
            'data' => User::all()
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $rules = [
                'firstName' => 'required|string|max:20',
                'lastName' => 'required|string|max:20',
                'username' => 'required|string|max:20',
                'phone' => 'required|string|min:8|max:8',
                'bio' => 'nullable|string',
            ];

            $validatedData = $request->validate($rules);

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'User not found',
                ], 404);
            }

            
            $user->update($validatedData);

            return response()->json([
                'status' => "OK",
                'data' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
