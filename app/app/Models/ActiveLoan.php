<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveLoan extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'loan_id',
        'sum',
        'total_sum',
        'month_pay',
        'month_left',
        'card_id',
        'user_id'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function card()
    {
        return $this->hasOne(Card::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loan()
    {
        return $this->hasMany(Loan::class);
    }
}
