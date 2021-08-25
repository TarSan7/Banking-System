<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'number',
        'cvv',
        'expires_end',
        'sum',
        'currency'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usercard()
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activeloan()
    {
        return $this->belongsTo(ActiveLoan::class);
    }
}
