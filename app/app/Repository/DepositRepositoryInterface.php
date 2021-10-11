<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface DepositRepositoryInterface
{
    /**
     * Getting all deposits
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all existing deposit types
     * @return ?Model
     */
    public function getDeposit($id): ?Model;

    /**
     * Creating a new deposit
     * @param int $id
     * @param array $deposit
     * @param int $user_id
     * @return bool
     */
    public function newDeposit($id, $deposit, $user_id): bool;
}
