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
}
