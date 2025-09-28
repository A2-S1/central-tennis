<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranking_group_id','name','notes','is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(RankingGroup::class, 'ranking_group_id');
    }

    public function entries()
    {
        return $this->hasMany(RankingEntry::class);
    }
}
