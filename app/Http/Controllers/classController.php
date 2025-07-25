<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Builder\Class_;

class classController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Classes $class)
    {
        $classes = Auth::user()->classes;
        return response()->json($classes);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_id'=>'required|string|exists:grades,id'
        ]);
        $grade = Grade::where('name', $request->grade_name)->first();
        $class = Classes::create([
            'name'=> $request ->name,
            'teacher_id'=> Auth::id(),
            'grade_id'=>$request->grade_id,
        ]);
        return response()->json($class,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classes $class)
    {
        $class ->load('students','tasks');
        return response()->json($class);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classes $class)
    {
        $request -> validate([
            'name' => 'required|string|max:255'
        ]);

        $class->update(['name' => $request->name]);
        return response()->json($class);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $class)
    {
        $class->delete();
        return response()->json(['message' => 'Class deleted']);

    }
     public function addStudent(Request $request,Classes $class){
        $Validator = Validator::make($request->all(),[
                 'student_email'=> 'required',
                 'student_email.*'=> 'exists:users,email',
        ]);

        $emails = is_array($request->student_email) ? $request->student_email : [$request->student_email];
          $studentIds = User::whereIn('email',$emails)
                      ->pluck('id')
                      ->toArray();
        $class->students()->syncWithoutDetaching($studentIds);
        $class->load('students');
         if($Validator->fails() ){
            return response()->json([
                'message' => 'somethins wrong',
                'errors' => $Validator->errors(),
            ],404);
        }
        if (empty($studentIds)) {
        return response()->json([
            'message' => 'No students found with the provided email',
            'errors' => ['student_email' => ['The email does not exist .']]
        ], 422);
    }
    if($class->students()->where('student_id', $studentIds)->exists()){
        return response()->json([
            'message' => 'the student alredy exist',
            'student_email' => $emails
        ],201);
    }
        return response()->json($class);
     }


     public function studentClasses(){
        $classes = Auth::user()->enrolledClasses;
        return response()->json($classes);
     }
     public function search(Request $request){
        $query = $request->query('q'); //search by name
        $grade_id = $request->query('grade_id'); //search by grade id
        $classes = Classes::query()->when($query , function ($q) use ($query){
            return $q->where('name' , 'LIKE' , "%{$query}%");
        })
        ->when($grade_id, function ($q) use ($grade_id) {
            return $q->where('grade_id',$grade_id);
        })
        ->with(['grade','teacher'])
        ->get();
        return response()->json($classes , 200);
     }
     public function showClassDeteils(Classes $classId){
        return response()->json([
            'status' => 1,
            'data' => $classId->load('students','tasks','grade')
        ],200);
     }
}
