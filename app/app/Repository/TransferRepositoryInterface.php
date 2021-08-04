<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface TransferRepositoryInterface
{
    public function getCardTransactions($number): Collection;
}
