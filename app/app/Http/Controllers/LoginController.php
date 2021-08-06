<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * @var UserRepository
     * @var CardRepository
     * @var UserCardRepository
     */
    private $userRepository, $cardRepository, $userCardRepossitory;

    /**
     * @param UserRepository $userRepository
     * @param CardRepository $cardRepository
     * @param UserCardRepository $userCardRepository
     */
    public function __construct(
        UserRepository $userRepository,
        CardRepository $cardRepository,
        UserCardRepository $userCardRepository
    ) {
        $this->userRepository = $userRepository;
        $this->cardRepository= $cardRepository;
        $this->userCardRepossitory = $userCardRepository;
    }

    /**
     * @param LoginUserRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function login(LoginUserRequest $request)
    {
        if (Auth::check()) {
            return redirect(route('user.private'));
        }

        $data = $request->only(['email', 'password']);

        if (Auth::attempt($data)) {
            return redirect()->intended(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
            'email' => 'Login failed!'
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function toPrivate()
    {
        $cardsId = $this->userCardRepossitory->cardIdByUser();
        return view('private', [
            'username' => $this->userRepository->getUsername(),
            'cards' => $this->cardRepository->findAll($cardsId)
        ]);
    }
}
