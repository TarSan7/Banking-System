<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class RegisterController extends Controller
{

    public function saveUser(RegisterUserRequest $request) {
        if(Auth::check()) {
            return redirect(route('user.private'));
        }

        $validate = $request->validated();

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
