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
use Illuminate\Support\Arr;

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
    public function index($lang, $id)
    {
        return view('otherTransfer', [app()->getLocale(), 'cards' => $this->cardService->getUserCards(), 'id' => $id]);
    }

    /**
     * @param TransferToPhoneRequest $request
     * @param string $id
     * @return Application|RedirectResponse|Redirector
     */
    public function make(TransferToPhoneRequest $request, $lang, $id)
    {
        var_dump($id);
        $this->transferService->setInfo($request->validated(), $id);
        $response = $this->transferService->otherCheck($id);
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.otherTransfer', [app()->getLocale(), 'id' => $id]))->with('success', $response[1]);
        } else {
            return redirect(route('user.otherTransfer', [app()->getLocale(), 'id' => $id]))->withErrors(['error' => $response[1]]);
        }
    }
}
