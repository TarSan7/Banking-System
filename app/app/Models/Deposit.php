<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'early_percent',
        'intime_percent',
        'min_duration',
        'max_duration',
        'max_sum'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activedeposit()
    {
        return $this->hasMany(ActiveDeposit::class);
    }
}
