<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

interface DepositRepositoryInterface
{
    /**
     * Get all existing deposit types
     * @return ?Model
     */
    public function getDeposit($id): ?Model;

//    /**
//     * @param integer $id
//     * @return string
//     */
//    public function getCurrency($id): String;

}
