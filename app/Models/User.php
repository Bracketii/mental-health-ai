<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Coach;
use App\Traits\HasRoles;
use App\Models\UserAnswer;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ConversationHistory;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;
    use HasRoles;

    // Define role constants
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: User has many subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Relationship: User has many answers.
     */
    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Check if the user has admin role.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if the user is subscribed to a specific plan.
     *
     * @param string $planName
     * @return bool
     */
    public function isSubscribedToPlan(string $planName): bool
    {
        return $this->subscriptions()->active()->where('name', $planName)->exists();
    }

    /**
     * Check if the user has any active subscription.
     *
     * @return bool
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()->active()->exists();
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }
    
    public function conversations()
    {
        return $this->hasMany(ConversationHistory::class, 'user_id');
    }

}
