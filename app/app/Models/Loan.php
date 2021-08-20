<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'percent',
        'duration',
        'max_sum',
        'currency'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeloan()
    {
        return $this->hasMany(ActiveLoan::class);
    }
}