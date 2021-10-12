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
     * @param int $deposit_id
     */
    public function getMoney($deposit_id): void;

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Getting deposits by current date
     * @return object
     */
    public function getDepositsByDate(): array;

    /**
     * Getting number of month left
     * @param int $depositId
     * @return array|\ArrayAccess|mixed
     */
    public function getMonthsLeft($depositId);

    /**
     * Getting month payment
     * @param int $depositId
     * @return array|\ArrayAccess|mixed
     */
    public function getMonthSum($depositId);

    /**
     * Getting id's of deposits
     * @return object
     */
    public function getIds(): object;

    /**
     * Update dates for active deposits
     * @param int $id
     * @param string $newDate
     */
    public function updateDate($id, $newDate): void;
}
