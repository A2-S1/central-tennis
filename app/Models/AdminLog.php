<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = ['action','target_id','target_type','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public static function log(string $action, int $targetId, string $targetType, array $meta = []): void
    {
        static::create([
            'action' => $action,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'meta' => $meta,
        ]);
    }
}
