<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'card_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public static function cardsNumber($number){
        return UserCard::where('card_id', self::cardsId($number))->exists();
    }

    public static function cardsId($number){
        return Card::where('number', $number)->get('id')[0]['id'];
    }
}
