<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Accountability extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'documents',
        'notes',
    ];

    protected $casts = [
        'documents' => 'array',
        'date_issued' => 'date:Y-m-d',
        'returned_at' => 'date:Y-m-d',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function itInventories(): BelongsToMany
    {
        return $this->belongsToMany(ItInventory::class, 'accountability_it_inventory')
            ->withTimestamps()
            ->withPivot('assigned_at', 'returned_at');
    }

    public function setDocumentsAttribute($value)
    {
        $this->attributes['documents'] = json_encode(array_slice((array) $value, 0, 10));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereHas('itInventories', function ($q) {
            $q->whereNull('returned_at');
        });
    }

    public function assignInventory(ItInventory $inventory, ?Carbon $assignedAt = null): void
    {
        $this->itInventories()->attach($inventory->id, [
            'assigned_at' => $assignedAt ?? now(),
        ]);
    }

    public function returnInventory(ItInventory $inventory, ?Carbon $returnedAt = null): void
    {
        $this->itInventories()->updateExistingPivot($inventory->id, [
            'returned_at' => $returnedAt ?? now(),
        ]);
    }

    public function getActiveInventoriesAttribute()
    {
        return $this->itInventories()->whereNull('accountability_it_inventory.returned_at')->get();
    }

    public function getDateIssuedAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getReturnedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
