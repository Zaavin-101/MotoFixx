<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>'required|email','password'=>'required|string']);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email'=>'Email atau kata sandi tidak cocok.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('checkout'))->with('success','Berhasil masuk.');
    }
    public function register(Request $request) {
        $data = $request->validate(['name'=>'required|string|max:100','email'=>'required|email|max:150|unique:users,email','phone'=>'nullable|string|max:30','password'=>'required|string|min:8|confirmed']);
        $user = User::create($data + ['role'=>'customer']);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('checkout'))->with('success','Akun berhasil dibuat.');
    }
    public function logout(Request $request) {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home')->with('success','Kamu sudah keluar.');
    }
}
