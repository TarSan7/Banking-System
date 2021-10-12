<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_id',
        'sum',
        'total_sum',
        'currency',
        'month_pay',
        'month_left',
        'duration',
        'early_percent',
        'intime_percent',
        'card_id',
        'user_id',
        'date'
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
    public function deposite()
    {
        return $this->hasMany(Loan::class);
    }
}
