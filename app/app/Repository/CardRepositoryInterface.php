<?php
namespace App\Repository;

use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Double;

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
    public function getId($number): int;

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
}
