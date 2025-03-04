<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\EnsureTeahcer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function(){
    Route::post('/logout',[AuthController::class,"logout"]);
    Route::put('/users/{userId}/role',[UserController::class,"UpdateRole"])->middleware(EnsureSuperAdmin::class);
    Route::get('/tasks',[TaskController::class,'index']);
    Route::post('/tasks',[TaskController::class,'store'])->middleware(EnsureTeahcer::class);
    Route::get('/tasks/{task}',[TaskController::class,'show']);
    Route::put('/tasks/{task}',[TaskController::class,'update'])->middleware(EnsureTeahcer::class);
    Route::delete('/tasks/{task}',[TaskController::class,'destroy'])->middleware(EnsureTeahcer::class);
});
