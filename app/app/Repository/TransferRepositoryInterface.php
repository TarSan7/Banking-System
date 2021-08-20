<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface TransferRepositoryInterface
{
    /**
     * @param integer $number
     * @return ?Collection
     */
    public function getCardTransactions($number): ?Collection;
}
