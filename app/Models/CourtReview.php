<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'tennis_court_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function court(): BelongsTo
    {
        return $this->belongsTo(TennisCourt::class, 'tennis_court_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
