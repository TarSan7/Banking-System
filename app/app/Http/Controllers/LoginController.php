<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {
        if(Auth::check()) {
            return redirect(route('user.private'));
        }

        $data = $request->only(['email', 'password']);

        if(Auth::attempt($data)) {
            return redirect()->intended(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
            'email' => 'Login failed!'
        ]);
    }
}
