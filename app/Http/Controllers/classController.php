<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'student_id'=> 'required',
            // 'student_id.*'=> 'exists::users,id,role,student',
        ]);

        $class->students()->sync($request->student_id);
        $class->load('students');
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
}
