<?php

namespace App\Repository\Eloquent;

use App\Models\Card;
use App\Models\Loan;
use App\Repository\CardRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\Double;
use function PHPUnit\Framework\isEmpty;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{

    /**
     * UserRepository constructor.
     * @param Card $model
     */
    public function __construct(Card $model)
    {
        parent::__construct($model);
    }

    /**
     * Getting all cards
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Finding all cards which have id's from array
     * @param array $cardsId
     * @return Collection|null
     */
    public function findAll($cardsId): ?Collection
    {
        return $this->model->select('*')->whereIn('id', $cardsId)->get();
    }

    /**
     * Checking cards for exist
     * @param array $validate
     * @return bool
     */
    public function cardExist($validate): bool
    {
        $card = $this->model->where('number', Arr::get($validate, 'number', null))
            ->where('cvv', Arr::get($validate, 'cvv', null))
            ->where('expires_end', Arr::get($validate, 'expires-end', null))->exists();
        return $card;
    }

    /**
     * Getting id by number
     * @param String $number
     * @return ?int
     */
    public function getId($number): ?int
    {
        return $this->model->where('number', $number)->first()->id ?? null;
    }

    /**
     * Getting card model by number
     * @param String $number
     * @return Model|null
     */
    public function getCardByNum($number): ?Model
    {
        return $this->model->where('number', $number)->first();
    }

    /**
     * Getting sum of card from which was made transfer
     * @param int $numberFrom
     * @return float
     */
    public function getSumFrom($numberFrom): float
    {
        return $this->model->where('id', $numberFrom)->first()->sum;
    }

    /**
     * Getting sum of card to which was made transfer
     * @param String $numberTo
     * @return float
     */
    public function getSumTo($numberTo): float
    {
        return $this->model->where('number', $numberTo)->first()->sum;
    }

    /**
     * Updating card from which was made transfer
     * @param int $numberFrom
     * @param array $attributes
     */
    public function updateFrom($numberFrom, array $attributes)
    {
        $this->model->where('id', $numberFrom)->update($attributes);
    }

    /**
     * Updating card to which was made transfer
     * @param String $numberTo
     * @param array $attributes
     */
    public function updateTo($numberTo, array $attributes)
    {
        $this->model->where('number', $numberTo)->update($attributes);
    }

    /**
     * Getting currency of card from which was made transfer
     * @param int $numberFrom
     * @return String
     */
    public function getCurrencyFrom($numberFrom): String
    {
        return $this->model->where('id', $numberFrom)->first()->currency;
    }

    /**
     * Getting currency of card to which was made transfer
     * @param String $numberTo
     * @return String
     */
    public function getCurrencyTo($numberTo): String
    {
        return $this->model->where('number', $numberTo)->first()->currency;
    }

    /**
     * Getting general card number by id
     * @param int $numberFrom
     * @return String
     */
    public function getGeneralCardNum($numberFrom): String
    {
        return $this->model->where('type', 'general')->where('currency',
            $this->getCurrencyFrom($numberFrom))->first()->number;
    }

    /**
     * Checking general sum
     * @param float $sum
     * @param string $currency
     * @return bool
     */
    public function checkGeneralSum($sum, $currency): bool
    {
        return $this->model->where('type', 'general')->where('currency', $currency)->first()->sum >= $sum;
    }

    /**
     * Updating sum
     * @param int $id
     * @param float $sum
     * @return bool
     */
    public function updateSum($id, $sum = 0): bool
    {
        $cardSum = $this->model->find($id)['sum'];
        if ($cardSum >= $sum) {
            $updated = $cardSum - $sum;
            return (bool)$this->model->find($id)->update(['sum' => $updated]);
        }
        return false;
    }

    /**
     * Getting number of card by id
     * @param int $id
     * @return string
     */
    public function getNumber($id): string
    {
        $num = $this->model->where('id', $id)->get('number');
        return count($num) > 0 ? $num->first()->number : $num;
    }

    /**
     * Getting user credit cards
     * @param array $userCards
     * @param int $loanId
     * @return Model|null
     */
    public function credit($userCards, $loanId): ?Model
    {
        $currency = Loan::find($loanId)->currency;
        $card = $this->model->whereIn('id', $userCards)->where('type', 'credit')
            ->where('currency', $currency)->first();
        return $card;
    }

    /**
     * Updating general cards
     * @param string $currency
     * @param array $toUpdate
     */
    public function updateGeneral($currency, $toUpdate): void
    {
        $this->model->where('type', 'general')->where('currency', $currency)->update($toUpdate);
    }

    /**
     * Getting general sum by currency
     * @param string $currency
     * @return int
     */
    public function generalSumByCurrency($currency): int
    {
        $genSum =  $this->getGeneral()->where('currency', $currency)->first();
        return Arr::get($genSum,'sum', 0);
    }

    /**
     * Getting all general cards
     * @return Collection
     */
    public function getGeneral(): Collection
    {
        return $this->model->where('type', 'general')->get();
    }
}
