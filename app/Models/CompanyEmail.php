<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'status',
    ];

    /**
     * The shareable links that this company email belongs to.
     */
    public function shareableLinks()
    {
        return $this->belongsToMany(ShareableEmailLink::class, 'company_email_shareable_link');
    }
    
    /**
     * Get the credentials associated with this company email.
     */
    public function credentials()
    {
        return $this->hasMany(Credentials::class, 'company_email', 'email');
    }
}
