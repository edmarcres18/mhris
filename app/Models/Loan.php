<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;  
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Loan extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'date',
        'sss_loan',
        'pagibig_loan',
        'cash_advance',
        'notes',
    ];

    protected $casts = [
        'sss_loan' => 'float',
        'pagibig_loan' => 'float',
        'cash_advance' => 'float',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Date mutator
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('F d, Y');
    }
}
