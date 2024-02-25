<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Workspace\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// User routes
Route::group([
    'middleware' => 'auth',
    'prefix' => 'users'
], function ($router) {
    Route::get('all', [UserController::class, 'getAllUsers']);
    Route::put('update/{id}', [UserController::class, 'updateUser']);
});


// Workspace routes
Route::group([
    'middleware' => 'auth',
    'prefix' => 'workspaces'
], function ($router) {
    Route::get('all', [WorkspaceController::class, 'getWorkspaces']);
    Route::get('one/{id}', [WorkspaceController::class, 'getWorkspaceById']);
    Route::get('members/{id}', [WorkspaceController::class, 'getWorkspaceMembers']);
    Route::post('create', [WorkspaceController::class, 'createWorkspace']);
    Route::put('update/{id}', [WorkspaceController::class, 'updateWorkspace']);
    Route::put('new-member/{id}', [WorkspaceController::class, 'addMemberToWorkspace']);
    Route::delete('delete/{id}', [WorkspaceController::class, 'deleteWorkspace']);
});

// Auth routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});
