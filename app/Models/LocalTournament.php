<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocalTournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','start_date','end_date','city','state','venue','description',
        'photo_path','bracket_path','registration_fee','registration_is_free','ticket_price','ticket_is_free'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_is_free' => 'boolean',
        'ticket_is_free' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
