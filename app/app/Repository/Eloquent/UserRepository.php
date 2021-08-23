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
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param integer $userId
     * @return Collection
     */
    public function getCards($userId): Collection
    {
        return UserCard::select('card_id')->where('user_id', $userId)->get();
    }

    /**
     * @return String
     */
    public function getUsername($email): String
    {
        return $this->model->where('email', $email)->first()->name;
    }
}
