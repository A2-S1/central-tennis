<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'category', 'position', 'points', 'date', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
