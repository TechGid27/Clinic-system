<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestItem extends Model
{
    protected $fillable = [
        'medication_request_id',
        'medication_id',
        'quantity',
        'quantity_disbursed',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_disbursed' => 'integer',
    ];

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class);
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
