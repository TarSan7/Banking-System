<?php
namespace App\Repository;

use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param integer $userId
     * @return Collection
     */
    public function getCards($userId): Collection;

    /**
     * @return String
     */
    public function getUsername($email): String;
}
