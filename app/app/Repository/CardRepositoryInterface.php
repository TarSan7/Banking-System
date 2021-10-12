<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CardRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $cardsId
     * @return Collection|null
     */
    public function findAll($cardsId): ?Collection;

    /**
     * @param array $validate
     * @return bool
     */
    public function cardExist($validate): bool;

    /**
     * @param String $number
     * @return int
     */
    public function getId($number): ?int;

    /**
     * @param string $number
     * @return Model|null
     */
    public function getCardByNum($number): ?Model;
    /**
     * @param integer $numberFrom
     * @return float
     */
    public function getSumFrom($numberFrom): float;

    /**
     * @param String $numberTo
     * @return float
     */
    public function getSumTo($numberTo): float;

    /**
     * @param integer $numberFrom
     * @param array $attributes
     * @return mixed
     */
    public function updateFrom($numberFrom, array $attributes);

    /**
     * @param String $numberTo
     * @param array $attributes
     * @return mixed
     */
    public function updateTo($numberTo, array $attributes);

    /**
     * @param integer $numberFrom
     * @return String
     */
    public function getCurrencyFrom($numberFrom): String;

    /**
     * @param String $numberTo
     * @return String
     */
    public function getCurrencyTo($numberTo): String;

    /**
     * @param integer $numberFrom
     * @return String
     */
    public function getGeneralCardNum($numberFrom): String;

    /**
     * @param float $sum
     * @param string $currency
     * @return bool
     */
    public function checkGeneralSum($sum, $currency): bool;

    /**
     * @param integer $id
     * @param float $sum
     * @return bool
     */
    public function updateSum($id, $sum): bool;

    /**
     * @param int $id
     * @return string
     */
    public function getNumber($id): string;

    /**
     * @param array $userCards
     * @param int $loanId
     * @return Model|null
     */
    public function credit($userCards, $loanId): ?Model;

    /**
     * @param string $currency
     * @param array $toUpdate
     */
    public function updateGeneral($currency, $toUpdate): void;

    /**
     * @param string $currency
     * @return int
     */
    public function generalSumByCurrency($currency): int;

    /**
     * @return Collection
     */
    public function getGeneral(): Collection;
}
