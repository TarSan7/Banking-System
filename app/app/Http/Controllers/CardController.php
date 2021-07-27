<?php

namespace App\Http\Controllers;

use App\Models\UserCard;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function addCard(Request $request)
    {
        $validate = $request->validate([
            'number' => 'required|regex:/^[0-9]{16}$/',
            'cvv' => 'required|regex:/^[0-9a-zA-Z]{3}$/',
            'expires-end' => 'required'
        ]);

        if(!Card::where('number', $validate['number'])->where('cvv', $validate['cvv'])
            ->where('expires_end', $validate['expires-end'])->exists()) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card doesn`t exist! Try again!'
            ]);
        }
        if(UserCard::cardsNumber($validate['number'])) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card has already used!'
            ]);
        }
        $uCard = UserCard::create(['user_id' => Auth::user()->id, 'card_id' => UserCard::cardsId($validate['number'])]);
        if($uCard) {
            return redirect(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
            'formError' => 'An error occurred while saving data!'
        ]);
    }

    public function cardInfo($cardId)
    {

    }
}
