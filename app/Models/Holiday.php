<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Holiday extends Model
{
    use HasFactory, Loggable;

    const TYPE_REGULAR = 'Regular Holiday';
    const TYPE_SPECIAL = 'Special Non-Working Holiday';
    const TYPE_SPECIAL_WORKING = 'Special Working Holiday';

    protected $fillable = [
        'title',
        'date',
        'type',
        'holiday_hours',
    ];

    protected $casts = [
        'date' => 'date',
        'holiday_hours' => 'decimal:2',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_REGULAR,
            self::TYPE_SPECIAL,
            self::TYPE_SPECIAL_WORKING,
        ];
    }
    
    /**
     * Get default holiday hours based on type
     * 
     * @return float
     */
    public function getDefaultHours(): float
    {
        return $this->type === self::TYPE_SPECIAL_WORKING ? 0.0 : 8.0;
    }
    
    /**
     * Set the holiday hours based on type when creating
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($holiday) {
            if (!isset($holiday->holiday_hours)) {
                $holiday->holiday_hours = $holiday->type === self::TYPE_SPECIAL_WORKING ? 0.0 : 8.0;
            }
        });
    }
}

