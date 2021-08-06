<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToCardRequest;
use App\Repository\Eloquent\TransferRepository;
use App\Services\CardService;
use App\Services\TransferService;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class CardTransferController
 * @package App\Http\Controllers
 */
class CardTransferController extends Controller
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
     * @return Application|Factory|View
     */
    public function goTransfer()
    {
        return view('cardTransfer', ['cards' => $this->cardService->getUserCards()]);
    }

    /**
     * @param TransferToCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function transferToCard(TransferToCardRequest $request)
    {
        $this->transferService->setInfo($request->validated());

        if ($this->transferService->transferSum() > $this->transferService->getBalanceFrom()) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }

        if (!$this->transferService->compareCurrency()) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Different currencies! Try another card.'
            ]);
        }

        if ($this->transferService->createTransfer()) {
            $this->transferService->updateCards();
            return redirect(route('user.cardTransfer'))->with('success', 'Done!');
        }

        return redirect(route('user.cardTransfer'))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }
}
