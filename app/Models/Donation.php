<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'donor_id',
        'reference',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'base_amount',
        'unique_code',
        'prayer_message',
        'is_anonymous',
        'show_on_public',
        'status',
        'paid_at',
        'expires_at',
        'metadata',
        'payment_bank',
        'payment_account_number',
        'payment_account_name',
        'verified_at',
        'verified_by',
        'admin_note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'unique_code' => 'integer',
        'is_anonymous' => 'boolean',
        'show_on_public' => 'boolean',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
