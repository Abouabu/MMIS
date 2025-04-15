<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string', 
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('name', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('patients.index'));
        }
    
        return back()->withErrors(['credentials' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard');
    }
}
