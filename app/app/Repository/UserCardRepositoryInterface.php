<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface UserCardRepositoryInterface
{
    /**
     * @param integer $id
     * @return bool
     */
    public function cards($id): bool;

    /**
     * @return Collection|null
     */
    public function cardIdByUser(): ?Collection;
}
