<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showregister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:user,email'],
                'password' => ['required', 'man:8', 'confirmed'],
            ]
        );

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();
    }

    public function showlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $userdata = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]
        );
        
        if(Auth::attempt($userdata, $request->boolean('remember')))  // can only be ($userdata) or $(userdata, false) , the boolean just remembers or justify boolean of $userdata
        {  
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()
        ->withErrors('passwords', 'Invalid email or password')  //can be any 'email' or 'password', just selected field
        ->onlyinput('email')
        ;

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
