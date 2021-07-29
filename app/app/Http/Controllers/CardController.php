<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Http\Requests\TransferToCardRequest;
use App\Models\UserCard;
use App\Models\Card;
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
        return view('oneCard', ['card' => Card::find($cardId)]);
    }

    public function transferToCard(TransferToCardRequest $request)
    {
        $validate = $request->validated();
        if ($validate['sum'] > Card::where('id', $validate['numberFrom'])->
            get('sum')[0]['sum']) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }

        $tran = CardTransfer::create([
            'card_from' => Card::find($validate['numberFrom'])['number'],
            'card_to' => $validate['numberTo'],
            'date' => date('Y-m-d H:i:s'),
            'sum' => $validate['sum'],
            'currency' => Card::where('id', $validate['numberFrom'])->get('currency')[0]['currency'],
            'comment' => $validate['comment']
        ]);

        $newSum = Card::where('id', $validate['numberFrom'])->get('sum')[0]['sum'] - $validate['sum'];

        Card::where('id', $validate['numberFrom'])->update(['sum' => $newSum]);

        if ($tran) {
            return redirect(route('user.cardTransfer'))->with('success', 'Done!');
        }

        return redirect(route('user.cardTransfer'))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }

}
