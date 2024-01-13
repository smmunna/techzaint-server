<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
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

// Public routers POST/PUT/PATCH METHOD
Route::post('/login', [AuthController::class, 'login']); //for login user
Route::post('/register', [UserController::class, 'registerUser']); //Register new user

Route::post('/send-code', [UserController::class, 'sendVerificationCode']); //Sending the verification code to email
Route::patch('/verify-email', [UserController::class, 'verifyEmail']);  //Verify the email address

Route::post('/create-blog', [BlogController::class, 'createBlog']); //create new blog
Route::get('/view-blog', [BlogController::class, 'viewBlogs']); //view blogs
Route::get('/single-blog', [BlogController::class, 'singleBlogs']); //view blogs
Route::get('/blogs-by-month-and-year', [BlogController::class, 'viewBlogsByMonthAndYear']); //month and year wise
Route::delete('/delete-blog', [BlogController::class, 'DeleteBlogs']); //Deleting the blogs
Route::put('/edit-blog', [BlogController::class, 'EditBlogs']);  //Edit blogs

Route::get('/individual-user-info', [UserController::class, 'signleUserInfo']);
Route::get('/top5blogs', [BlogController::class, 'top5Blog']);

// Authentication Routers
Route::group([

    'middleware' => ['api', 'jwt.auth'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    // All the GET Method will be here
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


// User Routers
Route::group([

    'middleware' => ['api', 'jwt.auth'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'user'

], function ($router) {

    // User Controller;
    Route::get('/userlist', 'UserController@userList'); //User List getting
    Route::get('/individual-blogs', [BlogController::class, 'individualBlogs']); //user blogs


});
