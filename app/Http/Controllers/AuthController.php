<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success','Logged In Successfully!');
        }

        return back()
            ->with(
                'error' , 'Invalid Credentials',
            )
            ->onlyInput('email');
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username'=>'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $username = Str::remove('',$validated['username']);
        try{

            User::create([
                'name' => $validated['name'],
                'username'=>$username,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                ]);
            
            return redirect()->route('login')->with('success','User Registered Successfully!');
        }catch(\Exception $e){
            return back()->with('error','Unable to register user. Please try again.');
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success','Logged Out Successfully!');
    }
}