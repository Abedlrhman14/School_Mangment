<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\classController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProfileContoller;
use App\Http\Controllers\SuperHomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\EnsureTeahcer;
use App\Models\Grade;
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
    Route::post('/tasks/{id}',[TaskController::class,'store'])->middleware(EnsureTeahcer::class);
    Route::get('/tasks/{task}',[TaskController::class,'show']);
    Route::put('/tasks/{task}',[TaskController::class,'update'])->middleware(EnsureTeahcer::class);
    Route::delete('/tasks/{task}',[TaskController::class,'destroy'])->middleware(EnsureTeahcer::class);
    Route::post('/teachers/{teacherId}/subject',[UserController::class,'assignSubject'])->middleware(EnsureTeahcer::class);
    Route::get('/classes', [classController::class, 'index']);
    // Route::post('/classes', [ClassController::class, 'store'])->middleware(EnsureTeahcer::class);
    Route::post('/classes', [ClassController::class, 'store']);
    Route::get('/classes/{class}', [ClassController::class, 'show']);
    Route::put('/classes/{class}', [ClassController::class, 'update'])->middleware(EnsureTeahcer::class);
    Route::get('/classDetailes/{classId}',[ClassController::class,'showClassDeteils']);
    // Route::post('/classes/{class}/students', [ClassController::class, 'addStudent'])->middleware(EnsureTeahcer::class);
    Route::post('/classes/{class}/students', [ClassController::class, 'addStudent']);
    // Route::delete('/classes/{class}', [ClassController::class, 'destroy'])->middleware(EnsureTeahcer::class);
    Route::delete('/classes/{class}', [ClassController::class, 'destroy']);
    Route::get('/grades',[GradeController::class,'index']);
    Route::post('/grades',[GradeController::class,'store'])->middleware(EnsureTeahcer::class);
    Route::delete('/grades/{id}',[GradeController::class,'destroy'])->middleware(EnsureTeahcer::class);
    Route::get('/grades/search',[GradeController::class,'search']);
    Route::get('/class/search',[ClassController::class,'search']);
    Route::get('/profile',[ProfileContoller::class,'show']);
    Route::put('/profile/password',[ProfileContoller::class,'changePassword']);
    Route::get('/show/super',[SuperHomeController::class,'show']);
    Route::get('/show/classses',[SuperHomeController::class,'showClass']);
    Route::get('/show-students',[UserController::class,'showStudents']);
    Route::get('/show-students-detailes/{id}',[UserController::class,'showStudentDetailes']);
});
