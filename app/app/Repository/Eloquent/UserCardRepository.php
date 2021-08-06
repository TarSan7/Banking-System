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
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param integer $id
     * @return bool
     */
    public function cards($id): bool
    {
        return $this->model->where('card_id', $id)->exists();
    }

    /**
     * @return Collection|null
     */
    public function cardIdByUser(): ?Collection
    {
        return $this->model->where('user_id', Auth::user()->id)->get('card_id');
    }
}
