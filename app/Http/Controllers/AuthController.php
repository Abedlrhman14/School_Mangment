<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
        {
            $validator=Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=> 0,
                'message'=> "validation error",
                'data'=> $validator-> errors()->all(),
            ]);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role' =>'student', //role = student by defult
        ]);

        $resposne =[];
        $resposne ['token'] = $user->createToken("MyApp")->accessToken;
        $resposne ['user'] = $user->name;
        $resposne ['email'] = $user->email;
        $resposne ['role'] = $user->role;

        return response()->json([
            'status'=>1 ,
            'message' => 'user Register',
            'date' => $resposne,
        ]);
        }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email',$request ->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['message' => 'Invalid credentials'],401);
        }

        $token = $user->createToken('SchoolSystemToken')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);

    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json(['message' => 'logged out']);
    }
}
