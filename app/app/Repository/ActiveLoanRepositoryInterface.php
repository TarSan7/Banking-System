<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface ActiveLoanRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @return Collection
     */
    public function getCardsId(): Collection;

    /**
     * @param array
     * @return bool
     */
    public function decrease($cardId): bool;

    /**
     * @param int $cardId
     * @return bool
     */
    public function delete($cardId): bool;
}
