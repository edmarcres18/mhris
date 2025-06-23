<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Task extends Model
{
    use HasFactory, Loggable;


    protected $fillable = ['title', 'description', 'employee_id', 'status', 'read_at',];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
