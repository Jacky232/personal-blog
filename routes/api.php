<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'role:User'], function() {

    Route::get('/user1', function() {
 
        return 'Welcome...!!';
        
     });
 
 });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//all user route
//get method show user
Route::get('/users/{id?}',[UserApiController::class,'showUser']);
//add user using post 
Route::post('/add-user',[UserApiController::class,'addUser']);
//add multiple user using post
Route::post('/add-multiple-user',[UserApiController::class,'addMultipleUser']);
//update user details using put
Route::put('/update-user-details/{id}',[UserApiController::class,'updateUserDetails']);
//update single record user data using patch
Route::patch('/update-single-record/{id}',[UserApiController::class,'updateSingleRecord']);
//delete user data using parameter delete method
Route::delete('/delete-user/{id}',[UserApiController::class,'deleteUser']);
//delete user data using json delete method
Route::delete('/delete-user-json',[UserApiController::class,'deleteUserJson']);
//delete multiple user data using parameter delete method
Route::delete('/delete-multiple-user/{ids}',[UserApiController::class,'deleteMultipleUser']);
//delete multiple user data using json delete method
Route::delete('/delete-multiple-user-json',[UserApiController::class,'deleteMultipleUserJson']);
//add register user using passport with post method
Route::post('/register-user-using-passport',[UserApiController::class,'registerUserUsingPassport']);
//add login user using passport with post method
Route::post('/login-user-using-passport',[UserApiController::class,'loginUserUsingPassport']);




//Post route
Route::get('/all-posts',[PostController::class,'index']);

Route::get('/post/{id}',[PostController::class,'show']);

Route::post('/add-post',[PostController::class,'store']);

Route::put('/update-post/{id}',[PostController::class,'update']);

Route::delete('/delete-post/{id}',[PostController::class,'destroy']);


//category route
Route::get('/all-categories',[CategoryController::class,'index']);

Route::get('/category/{id}',[CategoryController::class,'show']);

Route::post('/add-category',[CategoryController::class,'store']);

Route::put('/update-category/{id}',[CategoryController::class,'update']);

Route::delete('/delete-category/{id}',[CategoryController::class,'destroy']);



Route::get('/roles', [PermissionController::class,'Permission']);