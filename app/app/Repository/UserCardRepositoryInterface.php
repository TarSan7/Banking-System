<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface UserCardRepositoryInterface
{
    public function cards($id): bool;

    public function cardIdByUser(): ?Collection;
}
