<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface ActiveDepositRepositoryInterface
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
     * @param int $userId
     * @return Collection
     */
    public function userDeposits($userId): Collection;

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;
}
