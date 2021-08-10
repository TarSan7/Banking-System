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
 * Class OtherTransferController
 * @package App\Http\Controllers
 */
class OtherTransferController extends Controller
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
    public function index($id)
    {
        return view('otherTransfer', ['cards' => $this->cardService->getUserCards(), 'id' => $id]);
    }

    /**
     * @param TransferToPhoneRequest $request
     * @param string $id
     * @return Application|RedirectResponse|Redirector
     */
    public function make(TransferToPhoneRequest $request, $id)
    {
        $this->transferService->setInfo($request->validated(), $id);
        $response = $this->transferService->otherCheck($id);
        if ($response[0] === 'success') {
            return redirect(route('user.otherTransfer', ['id' => $id]))->with('success', $response[1]);
        } else {
            return redirect(route('user.otherTransfer', ['id' => $id]))->withErrors(['error' => $response[1]]);
        }
    }
}
