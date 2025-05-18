<?php
namespace App\Http\Controllers;
use App\Models\Classes;
use App\Models\Task;
use App\Models\User;
use App\Notifications\NewTaskNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use PhpParser\Builder\Class_;

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
    public function store(Request $request , Classes $class)
    {
        $user = Auth::user();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docs|max:2048',
            'book' => 'nullable|file|mimes:pdf,doc,docs|max:2048',
            // 'subject_id' => 'required|exists:subjects,subject_id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = Classes::find($request->class_id);
        $filepath = null;
        $bookpath = null;
        if($request->hasFile('file')){
            $filepath = $request->file('file')->store('tasks','public');
        }
            if($request->hasFile('book')){
            $bookpath = $request->file('book')->store('tasks','public');
        }
        $tasks =Task::create([
            'teacher_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filepath,
            'book_path' => $bookpath,
            // 'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
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
            'book' => 'nullable|file|mimes:pdf,doc,docs|max:2048',
        ]);

        $filepath = $task -> file_path;
        $bookpath = $task -> book_path;
        if($request->hasFile('file')){
           if($filepath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filepath)){
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filepath);
           }
           $filepath =$request->file('file')->store('tasks','public');
        }
             if($request->hasFile('book')){
           if($filepath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filepath)){
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filepath);
           }
           $filepath =$request->file('book')->store('tasks','public');
        }
        $task->update([
            // 'title' => $request->title,
            // 'description' => $request->description,
            'file_path' => $filepath,
            'book_path' => $bookpath,
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

    public function downloadTask(Task $task){
        $class = Classes::findOrFail($task->class_id);
        if(!$task->file_path || !Storage::disk('public')->exists($task->file_path)){
            return response()->json(['message' => 'Task file not found'],404);
        }
        return Storage::disk('public')->download($task->file_path);
    }

    public function downloadBook(Task $task){
        $clas = Classes::findOrFail($task->class_id);

            if(!$task->book_path || !Storage::disk('public')->exists($task->book_path)){
            return response()->json(['message' => 'Book not found'],404);
        }

            return Storage::disk('public')->download($task->book_path);

    }
}
