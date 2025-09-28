<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','period_start','period_end','category','gender','badge_url','is_public'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'is_public' => 'boolean',
    ];

    public function tables()
    {
        return $this->hasMany(RankingTable::class);
    }
}
