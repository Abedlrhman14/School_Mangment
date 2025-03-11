<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\classController;
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
    Route::post('/teachers/{teacherId}/subject',[UserController::class,'assignSubject'])->middleware(EnsureTeahcer::class);
    Route::get('/classes', [classController::class, 'index']);
    Route::post('/classes', [ClassController::class, 'store'])->middleware(EnsureTeahcer::class);
    Route::get('/classes/{class}', [ClassController::class, 'show']);
    Route::put('/classes/{class}', [ClassController::class, 'update'])->middleware(EnsureTeahcer::class);
    Route::post('/classes/{class}/students', [ClassController::class, 'addStudent'])->middleware(EnsureTeahcer::class);
    Route::delete('/classes/{class}', [ClassController::class, 'destroy'])->middleware(EnsureTeahcer::class);
    // Route::post('/classes/{class}/tasks', [TaskController::class, 'store'])->middleware(EnsureTeahcer::class);

});




// Routes للكلاسات مع Middleware للـ Authentication
// Route::middleware('auth:api')->group(function () {
//          // عرض كل الكلاسات للمدرس
//             // إنشاء كلاس جديد
//     // عرض تفاصيل كلاس معين
//       // تحديث كلاس
//     Route::delete('/classes/{class}', [ClassController::class, 'destroy']); // حذف كلاس
//     Route::post('/classes/{class}/students', [ClassController::class, 'addStudents']); // إضافة طلاب للكلاس
//     Route::get('/student/classes', [ClassController::class, 'studentClasses']); // عرض الكلاسات الخاصة بالطالب
// });
