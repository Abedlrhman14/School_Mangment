<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('teacher')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if(!Auth::check()||Auth::user()->role !=='teacher'){
        //     return response()->json(['message' => 'Unauthorized'],403);
        // }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docs|max:2048',
        ]);

        $filepath = null;
        if($request->hasFile('file')){
            $filepath = $request->file('file')->store('tasks','public');
        }

        $tasks =Task::create([
            'teacher_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filepath,
        ]);
        return response()->json($tasks,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task->load('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docs|max:2048',
        ]);

        $filepath = $task -> file_prth;
        if($request->hasFile('file')){
           if($filepath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filepath)){
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filepath);
           }
           $filepath =$request->file('file')->store('tasks','public');
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filepath,
        ]);

        return response()->json(['message' => 'Task updated successfully','task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if($task->fill_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($task->file_path)){
            \Illuminate\Support\Facades\Storage::disk('public')->delete($task->file_path);
        }

        $task->delete();

        return response()->json(['message'=>'Task deleted successfully']);
    }
}
