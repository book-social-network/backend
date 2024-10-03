<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DetailGroupUserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
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
    Route::get('/get-all-author/{idType}', [TypeController::class,'getAllAuthorOfType']);
    Route::get('/get-all-book/{idType}', [TypeController::class,'getAllBookOfType']);

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

Route::group([
    'prefix' => 'author'
], function ($router) {
    Route::get('/get-all', [AuthorController::class,'index']);
    Route::get('/get/{id}', [AuthorController::class,'getAuthor']);
    Route::post('/insert', [AuthorController::class, 'insert']);
    Route::post('/update/{id}', [AuthorController::class, 'update']);
    Route::delete('/delete/{id}', [AuthorController::class, 'delete']);
    Route::post('/insert-type', [AuthorController::class, 'insertTypeBookForAuthor']);
    Route::delete('/delete-type/{id}', [AuthorController::class, 'deleteTypeBookForAuthor']);
    Route::get('/get-all-type/{idAuthor}',[AuthorController::class,'getAllTypeOfAuthor']);
    Route::get('/get-all-book/{idAuthor}',[AuthorController::class,'getAllBookOfAuthor']);
});

Route::group([
    'prefix' => 'book'
], function ($router) {
    Route::get('/get-all', [BookController::class,'index']);
    Route::get('/get/{id}', [BookController::class,'getBook']);
    Route::post('/insert', [BookController::class, 'insert']);
    Route::post('/update/{id}', [BookController::class, 'update']);
    Route::delete('/delete/{id}', [BookController::class, 'delete']);
    //type
    Route::post('/insert-type', [BookController::class, 'insertTypeBookForBook']);
    Route::delete('/delete-type/{id}', [BookController::class, 'deleteTypeBookForBook']);
    Route::get('/get-all-type/{idBook}',[BookController::class,'getAllTypeOfAuthor']);
    //author
    Route::post('/insert-author', [BookController::class, 'insertAuthorForBook']);
    Route::delete('/delete-author/{id}', [BookController::class, 'deleteAuthorForBook']);
    Route::get('/get-all-author/{idAuthor}',[BookController::class,'getAllAuthorForBook']);
});

Route::group([
    'prefix' => 'user'
], function ($router) {
    Route::get('/get-all', [UserController::class,'index']);
    Route::get('/get/{id}', [UserController::class,'getUser']);
    Route::post('/insert', [UserController::class, 'insert']);
    Route::post('/update/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
});


Route::group([
    'prefix' => 'follow'
], function ($router) {
    Route::get('/get-all', [FollowController::class,'getAllFollow']);
    Route::get('/follow/{id}', [FollowController::class,'handleFollow']);
    Route::get('/unfollow/{id}', [FollowController::class,'handleUnfollow']);
});

Route::group([
    'prefix' => 'assessment'
], function ($router) {
    Route::get('/get-all', [AssessmentController::class,'index']);
    Route::get('/get/{id}', [AssessmentController::class,'getAssessment']);
    Route::post('/insert', [AssessmentController::class, 'insert']);
    Route::post('/update/{id}', [AssessmentController::class, 'update']);
    Route::post('/update-state-read/{idBook}', [AssessmentController::class, 'updateStateRead']);
    Route::delete('/delete/{id}', [AssessmentController::class, 'delete']);
    Route::get('/get-assessment-user/{idUser}', [AssessmentController::class,'getAssessmentOfUser']);
    Route::get('/get-assessment-book/{idBook}', [AssessmentController::class,'getAssessmentOfBook']);

});


