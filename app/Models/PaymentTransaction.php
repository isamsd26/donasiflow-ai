<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'donation_id',
        'gateway',
        'external_id',
        'reference',
        'payment_method',
        'gross_amount',
        'fee_amount',
        'net_amount',
        'status',
        'paid_at',
        'expires_at',
        'payload',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'payload' => 'array',
    ];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }
}
