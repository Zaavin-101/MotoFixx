<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
 public function register(Request $r){$d=$r->validate(['name'=>'required|string|max:100','email'=>'required|email|unique:users,email','password'=>'required|string|min:8|confirmed']);$u=User::create($d);$token=$u->createToken('motocare')->plainTextToken;return response()->json(['user'=>$u,'token'=>$token],201);}
 public function login(Request $r){$d=$r->validate(['email'=>'required|email','password'=>'required']);$u=User::where('email',$d['email'])->first();if(!$u||!Hash::check($d['password'],$u->password))return response()->json(['message'=>'Email atau password salah'],422);$u->tokens()->delete();return response()->json(['user'=>$u,'token'=>$u->createToken('motocare')->plainTextToken]);}
 public function user(Request $r){return response()->json($r->user());}
 public function logout(Request $r){$r->user()->currentAccessToken()?->delete();return response()->json(['message'=>'Logout berhasil']);}
}
