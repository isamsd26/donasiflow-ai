<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'campaign_category_id',
        'title',
        'slug',
        'organizer_name',
        'featured_badge',
        'featured_image',
        'cta_label',
        'location',
        'focus_area',
        'distribution_plan',
        'summary',
        'content',
        'target_amount',
        'current_amount',
        'donor_count',
        'start_date',
        'end_date',
        'publish_status',
        'public_status',
        'is_featured',
        'display_order',
        'meta',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'meta' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CampaignCategory::class, 'campaign_category_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function heroBanners(): HasMany
    {
        return $this->hasMany(HeroBanner::class);
    }
}
