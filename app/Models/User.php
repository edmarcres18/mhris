<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use App\Models\Department;
use App\Models\PushSubscription;
use App\Models\Notification;
use App\Models\ChatMessage;
use App\Models\SystemUpdate;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPushSubscriptions, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'bio',
        'profile_image',
        'date_hired',
        'password',
        'company_id',
        'status',
        'biometric_token',
        'device_id',
        'device_token',
        'signature',
        'department_id',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen' => 'datetime',
    ];

    /**
     * Get the linked accounts for the user.
     */
    public function linkedAccounts()
    {
        return $this->hasMany(LinkedAccount::class);
    }

    public function adminlte_image()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        } else {
            return $this->generateInitialsAvatar();
        }
    }

    private function generateInitialsAvatar()
    {
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

        if (empty($initials)) {
            $initials = 'U';
        }

        $colors = [
            '#00ACC1', // Cyan
            '#FF5722', // Deep Orange
            '#9C27B0', // Purple
            '#4CAF50', // Green
            '#F44336', // Red
            '#3F51B5', // Indigo
        ];

        $backgroundColor = $colors[crc32($this->email) % count($colors)];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <circle cx="20" cy="20" r="20" fill="' . $backgroundColor . '"/>
                    <text x="20" y="20" font-size="16" fill="#FFFFFF" text-anchor="middle" dominant-baseline="central" font-family="Arial, Helvetica, sans-serif" font-weight="bold">' . $initials . '</text>
                </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function adminlte_desc()
    {
        return $this->bio;
    }

    public function adminlte_profile_url()
    {
        return route('profile.show', $this->id);
    }

    public function post(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // User.php (User Model)
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function nightPremiums()
    {
        return $this->hasMany(NightPremium::class);
    }


    public function getIsEmployeeAttribute()
    {
        // Example: assuming you have a column 'employee_type' in users table
        return $this->employee_type === 'employee'; // Adjust this based on your actual implementation
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function systemUpdates()
    {
        return $this->hasMany(SystemUpdate::class);
    }

    /**
     * Get all conversations the user is participating in.
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants')
            ->withPivot('last_read')
            ->withTimestamps()
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Get all messages sent by the user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get all participants belonging to the user.
     */
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

}
