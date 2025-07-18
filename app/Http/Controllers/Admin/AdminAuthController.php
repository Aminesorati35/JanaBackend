<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
    // if() || !Hash::check($request->password,$admin->password)
    public function login(Request $request){
        $request->validate([
            "email"=>'required|email',
            "password"=>"required"
        ]);
        $admin = Admin::where('email',$request->email)->first();
        if(!$admin ){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $admin->createToken('admin-token')->plainTextToken;
        return response()->json([
            "admin"=>$admin,
            "token"=>$token,
            "message"=> "Login successful"
        ]);
    }

    public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();
            return response()->json([
            'message' => 'Logout successful'
        ]);
        }
    public function me(Request $request){
        return response()->json([
            'admin'=>$request->user()
        ]);
    }
}
