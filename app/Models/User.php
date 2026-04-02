<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'password',
        'org_type',
        'org_name',
        'province',
        'is_lis_cmu',
        'student_id',
        'role',
        'language',
        'bibliography_count',
        'project_count',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
        'reset_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'token_expiry' => 'datetime',
            'reset_token_expires' => 'datetime',
            'last_login' => 'datetime',
            'is_lis_cmu' => 'boolean',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name . ' ' . $this->surname)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function bibliographies()
    {
        return $this->hasMany(Bibliography::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'admin_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function emailVerifications()
    {
        return $this->hasMany(EmailVerification::class);
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class);
    }

    public function supportReports()
    {
        return $this->hasMany(SupportReport::class);
    }

    public function ratings()
    {
        return $this->hasMany(UserRating::class);
    }
}
