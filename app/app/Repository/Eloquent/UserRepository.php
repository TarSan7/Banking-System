<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Models\UserCard;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * All users
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Getting all user cards
     * @param integer $userId
     * @return Collection
     */
    public function getCards($userId): Collection
    {
        return UserCard::select('card_id')->where('user_id', $userId)->get();
    }

    /**
     * Getting all user's names
     * @return String
     */
    public function getUsername($email): String
    {
        return $this->model->where('email', $email)->first()->name;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function userExists($email): bool
    {
        return $this->model->where('email', $email)->exists();
    }
}
