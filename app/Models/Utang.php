<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Utang extends Model
{
    protected $table = 'utang';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'person_name',
        'type',
        'amount',
        'due_date',
        'is_paid',
        'note',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}