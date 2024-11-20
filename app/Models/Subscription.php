<?php

namespace App\Models;

use App\Models\SubscriptionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'type',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'trial_ends_at',
        'ends_at',
    ];

    protected $dates = [
        'trial_ends_at',
        'ends_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription items.
     */
    public function items()
    {
        return $this->hasMany(SubscriptionItem::class);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('stripe_status', 'active');
    }
}
