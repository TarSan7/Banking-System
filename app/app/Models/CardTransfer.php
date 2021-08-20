<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransfer extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'card_from',
        'card_to',
        'date',
        'sum',
        'new_sum',
        'currency',
        'comment',
        'user_id'
    ];

    public function card()
    {

    }
}
