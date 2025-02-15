<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Display homepage
    public function showLoginForm(){
         return view('login');
    }

    //  login Process
    public function login(Request $request) {
         $credentials = $request->validate([
              'email' => 'required|email',
              'password' => 'required',
         ]);

         if (Auth::attempt($credentials)) {
              $request->session()->regenerate();
              return redirect()->intended('chat');
         }

         return back()->withErrors([
              'email' => 'The provided credentials do not match our records.',
         ]);
    }

    // Display registration form
    public function showRegisterForm(){
         return view('register');
    }

    // Process registration

public function register(Request $request){
    $data = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|email|unique:users,email',
         'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
         'name'     => $data['name'],
         'email'    => $data['email'],
         'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);
    return redirect('chat');
}


    // Logout the user
    public function logout(Request $request){
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
         return redirect('/');
    }
}
