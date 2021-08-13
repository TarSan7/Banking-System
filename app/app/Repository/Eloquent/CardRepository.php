<?php

namespace App\Repository\Eloquent;

use App\Models\Card;
use App\Repository\CardRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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
     * @param array $cardsId
     * @return Collection|null
     */
    public function findAll($cardsId): ?Collection
    {
        return $this->model->select('*')->whereIn('id', $cardsId)->get();
    }

    /**
     * @param array $validate
     * @return bool
     */
    public function cardExist($validate): bool
    {
        return $this->model->where('number', Arr::get($validate, 'number', null))
            ->where('cvv', Arr::get($validate, 'cvv', null))
            ->where('expires_end', Arr::get($validate, 'expires-end', null))->exists();
    }

    /**
     * @param String $number
     * @return int
     */
    public function getId($number): int
    {
        return $this->model->where('number', $number)->get('id')[0]['id'];
    }

    /**
     * @param String $number
     * @return Model
     */
    public function getCardByNum($number): ?Model
    {
        return $this->model->where('number', $number)->get('*')[0];
    }

    /**
     * @param int $numberFrom
     * @return float
     */
    public function getSumFrom($numberFrom): float
    {
        return $this->model->where('id', $numberFrom)->get('sum')[0]['sum'];
    }

    /**
     * @param String $numberTo
     * @return float
     */
    public function getSumTo($numberTo): float
    {
        return $this->model->where('number', $numberTo)->get('sum')[0]['sum'];
    }

    /**
     * @param int $numberFrom
     * @param array $attributes
     */
    public function updateFrom($numberFrom, array $attributes)
    {
        $this->model->where('id', $numberFrom)->update($attributes);
    }

    /**
     * @param String $numberTo
     * @param array $attributes
     */
    public function updateTo($numberTo, array $attributes)
    {
        $this->model->where('number', $numberTo)->update($attributes);
    }

    /**
     * @param int $numberFrom
     * @return String
     */
    public function getCurrencyFrom($numberFrom): String
    {
        return $this->model->where('id', $numberFrom)->get('currency')[0]['currency'];
    }

    /**
     * @param String $numberTo
     * @return String
     */
    public function getCurrencyTo($numberTo): String
    {
        return $this->model->where('number', $numberTo)->get('currency')[0]['currency'];
    }

    /**
     * @param int $numberFrom
     * @return String
     */
    public function getGeneralCardNum($numberFrom): String
    {
        return $this->model->where('type', 'general')->where('currency',
            $this->getCurrencyFrom($numberFrom))->get('number')[0]['number'];
    }

    /**
     * @param int $id
     * @param float $sum
     * @return bool
     */
    public function updateSum($id, $sum): bool
    {
        $updated = $this->model->find($id)['sum'] - $sum;
        return (bool) $this->model->find($id)->update(['sum' => $updated]);
    }
}
