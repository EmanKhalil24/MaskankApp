<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\renterController;
use App\Http\Controllers\ownerController;

use App\Http\Controllers\Adminscontroller;
use App\Http\Controllers\Ownerscontroller;
use App\Http\Controllers\postsController;
use App\Http\Controllers\Renterscontroller;
use App\Http\Controllers\Requestscontroller;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\postController;

// ****************************************************************
// Eman Apis
Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers'], function(){
    Route::post('posts', 'postController@store');
    Route::put('acceptPost/{post_id}', 'postController@updateStatus');
    Route::get('WaitingAndDone/{ownerId}', 'postController@numberOfWaitingAndDone');
    Route::get('post/{post_id}', 'postController@showPostDetails');
    Route::get('posts/filter', 'postController@filterPosts');
    Route::get('home/renter', 'postController@showRandomPosts');
    Route::get('booked/{renter_id}', 'postController@showBooked');
    Route::get('admin/login', 'postController@login');
});


// ****************************************************************
// Mostafa Apis
Route::post('/register' , renterController::class. '@register');
Route::post('/login' , renterController::class. '@login');
Route::post('/logout' , renterController::class. '@logout')->middleware('auth:sanctum');

Route::post('/ownerRegister' , ownerController::class. '@register');
Route::post('/ownerLogin' , ownerController::class. '@login');
Route::post('/ownerLogout' , ownerController::class. '@logout')->middleware('auth:sanctum');


// ****************************************************************
// Mohummad Apis
Route::post('/adminLogin',[Adminscontroller::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
Route::post('/adminLogout',[Adminscontroller::class,'logout']);
Route::get('/numberofposts',[Postscontroller::class,'postsnumber']);
Route::get('/numberofrequests',[Requestscontroller::class,'requestsnumber']);
Route::get('/waiting',[Postscontroller::class,'waiting']);
Route::get('/acceptable',[Postscontroller::class,'acceptable']);
Route::get('/renters',[Renterscontroller::class,'renters']);
Route::get('/owners',[Ownerscontroller::class,'allowners']);
});


// ****************************************************************
// Mahmmoud Apis
Route::delete('/ownerDestroy/{owner_id}', [ownerController::class,'destroy']);
Route::get('/showOwner/{owner_id}', [ownerController::class,'show']);
Route::put('/updateOwner/{id}', [ownerController::class,'update']);
Route::resource('owner/', ownerController::class);

Route::get('/search',[postController::class,'searchLocal']);
Route::put('/approved/{id}', [AdminsController::class,'approvedPost']);

Route::apiResource('renters/',   RentersController::class);
Route::get('renterShow/{renter_id}',[ RentersController::class,'show']);
Route::put('renterUpdate/{renter_id}',    [RentersController::class,'update']);
Route::delete('renterDestroy/{renter_id}',    [RentersController::class,'destroy']);