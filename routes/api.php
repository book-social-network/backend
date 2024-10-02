<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DetailGroupUserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TypeController;
use App\Models\DetailGroupUser;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', action: [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);
});


// Type
Route::group([
    'prefix' => 'type'
], function ($router) {
    Route::get('/get-all', [TypeController::class,'index']);
    Route::post('/insert', [TypeController::class, 'insert']);
    Route::post('/update/{id}', [TypeController::class, 'update']);
    Route::delete('/delete/{id}', [TypeController::class, 'delete']);
});

// Group
Route::group([
    'prefix' => 'group'
], function ($router) {
    Route::get('/get-all', [GroupController::class,'index']);
    Route::post('/insert', [GroupController::class, 'insert']);
    Route::post('/update/{id}', [GroupController::class, 'update']);
    Route::delete('/delete/{id}', [GroupController::class, 'delete']);
});

// DEtail Group User
Route::group([
    'prefix' => 'detail-group-user'
], function ($router) {
    Route::get('/get-all', [DetailGroupUserController::class,'index']);
    Route::get('/get-all-user/{idGroup}',[DetailGroupUserController::class,'getAllUserInGroup']);
    Route::post('/insert', [DetailGroupUserController::class, 'insert']);
    Route::post('/update-state', [DetailGroupUserController::class, 'updateState']);
    Route::post('/update-role', [DetailGroupUserController::class, 'updateRole']);
    Route::delete('/delete/{id}', [DetailGroupUserController::class, 'delete']);
});
