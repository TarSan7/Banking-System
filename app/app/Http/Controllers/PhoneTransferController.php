<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToPhoneRequest;
use App\Models\Card;
use App\Models\CardTransfer;
use Illuminate\Http\Request;

class PhoneTransferController extends Controller
{
    public function transferToPhone(TransferToPhoneRequest $request, $id)
    {
        $validate = $request->validated();
        if ($id == 'phone' && !preg_match('/^(((\+380)[0-9]{9})?|([A-Za-z]{0,}))$/', $validate['numberTo'])) {
            return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
                'numberTo' => 'Incorrect number format!'
            ]);
        }

        if ($validate['sum'] > Card::where('id', $validate['numberFrom'])->
            get('sum')[0]['sum']) {
            return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }

        $sumFrom = Card::where('id', $validate['numberFrom'])->get('sum')[0]['sum'];
        $numberTo = Card::where('type', 'general')->where('currency',
            Card::where('id', $validate['numberFrom'])->get('currency')[0]['currency'])->get('number')[0]['number'];

        $tranFrom = CardTransfer::create([
            'card_from' => Card::find($validate['numberFrom'])['number'],
            'card_to' => $numberTo,
            'date' => date('Y-m-d H:i:s'),
            'sum' => $validate['sum'],
            'new_sum' => $sumFrom - $validate['sum'],
            'currency' => Card::where('id', $validate['numberFrom'])->get('currency')[0]['currency'],
            'comment' => $validate['comment']."To: ".$validate['numberTo']
        ]);

        Card::where('id', $validate['numberFrom'])->update(['sum' => $sumFrom - $validate['sum']]);
        Card::where('number', $numberTo)->update(['sum' => Card::where('number', $numberTo)->get('sum')[0]['sum'] +
            $validate['sum']]);

        if ($tranFrom) {
            return redirect(route('user.phoneTransfer', ['id'=>$id]))->with('success', 'Done!');
        }

        return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }
}
