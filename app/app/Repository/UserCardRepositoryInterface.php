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
    public function cardIdByUser($id): ?Collection;

    /**
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public function createNew($userId, $cardId): bool;
}
