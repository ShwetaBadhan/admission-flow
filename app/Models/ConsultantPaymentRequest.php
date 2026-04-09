<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultantPaymentRequest extends Model
{
    protected $fillable = [
        'consultant_id',
        'requested_amount',
        'notes',
        'status',
        'admission_ids',
        'approved_at',
        'approved_by',
        'paid_at',
        'payment_reference',
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'admission_ids' => 'array',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
