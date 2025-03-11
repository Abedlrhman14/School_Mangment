<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;




class UserController extends Controller
{
    public function UpdateRole(Request $request,$userID){

        // if(!Auth::check()||Auth::user()->role !=='super_admin'){
        //     return response()->json(['message' => 'Unauthorized'],403);
        // }
        $request -> validate([
            'role' => 'required|in:student,teacher'
        ]);

        $user = User::findOrFail($userID);
        $user -> role = $request->role;
        $user ->save();

        return response()->json([
            'message' => 'Role updated successfully',
            'user' => $user,
        ]);
    }

    public function assignSubject(Request $request,$teacherId){
        $request->validate([
            'subject_id' => 'required|exists:subjects,subject_id'
        ]);

        $teacher = User::findOrFail($teacherId);
        $teacher->subjects()->syncWithoutDetaching([$request->subject_id]);
        return response()->json(["message"=> "The teacher is linked to thee subject"]);
    }
}
