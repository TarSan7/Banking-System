<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Models\UserCard;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
     * @param $userId
     * @return Collection
     */
    public function getCards($userId): Collection
    {
        return UserCard::select('card_id')->where('user_id', $userId)->get();
    }

    /**
     * @return String
     */
    public function getUsername(): String
    {
        return $this->model->where('email', Auth::user()->email)->get('name')[0]['name'];
    }
}
