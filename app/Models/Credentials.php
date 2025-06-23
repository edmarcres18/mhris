<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Credentials extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'company_email',
        'company_number',
        'email_password',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function companyEmail()
    {
        return $this->belongsTo(CompanyEmail::class, 'company_email', 'email');
    }
    
    /**
     * The shareable links that this credential belongs to.
     */
    public function shareableLinks()
    {
        return $this->belongsToMany(ShareableCredentialLink::class, 'credential_shareable_link');
    }
}
