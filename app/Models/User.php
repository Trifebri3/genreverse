<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function ambassador(): HasOne
    {
        return $this->hasOne(Ambassador::class);
    }

    public function regionScopes(): HasMany
    {
        return $this->hasMany(UserRegionScope::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(UserLessonProgress::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function questAttempts(): HasMany
    {
        return $this->hasMany(QuestAttempt::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }

    public function xpTransactions(): HasMany
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'creator_id');
    }

    public function campaignReports(): HasMany
    {
        return $this->hasMany(CampaignReport::class, 'submitted_by');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'author_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'author_id');
    }

    public function hasRole(string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : [$roles];

        return $this->roles->pluck('slug')->intersect($roleList)->isNotEmpty();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permissionSlug)) {
                return true;
            }
        }

        return false;
    }

    public function isAmbassador(): bool
    {
        return $this->hasRole(['ambassador', 'duta']) ||
            ($this->ambassador !== null && $this->ambassador->verification_status === 'verified');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(['super_admin', 'admin_nasional', 'admin_wilayah']);
    }

    public function isModerator(): bool
    {
        return $this->hasRole(['moderator', 'super_admin', 'admin_nasional']);
    }

    public function totalXp(): int
    {
        return (int) $this->xpTransactions()->sum('points');
    }

    public function level(): int
    {
        $xp = $this->totalXp();
        // Level calculation: 0-100 (Level 1), 101-250 (Level 2), 251-500 (Level 3), etc.
        if ($xp < 100) {
            return 1;
        }
        if ($xp < 300) {
            return 2;
        }
        if ($xp < 600) {
            return 3;
        }
        if ($xp < 1000) {
            return 4;
        }

        return 5 + (int) floor(($xp - 1000) / 500);
    }
}
