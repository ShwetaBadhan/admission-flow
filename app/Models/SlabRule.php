<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlabRule extends Model
{
    protected $fillable = [
        'consultant_id',
        'college_id', 
        'course_name',
        'threshold',
        'bonus_type',
        'bonus_value',
        'currency',
        'retroactive',
        'scope',
        'status',
        'notes',
    ];

    protected $casts = [
        'retroactive' => 'boolean',
        'threshold' => 'integer',
        'bonus_value' => 'decimal:2',
    ];

    // Relationships
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    // Helper: Check if this rule applies to given context
    public function appliesTo(?int $consultantId, ?int $collegeId, ?string $courseName): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Consultant match (null = all consultants)
        if ($this->consultant_id && $this->consultant_id !== $consultantId) {
            return false;
        }

        // College match (null = all colleges)
        if ($this->college_id && $this->college_id !== $collegeId) {
            return false;
        }

        // Course match (null = all courses)
        if ($this->course_name && $this->course_name !== $courseName) {
            return false;
        }

        return true;
    }

    // Helper: Calculate bonus amount
    public function calculateBonus(float $baseCommission, float $tuitionFee): float
{
    return match ($this->bonus_type) {
        'fixed_amount' => (float) $this->bonus_value,
        'percentage_of_commission' => ($baseCommission * $this->bonus_value) / 100,
        'percentage_of_fee' => ($tuitionFee * $this->bonus_value) / 100,
        default => 0,
    };
}
}
