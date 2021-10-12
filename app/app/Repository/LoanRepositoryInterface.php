<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Integer;

interface LoanRepositoryInterface
{
    /**
     * Getting all loans
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all existing loan types
     * @return ?Model
     */
    public function getLoan($id): ?Model;

    /**
     * @param integer $id
     * @return string
     */
    public function getCurrency($id): String;

    /**
     * @param int $id
     * @param float $sum
     * @param int $card_id
     * @param int $user_id
     * @return bool
     */
    public function newLoan($id, $sum, $card_id, $user_id): bool;
}
