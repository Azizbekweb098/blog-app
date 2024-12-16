<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
  
        Mail::to($user->email)->send(new RegisterMail($user));
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $login = $request->only('email', 'password');
        if(!$token = JWTAuth::attempt($login)) {
            return response()->json(['xat' => 'xatolik'], 401);
        }
  $user = auth('api')->user();

  if($user->role === 'admin') {
    return response()->json([
        'message' => 'Salom Admin',
        'token' => $token,
        'role' => 'admin',
        'dashboard' => 'admin/panel',
    ]);
  } elseif ($user->role === 'user') {
    return response()->json([
        'message' => 'Salom User',
        'token' => $token,
        'role' => 'user',
        'dashboard' => 'user/panel',
    ]);
  }


        return response()->json([
            'token' => $token,
        ]);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }


}
