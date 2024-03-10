<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Workspace;

use Auth;
use Illuminate\Http\Request;
use Log;

class BoardController extends Controller
{
    public function getBoards(Request $request, $id)
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

            $boards = Board::where('workspace_id', $id)->get();
            return response()->json([
                'status' => "OK",
                'data' => $boards
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function createBoard(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $workspace = $user->workspaces()->find($id);

            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found',
                ], 404);
            }

            $board = $workspace->boards()->create([
                'name' => $validatedData['name'],
            ]);

            return response()->json([
                'status' => "OK",
                'data' => $board
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    public function updateBoard(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $board = Board::find($id);

            if (!$board) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Board not found',
                ], 404);
            }

            $board->name = $validatedData['name'];

            $board->save();

           /*  $board->update($validatedData); */

            return response()->json([
                'status' => "OK",
                'data' => $board
            ]);
            

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function deleteBoard(Request $request, $id)
    {
        try {
            $board = Board::find($id);
            
            if (!$board) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Board not found',
                ], 404);
            }

            $board->delete();

            return response()->json([
                'status' => "OK",
                'message' => "Board deleted"
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
