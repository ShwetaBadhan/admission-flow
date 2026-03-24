<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionPayment extends Model
{
    protected $fillable = [
        'consultant_id',
        'admission_request_id',
        'commission_rule_id',
        'lead_id',
        'payment_type',
        'commission_value',
        'calculated_amount',
        'currency',
        'status',
        'payment_date',
        'payment_reference',
        'notes',
        'created_by',
        'paid_by',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function admissionRequest(): BelongsTo
    {
        return $this->belongsTo(AdmissionRequest::class);
    }

    public function commissionRule(): BelongsTo
    {
        return $this->belongsTo(CommissionRule::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}