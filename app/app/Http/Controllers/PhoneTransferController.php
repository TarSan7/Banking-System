<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToPhoneRequest;
use App\Services\CardService;
use App\Services\TransferService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

/**
 * Class PhoneTransferController
 * @package App\Http\Controllers
 */
class PhoneTransferController extends Controller
{
    /**
     * @var TransferService
     * @var CardService
     */
    private $transferService, $cardService;

    /**
     * @param TransferService $transferService
     * @param CardService $cardService
     */
    public function __construct(TransferService $transferService, CardService $cardService)
    {
        $this->transferService = $transferService;
        $this->cardService = $cardService;
    }

    /**
     * @param string $id
     * @return Application|Factory|View
     */
    public function goTransfer($id)
    {
        return view('phoneTransfer', ['cards' => $this->cardService->getUserCards(), 'id' => $id]);
    }

    /**
     * @param TransferToPhoneRequest $request
     * @param string $id
     * @return Application|RedirectResponse|Redirector
     */
    public function transferToPhone(TransferToPhoneRequest $request, $id)
    {
        $this->transferService->setInfo($request->validated(), $id);

        if ($id == 'phone' && !$this->transferService->checkPhoneNumber()) {
            return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
                'numberTo' => 'Incorrect number format!'
            ]);
        }

        if ($this->transferService->transferSum() > $this->transferService->getBalanceFrom()) {
            return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }

        if ($this->transferService->createTransfer()) {
            $this->transferService->updateCards();
            return redirect(route('user.phoneTransfer', ['id'=>$id]))->with('success', 'Done!');
        }

        return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }
}
