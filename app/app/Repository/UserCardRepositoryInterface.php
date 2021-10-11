<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface UserCardRepositoryInterface
{
    /**
     * Getting all user cards
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param integer $id
     * @return bool
     */
    public function cardsExist($id): bool;

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

    /**
     * Deleting user card
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public function delete($userId, $cardId): bool;
}
