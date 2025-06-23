<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class QuotationRequest extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'product_id',
        'product_name',
        'name',
        'email',
        'phone',
        'hospital_name',
        'message',
        'status',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(MedicalProduct::class);
    }
} 
