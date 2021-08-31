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
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param integer $id
     * @return bool
     */
    public function cards($id): bool
    {
        return (bool) count($this->model->where('card_id', $id)->get());
    }

    /**
     * @param int $id
     * @return Collection|null
     */
    public function cardIdByUser($id): ?Collection
    {
        return $this->model->where('user_id', $id)->get('card_id');
    }

    /**
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
     * Deleting field userCard
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public function delete($userId, $cardId): bool
    {
        return (bool) $this->model->where('card_id', $cardId)->where('user_id', $userId)->delete();
    }
}
