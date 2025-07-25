<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileContoller extends Controller
{

    public function show(Request $request){
        $user = Auth::user();
        Log::info("profile Show" , ['user_id' => $user->id]);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'image' =>$user->image
            ? asset('storage/' .$user->image):asset('assets/user-profile-icon-flat-style-member-avatar-vector-illustration-isolated-background-human-permission-sign-business-concept_157943-15752.avif'),
        ],200);
    }

    //   private function getAccessTokenIdFromJwt($jwt)
    // {
    //     try {
    //         $parts = explode('.', $jwt);
    //         if (count($parts) !== 3) {
    //             return null;
    //         }

    //         $payload = json_decode(base64_decode($parts[1]), true);

    //         return $payload['jti'] ?? null;
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }


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
        User::update([
                'password' => Hash::make($request->new_password),
            ]);

        log::info('Password Change', ['user_id' =>$user->id]);
        return response()->json([
            'message' => 'Pasword changed successfully'
        ],200);
    }


}
