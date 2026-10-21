<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthApiController extends Controller {
    public function login(Request $request) {
        $data = $request->validate(['email'=>'required|email','password'=>'required|string']);
        $user = User::where('email',$data['email'])->first();
        if (!$user || !Hash::check($data['password'],$user->password)) return response()->json(['message'=>'Email atau kata sandi tidak cocok.'],422);
        return response()->json(['token'=>$user->createToken('motorcare-api')->plainTextToken,'user'=>$user->only(['id','name','email','role'])]);
    }
    public function logout(Request $request) { $request->user()->currentAccessToken()?->delete(); return response()->json(['message'=>'Logout berhasil.']); }
}
