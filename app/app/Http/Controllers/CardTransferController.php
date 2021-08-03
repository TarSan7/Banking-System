<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToCardRequest;
use App\Models\Card;
use App\Models\CardTransfer;
use Illuminate\Http\Request;

class CardTransferController extends Controller
{
    public function transferToCard(TransferToCardRequest $request)
    {
        $validate = $request->validated();
        if ($validate['sum'] > Card::where('id', $validate['numberFrom'])->
            get('sum')[0]['sum']) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }
        if (Card::where('number', $validate['numberTo'])->get('currency')[0]['currency'] !=
            Card::where('id', $validate['numberFrom'])->get('currency')[0]['currency']) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Different currencies! Try another card.'
            ]);
        }

        $sumFrom = Card::where('id', $validate['numberFrom'])->get('sum')[0]['sum'];
        $sumTo = Card::where('number', $validate['numberTo'])->get('sum')[0]['sum'];

        $tranFrom = CardTransfer::create([
            'card_from' => Card::find($validate['numberFrom'])['number'],
            'card_to' => $validate['numberTo'],
            'date' => date('Y-m-d H:i:s'),
            'sum' => $validate['sum'],
            'new_sum' => $sumFrom - $validate['sum'],
            'currency' => Card::where('id', $validate['numberFrom'])->get('currency')[0]['currency'],
            'comment' => $validate['comment']
        ]);

        Card::where('id', $validate['numberFrom'])->update(['sum' => $sumFrom - $validate['sum']]);
        Card::where('number', $validate['numberTo'])->update(['sum' => $sumTo + $validate['sum']]);

        if ($tranFrom) {
            return redirect(route('user.cardTransfer'))->with('success', 'Done!');
        }

        return redirect(route('user.cardTransfer'))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }
}
