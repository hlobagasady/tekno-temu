<?php
namespace App\Http\Controllers;
use App\Models\AdminUser; use Illuminate\Http\Request; use Illuminate\Support\Facades\Hash; use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller {
 public function login(Request $request) { $data=$request->validate(['email'=>'required|email','password'=>'required|string']); $user=AdminUser::where('email',$data['email'])->first(); if(!$user || !Hash::check($data['password'],$user->password)) return response()->json(['message'=>'Kredensial salah.'],401); return response()->json(['token'=>JWTAuth::fromUser($user),'token_type'=>'bearer','expires_in'=>config('jwt.ttl')*60]); }
 public function me() { return response()->json(auth()->user()); }
 public function logout() { JWTAuth::invalidate(JWTAuth::getToken()); return response()->json(['message'=>'Logout berhasil.']); }
}
