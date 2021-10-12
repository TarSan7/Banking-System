<?php

namespace App\Repository\Eloquent;

use App\Models\UserCard;
use App\Repository\UserCardRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserCardRepository extends BaseRepository implements UserCardRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param UserCard $model
     */
    public function __construct(UserCard $model)
    {
        parent::__construct($model);
    }

    /**
     * Getting all user cards
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Checking cards for existing
     * @param integer $id
     * @return bool
     */
    public function cardsExist($id): bool
    {
        return $this->model->where('card_id', $id)->exists();
    }

    /**
     * Getting card ids by user id
     * @param int $id
     * @return Collection|null
     */
    public function cardIdByUser($id): ?Collection
    {
        $rez =  $this->model->where('user_id', $id)->get('card_id');
        return $rez;
    }

    /**
     * Creating new user card
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public function createNew($userId, $cardId): bool
    {
        return (bool) $this->model->insert([
            'user_id' => $userId,
            'card_id' => $cardId
        ]) ?? false;
    }

    /**
     * Deleting user card
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public function delete($userId, $cardId): bool
    {
        return (bool) $this->model->where('card_id', $cardId)->where('user_id', $userId)->delete();
    }
}
