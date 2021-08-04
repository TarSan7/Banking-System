<?php
namespace App\Repository;

use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Double;

interface CardRepositoryInterface
{
    public function all(): Collection;

    public function findAll($cardsId): ?Collection;

    public function cardExist($validate): bool;

    public function getId($number): int;

    public function getSumFrom($numberFrom): float;

    public function getSumTo($numberTo): float;

    public function updateFrom($numberFrom, array $attributes);

    public function updateTo($numberTo, array $attributes);

    public function getCurrencyFrom($numberFrom): String;

    public function getCurrencyTo($numberTo): String;

    public function getGeneralCardNum($numberFrom): String;
}
