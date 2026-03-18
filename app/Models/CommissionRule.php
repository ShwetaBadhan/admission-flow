<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionRule extends Model
{
    protected $fillable = [
        'consultant_id',
        'college_id',
        'course_name',
        'commission_type',
        'commission_value',
        'currency',
        'status',
        'notes',
    ];

    // Optional: explicitly set table name if needed
    // protected $table = 'commission_rules';

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }
}