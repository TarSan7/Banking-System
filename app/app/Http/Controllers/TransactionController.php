<?php

namespace App\Http\Controllers;

use App\Services\TransferService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TransactionController extends Controller
{
    /**
     * @var TransferService
     */
    private $transferService;

    /**
     * @param TransferService $transferService
     */
    public function __construct(
        TransferService $transferService
    ){
        $this->transferService = $transferService;
    }

    /**
     * @return Application|Factory|View
     */
    public function all()
    {
        return view('allTransfers', [
            'transactions' => $this->transferService->getTransactions()
        ]);
    }
}
