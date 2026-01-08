<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            return back()->with('success', 'Login successfuly');

        }

        return back()->with('error', 'Login fail!');

    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validatedData);
        return back()->with('success', 'Register berhasil');

    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('home')->with('success', 'Logout berhasil');

    }

}

