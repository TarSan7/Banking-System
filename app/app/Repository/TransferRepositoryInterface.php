<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface TransferRepositoryInterface
{
    /**
     * Getting all transfers
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param integer $number
     * @return ?Collection
     */
    public function getCardTransactions($number): ?Collection;

    /**
     * Getting all user cards transactions
     * @param string $number
     * @return Collection
     */
    public function allTransactions($numbers): Collection;
}
