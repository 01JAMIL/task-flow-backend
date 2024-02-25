<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Auth;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class WorkspaceController extends Controller
{
    public function getWorkspaces(Request $request)
    {
        try {
            $user = Auth::user();
            return response()->json([
                'status' => "OK",
                'data' => $user->workspaces
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getWorkspaceById(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $workspace = $user->workspaces()->find($id);
            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found',
                ], 404);
            }
            return response()->json([
                'status' => "OK",
                'data' => $workspace
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getWorkspaceMembers(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $workspace = $user->workspaces()->find($id);
            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found',
                ], 404);
            }

            return response()->json([
                'status' => "OK",
                'data' => $workspace->users
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function createWorkspace(Request $request)
    {
        try {
            $user = Auth::user();
            $rules = [
                'name' => 'required|string|max:50',
                'description' => 'nullable|string',
            ];

            $validatedData = $request->validate($rules);

            $workspace = Workspace::create($validatedData);

            $user->workspaces()->attach($workspace, [
                'join_date' => now(),
                'role' => 'OWNER',
            ]);

            return response()->json([
                'status' => "OK",
                'data' => $workspace
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function updateWorkspace(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $workspace = $user->workspaces()->find($id);
            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found',
                ], 404);
            }

            $rules = [
                'name' => 'required|string|max:50',
                'description' => 'nullable|string',
            ];

            $validatedData = $request->validate($rules);

            $workspace->update($validatedData);

            return response()->json([
                'status' => "OK",
                'data' => $workspace
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function deleteWorkspace(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $workspace = $user->workspaces()->find($id);
            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found',
                ], 404);
            }

            $workspace->delete();

            return response()->json([
                'status' => "OK",
                'message' => 'Workspace deleted'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function addMemberToWorkspace(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $workspace = $user->workspaces()->find($id);
            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found',
                ], 404);
            }

            $rules = [
                'email' => 'required|email|exists:users,email',
                'role' => 'required|string|in:MEMBER,ADMIN',
            ];

            $validatedData = $request->validate($rules);

            $newUser = User::where('email', $validatedData['email'])->first();

            $user->workspaces()->attach($workspace, [
                'join_date' => now(),
                'user_id' => $newUser->id,
                'role' => $validatedData['role'],
            ]);

            return response()->json([
                'status' => "OK",
                'message' => 'User added to workspace'
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
