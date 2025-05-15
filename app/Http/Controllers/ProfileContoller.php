<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class ProfileContoller extends Controller
{

    public function show(Request $request){
        $user = Auth::user();
        Log::info("profile Show" , ['user_id' => $user->id]);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],200);
    }

    public function changePassword(Request $request){
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        if(!Hash::check($request ->current_password, $user->password)){
            return response()->json([
                'message' => 'Current password is incorrect',
            ],422);
        }


       $user->update([
                'password' => Hash::make($request->new_password),
            ]);

        log::info('Password Change', ['user_id' =>$user->id]);
        return response()->json([
            'message' => 'Pasword changed successfully'
        ],200);
    }


}
