<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ShareableCredentialLink extends Model
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
     * The credentials that belong to this shareable link.
     */
    public function credentials()
    {
        return $this->belongsToMany(Credentials::class, 'credential_shareable_link');
    }

    /**
     * Get the views associated with this shareable link.
     */
    public function views()
    {
        return $this->hasMany(ShareableCredentialView::class, 'shareable_credential_link_id');
    }

    /**
     * Check if the link is still active.
     */
    public function isActive()
    {
        return $this->expires_at > Carbon::now();
    }

    /**
     * Get the creator of this link.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get only active links.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', Carbon::now());
    }

    /**
     * Calculate remaining time in minutes.
     */
    public function remainingTimeInMinutes()
    {
        if (!$this->isActive()) {
            return 0;
        }

        $now = Carbon::now();
        $expiresAt = $this->expires_at;

        return max(0, $now->diffInMinutes($expiresAt));
    }
} 