<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class RegisterController extends Controller
{
    public function saveUser(Request $request) {
        if(Auth::check()) {
            return redirect(route('user.private'));
        }

        $validate = $request->validate([
           'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        if(User::where('email', $validate['email'])->exists()) {
            return redirect(route('user.registration'))->withErrors([
                'email' => 'This email has already been used!'
            ]);
        }

        $user = User::create($validate);
        if($user) {
            Auth::login($user);
            return redirect(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
           'formError' => 'An error occurred while saving data!'
        ]);
    }
}
