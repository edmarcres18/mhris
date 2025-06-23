<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    use HasFactory;

    protected $fillable = ['google_user_id', 'hiring_id'];

    public function googleUser()
    {
        return $this->belongsTo(GoogleUser::class);
    }

    public function hiring()
    {
        return $this->belongsTo(Hiring::class);
    }
}
