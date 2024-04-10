<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Auth;
use Illuminate\Http\Request;
use Log;

class TaskController extends Controller
{
    public function createTask(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'string|nullable',
                'priority' => 'string|in:low,medium,high',
            ]);

            $workspace = Workspace::whereHas('boards', function ($query) use ($id) {
                $query->where('id', $id);
            })->first();

            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found or board not associated with the workspace',
                ], 404);
            }

            $board = $workspace->boards()->find($id);

            if (!$board) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Board not found',
                ], 404);
            }


            $task = $board->tasks()->create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'priority' => $validatedData['priority'],
            ]);

            return response()->json([
                'status' => 'OK',
                'data' => $task,
            ], 201);



        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'An error occurred',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateTask(Request $request, $id, $taskId)
    {
        try {
            $workspace = Workspace::whereHas('boards', function ($query) use ($id) {
                $query->where('id', $id);
            })->first();

            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found or board not associated with the workspace',
                ], 404);
            }

            $board = $workspace->boards()->find($id);

            if (!$board) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Board not found',
                ], 404);
            }

            $task = $board->tasks()->find($taskId);

            if (!$task) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Task not found',
                ], 404);
            }

            if (empty($request->all())) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'No data provided for update',
                ], 422);
            }

            $validatedData = $request->validate([
                'name' => 'string|max:255',
                'description' => 'string|nullable',
                'priority' => 'string|in:low,medium,high',
            ]);


            $task->update($validatedData);

            return response()->json([
                'status' => 'OK',
                'data' => $task,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'An error occurred',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteTask($id, $taskId)
    {
        try {
            $workspace = Workspace::whereHas('boards', function ($query) use ($id) {
                $query->where('id', $id);
            })->first();

            if (!$workspace) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Workspace not found or board not associated with the workspace',
                ], 404);
            }

            $board = $workspace->boards()->find($id);

            if (!$board) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Board not found',
                ], 404);
            }

            $task = $board->tasks()->find($taskId);

            if (!$task) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Task not found',
                ], 404);
            }

            $task->delete();

            return response()->json([
                'status' => 'OK',
                'message' => 'Task deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'An error occurred',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
