<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterController
 * @package App\Http\Controllers
 */
class RegisterController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param RegisterUserRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function saveUser(RegisterUserRequest $request)
    {
        if (Auth::check()) {
            return redirect(route('user.private'));
        }
        $validate = $request->validated();
        $user = $this->userRepository->create($validate);
        if ($user) {
            Auth::login($user);
            return redirect(route('user.private',
                app()->getLocale()));
        }

        return redirect(route('user.login', app()->getLocale()))->withErrors([
           'formError' => 'An error occurred while saving data!'
        ]);
    }
}
