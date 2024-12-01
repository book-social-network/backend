<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DetailGroupUserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
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
// Assessment
Route::group([
    'prefix' => 'assessment'
], function ($router) {
    Route::get('/get-all', [AssessmentController::class, 'index']);
    Route::get('/get/{id}', [AssessmentController::class, 'getAssessment']);
    Route::post('/insert', [AssessmentController::class, 'insert']);
    Route::post('/update/{id}', [AssessmentController::class, 'update']);
    Route::post('/update-state-read/{idBook}', [AssessmentController::class, 'updateStateRead']);
    Route::delete('/delete/{id}', [AssessmentController::class, 'delete']);
    Route::get('/get-assessment-user/{idUser}', [AssessmentController::class, 'getAssessmentOfUser']);
    Route::get('/get-assessment-book/{idBook}', [AssessmentController::class, 'getAssessmentOfBook']);
});

// Auth finish test
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

// Author finish test
Route::group([
    'prefix' => 'author'
], function ($router) {
    Route::get('/get-all', [AuthorController::class, 'index']);
    Route::get('/get/{id}', [AuthorController::class, 'getAuthor']);
    Route::post('/insert', [AuthorController::class, 'insert']);
    Route::post('/update/{id}', [AuthorController::class, 'update']);
    Route::delete('/delete/{id}', [AuthorController::class, 'delete']);
    // type
    Route::post('/insert-type', [AuthorController::class, 'insertTypeBookForAuthor']);
    Route::delete('/delete-type/{id}', [AuthorController::class, 'deleteTypeBookForAuthor']);
    Route::get('/get-all-type/{idAuthor}', [AuthorController::class, 'getAllTypeOfAuthor']);
    // book
    Route::get('/get-all-book/{idAuthor}', [AuthorController::class, 'getAllBookOfAuthor']);
});

// Book
Route::group([
    'prefix' => 'book'
], function ($router) {
    Route::get('/get-all', [BookController::class, 'index']);
    Route::get('/get/{id}', [BookController::class, 'getBook']);
    Route::post('/insert', [BookController::class, 'insert']);
    Route::post('/update/{id}', [BookController::class, 'update']);
    Route::delete('/delete/{id}', [BookController::class, 'delete']);
    //type
    Route::post('/insert-type', [BookController::class, 'insertTypeBookForBook']);
    Route::delete('/delete-type/{id}', [BookController::class, 'deleteTypeBookForBook']);
    //author
    Route::post('/insert-author', [BookController::class, 'insertAuthorForBook']);
    Route::delete('/delete-author/{id}', [BookController::class, 'deleteAuthorForBook']);
    // post
    Route::get('/get-all-post/{idBook}', [BookController::class, 'getAllPostOfBook']);
});

// Detail Group User finish test
Route::group([
    'prefix' => 'detail-group-user'
], function ($router) {
    Route::get('/get-all', [DetailGroupUserController::class, 'index']);
    Route::get('/get-all-user/{idGroup}', [DetailGroupUserController::class, 'getAllUserInGroup']);
    Route::get('/get-all-user-want-join/{idGroup}', [DetailGroupUserController::class, 'getAllUserWantJoinGroup']);
    Route::post('/insert', [DetailGroupUserController::class, 'insert']);
    Route::post('/invite', [DetailGroupUserController::class, 'inviteGroup']);
    Route::post('/update-state', [DetailGroupUserController::class, 'updateState']);
    Route::post('/update-image-default', [DetailGroupUserController::class, 'updateDefaultImage']);
    Route::post('/update-role', [DetailGroupUserController::class, 'updateRole']);
    Route::post('/delete', [DetailGroupUserController::class, 'delete']);
});

// Follow finish test
Route::group([
    'prefix' => 'follow'
], function ($router) {
    Route::get('/get-all', [FollowController::class, 'getAllFollow']);
    Route::get('/follow/{id}', [FollowController::class, 'handleFollow']);
    Route::get('/unfollow/{id}', [FollowController::class, 'handleUnfollow']);
    Route::get('/suggest-friends', [FollowController::class, 'suggestFriends']);
});
// Group finish test
Route::group([
    'prefix' => 'group'
], function ($router) {
    Route::get('/get-all', [GroupController::class, 'index']);
    Route::get('/get/{id}', [GroupController::class, 'get']);
    Route::post('/insert', [GroupController::class, 'insert']);
    Route::post('/update/{id}', [GroupController::class, 'update']);
    Route::delete('/delete/{id}', [GroupController::class, 'delete']);
    Route::get('/get-all-post-group/{id}', [GroupController::class, 'getAllPostInGroup']);
});

//Notification
Route::group([
    'prefix' => 'notification'
], function ($router) {
    Route::get('/get-all', [NotificationController::class, 'index']);
    Route::post('/update-state/{id}', [NotificationController::class, 'updateState']);
    Route::delete('/delete/{id}', [NotificationController::class, 'delete']);
});

// Post
Route::group([
    'prefix' => 'post'
], function ($router) {
    Route::get('/get-all', [PostController::class, 'index']);
    Route::get('/get/{id}', [PostController::class, 'getPost']);
    Route::get('/get-post-in-group', [PostController::class, 'getPostOnAllGroup']);
    Route::post('/insert', [PostController::class, 'insert']);
    Route::post('/update/{id}', [PostController::class, 'update']);
    Route::delete('/delete/{id}', [PostController::class, 'delete']);
    // Book
    Route::post('/insert-book', [PostController::class, 'insertBook']);
    Route::post('/delete-book', [PostController::class, 'deleteBook']);
    // Like
    Route::post('/insert-like', [PostController::class, 'insertLike']);
    Route::delete('/delete-like/{idPost}', [PostController::class, 'deleteLike']);
    // Comment
    Route::post('/insert-comment', [PostController::class, 'insertComment']);
    Route::post('/update-comment/{idComment}', [PostController::class, 'updateComment']);
    Route::delete('/delete-comment/{idComment}', [PostController::class, 'deleteComment']);
});

// Profession
Route::group([
    'prefix' => 'profession'
], function ($router) {
    Route::post('/search', [ProfessionController::class, 'search']);
    Route::post('/sort', [ProfessionController::class, 'sort']);
    Route::get('/get-points', [ProfessionController::class, 'getAllPoint']);
});
// Share
Route::group([
    'prefix' => 'share'
], function ($router) {
    Route::get('/get-all', [ShareController::class, 'index']);
    Route::post('/insert', [ShareController::class, 'insert']);
    Route::post('/update/{id}', [ShareController::class, 'update']);
    Route::delete('/delete/{id}', [ShareController::class, 'delete']);
    // author
    Route::get('/get-all-author/{idType}', [ShareController::class, 'getAllAuthorOfType']);
    // book
    Route::get('/get-all-book/{idType}', [ShareController::class, 'getAllBookOfType']);
});
// Type finish test
Route::group([
    'prefix' => 'type'
], function ($router) {
    Route::get('/get-all', [TypeController::class, 'index']);
    Route::post('/insert', [TypeController::class, 'insert']);
    Route::post('/update/{id}', [TypeController::class, 'update']);
    Route::delete('/delete/{id}', [TypeController::class, 'delete']);
    // author
    Route::get('/get-all-author/{idType}', [TypeController::class, 'getAllAuthorOfType']);
    // book
    Route::get('/get-all-book/{idType}', [TypeController::class, 'getAllBookOfType']);
});

// User finish test
Route::group([
    'prefix' => 'user'
], function ($router) {
    Route::get('/get-all', [UserController::class, 'index']);
    Route::get('/get/{id}', [UserController::class, 'getUser']);
    Route::post('/insert', [UserController::class, 'insert']);
    Route::post('/update', [UserController::class, 'update']);
    Route::post('/update-point', [UserController::class, 'updatePoint']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
    // post
    Route::get('/get-all-post/{id}', [UserController::class, 'getAllPostOfUser']);
    Route::get('/get-all-post-user-follow', [UserController::class, 'getAllPostUserFollow']);
    // comment
    Route::get('/get-all-comment/{id}', [UserController::class, 'getAllComment']);
    // like
    Route::get('/get-all-like/{id}', [UserController::class, 'getAllLike']);
});


// share
Route::group([
    'prefix' => 'share'
], function ($router) {
    Route::get('/get-all', [ShareController::class, 'index']);
    Route::get('/get/{id}', [ShareController::class, 'getShare']);
    Route::post('/insert', [ShareController::class, 'insert']);
    Route::delete('/delete/{id}', [ShareController::class, 'delete']);
    // user
    Route::get('/get-all-user/{id}', [ShareController::class, 'getAllShareOfUser']);
    // book
    Route::get('/get-all-book/{id}', [ShareController::class, 'getAllShareOfBook']);
});

// notification
Route::group([
    'prefix' => 'notification'
], function ($router) {
    Route::get('/get-all', [NotificationController::class, 'index']);
    Route::post('/update-state/{id}', [NotificationController::class, 'updateState']);
    Route::delete('/delete/{id}', [NotificationController::class, 'delete']);
});

// view
Route::group([
    'prefix' => 'view'
], function ($router) {
    Route::get('/total-views', [ViewController::class, 'getTotalViews']);
    Route::get('/views-by-day', [ViewController::class, 'getViewsByDay']);
    Route::get('/views-by-week', [ViewController::class, 'getViewsByWeek']);
    Route::get('/views-by-month', [ViewController::class, 'getViewsByMonth']);
    Route::get('/views-by-year', [ViewController::class, 'getViewsByYear']);
    Route::get('/statistical', [ViewController::class, 'statistical']);
});
