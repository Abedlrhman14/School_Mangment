<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class SuperHomeController extends Controller
{
        public function show(Request $request , User $user , Classes $classes){
        $students = $user->get()->where('role','student')->count();
        $teachers = $user->get()->where('role','teacher')->count();
         $class = $classes->get()->count();
        return response()->json([
            'status' => true,
            'data' => [
                'student' =>$students,
                'techers' =>$teachers,
                'classes' =>$class,
            ]
        ],201);
    }
    public function showClass(Classes $classes ){
        $class  = $classes->with('teacher','grade',)->withCount('students')->paginate(5);
        $classes = Classes::withCount('students')->get();
        return response()->json([
            'status' => 1,
            "data" => $class,
        ],200);
    }
}
