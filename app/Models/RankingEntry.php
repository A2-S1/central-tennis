<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranking_table_id','position','player_name','club','state','points','matches','obs'
    ];

    public function table()
    {
        return $this->belongsTo(RankingTable::class, 'ranking_table_id');
    }
}
