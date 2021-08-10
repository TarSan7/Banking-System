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
     * @param integer $id
     * @return bool
     */
    public function decrease($id): bool;
}
