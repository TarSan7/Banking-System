<?php

namespace App\Repository\Eloquent;

use App\Models\Card;
use App\Repository\CardRepositoryInterface;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\Double;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param Card $model
     */
    public function __construct(Card $model)
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
     * @param $cardsId
     * @return Collection
     */
    public function findAll($cardsId): ?Collection
    {
        return $this->model->select('*')->whereIn('id', $cardsId)->get();
    }

    /**
     * @param $validate
     * @return bool
     */
    public function cardExist($validate): bool
    {
        return $this->model->where('number', $validate['number'])->where('cvv', $validate['cvv'])
            ->where('expires_end', $validate['expires-end'])->exists();
    }

    /**
     * @param $number
     * @return int
     */
    public function getId($number): int
    {
        return $this->model->where('number', $number)->get('id')[0]['id'];
    }

    /**
     * @param $numberFrom
     * @return float
     */
    public function getSumFrom($numberFrom): float
    {
        return $this->model->where('id', $numberFrom)->get('sum')[0]['sum'];
    }

    /**
     * @param $numberTo
     * @return float
     */
    public function getSumTo($numberTo): float
    {
        return $this->model->where('number', $numberTo)->get('sum')[0]['sum'];
    }

    /**
     * @param $numberFrom
     * @param array $attributes
     */
    public function updateFrom($numberFrom, array $attributes)
    {
        $this->model->where('id', $numberFrom)->update($attributes);
    }

    /**
     * @param $numberTo
     * @param array $attributes
     */
    public function updateTo($numberTo, array $attributes)
    {
        $this->model->where('number', $numberTo)->update($attributes);
    }

    /**
     * @param $numberFrom
     * @return String
     */
    public function getCurrencyFrom($numberFrom): String
    {
        return $this->model->where('id', $numberFrom)->get('currency')[0]['currency'];
    }

    /**
     * @param $numberTo
     * @return String
     */
    public function getCurrencyTo($numberTo): String
    {
        return $this->model->where('number', $numberTo)->get('currency')[0]['currency'];
    }

    /**
     * @param $numberFrom
     * @return String
     */
    public function getGeneralCardNum($numberFrom): String
    {
        return $this->model->where('type', 'general')->where('currency',
            $this->getCurrencyFrom($numberFrom))->get('number')[0]['number'];
    }
}
