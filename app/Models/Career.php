<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Career extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'hiring_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'linkedin',
        'experience',
        'resume_path',
        'cover_letter',
        'agree_terms',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public function hiring()
    {
        return $this->belongsTo(Hiring::class);
    }

}
