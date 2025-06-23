<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareableCredentialView extends Model
{
    use HasFactory;

    protected $fillable = [
        'shareable_credential_link_id',
        'ip_address',
        'user_agent',
        'email',
        'auth_provider',
        'timestamp',
        'is_authenticated'
    ];

    /**
     * Get the shareable link that was viewed.
     */
    public function shareableLink()
    {
        return $this->belongsTo(ShareableCredentialLink::class, 'shareable_credential_link_id');
    }
} 