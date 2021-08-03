<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Http\Requests\TransferToCardRequest;
use App\Http\Requests\TransferToPhoneRequest;
use App\Models\UserCard;
use App\Models\Card;
use App\Models\CardTransfer;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function addCard(AddCardRequest $request)
    {
        $validate = $request->validated();

        if (!Card::where('number', $validate['number'])->where('cvv', $validate['cvv'])
            ->where('expires_end', $validate['expires-end'])->exists()) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card doesn`t exist! Try again!'
            ]);
        }
        if (UserCard::cardsNumber($validate['number'])) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card has already used!'
            ]);
        }
        $uCard = UserCard::create(['user_id' => Auth::user()->id,
                 'card_id' => UserCard::cardsId($validate['number'])]);
        if ($uCard) {
            return redirect(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
            'formError' => 'An error occurred while saving data!'
        ]);
    }

    public function cardInfo($cardId)
    {
        $card = Card::find($cardId);
        return view('oneCard', ['card' => $card,
            'transactions' => CardTransfer::select('*')->where('card_from', $card['number'])
            ->orWhere('card_to', $card['number'])->orderByDesc('date')->get()]);
    }
}
