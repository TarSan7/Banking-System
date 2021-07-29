<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_from',
        'card_to',
        'date',
        'sum',
        'currency',
        'comment',
    ];
}