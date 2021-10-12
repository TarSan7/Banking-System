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
     * Getting loans by current dates
     * @return object
     */
    public function getLoansByDate(): array;

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Getting user loans
     * @param int $userId
     * @return Collection
     */
    public function userLoans($userId): Collection;

    /**
     * Getting id's
     * @return object
     */
    public function getIds(): object;

    /**
     * Updating dates of loans
     * @param $id
     * @param $newDate
     * @return bool
     */
    public function updateDate($id, $newDate): bool;
}
