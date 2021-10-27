<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Repository\Eloquent\CardRepository;
use App\Services\CardService;
use App\Services\TransferService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
{
    /**
     * @var TransferService
     * @var CardService
     */
    private $transferService, $cardService, $cardRepository;

    /**
     * @param TransferService $transferService
     * @param CardService $cardService
     */
    public function __construct(TransferService $transferService, CardService $cardService, CardRepository $cardRepository)
    {
        $this->transferService = $transferService;
        $this->cardService = $cardService;
        $this->cardRepository = $cardRepository;
    }

    /**
     * @param AddCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function add(AddCardRequest $request)
    {
        $this->cardService->setCard($request->validated());
        $response = $this->cardService->check();
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.addCard', app()->getLocale()))->with('success', Arr::get($response, 1, null));
        } else {
            return redirect(route('user.addCard', app()->getLocale()))->withErrors(['error' => Arr::get($response, 1, null)]);
        }
    }

    /**
     * @param int $cardId
     * @return Application|Factory|View
     */
    public function info($lang, $cardId)
    {
        $transactions = $this->transferService->getCardTransfers($cardId);
        $path = $this->cardRepository->getImage($cardId);
        return view('oneCard', [
            app()->getLocale(),
            'card' => $this->cardService->getCardById($cardId),
            'path' => $path,
            'transactions' => $transactions
        ]);
    }

    public function change(Request $request, $lang, $id)
    {
        if ($request->file('AddImage')) {
            $file = $request->file('AddImage');
            $is = Storage::disk('dropbox')->putFile('', $file);
            if (Storage::disk('dropbox')->exists($is)) {
               $path = Storage::disk('dropbox')->url($is);
               $this->cardRepository->setImage($path, $id);
               echo '<script>window.close()</script>';
            }
        } else {
            $this->cardRepository->setInitial($id);
            echo '<script>window.close()</script>';
        }

    }
}
