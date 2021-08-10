<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Integer;

interface LoanRepositoryInterface
{
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

    public function newLoan($id, $sum, $card_id): bool;
}
