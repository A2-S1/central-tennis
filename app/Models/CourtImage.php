<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tennis_court_id',
        'path',
        'caption',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function court(): BelongsTo
    {
        return $this->belongsTo(TennisCourt::class, 'tennis_court_id');
    }
}
