<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'quantity',
        'low_stock_threshold',
        'unit',
        'production_date',
        'expiry_date',
    ];

    protected $casts = [
        'quantity'           => 'integer',
        'low_stock_threshold'=> 'integer',
        'production_date'    => 'date',
        'expiry_date'        => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function requestItems(): HasMany
    {
        return $this->hasMany(MedicationRequestItem::class);
    }

    public function disbursements(): HasMany
    {
        return $this->hasMany(Disbursement::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return !$this->isExpired()
            && $this->expiry_date->diffInDays(now()) <= $days;
    }
}
