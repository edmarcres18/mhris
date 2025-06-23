<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ShareableEmailLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'created_by',
        'description',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * The company emails that belong to this shareable link.
     */
    public function companyEmails()
    {
        return $this->belongsToMany(CompanyEmail::class, 'company_email_shareable_link');
    }

    /**
     * The user who created this shareable link.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active links.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('expires_at', '>', Carbon::now());
    }

    /**
     * Determine if the link is still valid.
     */
    public function isValid(): bool
    {
        return $this->expires_at > Carbon::now();
    }

    /**
     * Get the remaining time in minutes.
     */
    public function remainingTimeInMinutes(): int
    {
        if (!$this->isValid()) {
            return 0;
        }

        return Carbon::now()->diffInMinutes($this->expires_at);
    }
}
