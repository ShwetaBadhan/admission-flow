<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lead_id', 'college_id', 'course_id', 'intake_session',
        'status', 'application_notes', 'application_reference',
        'submitted_date', 'expected_decision_date', 'submitted_by'
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'expected_decision_date' => 'date'
    ];

    public function lead()
{
    return $this->belongsTo(Lead::class);
}

public function college()
{
    return $this->belongsTo(College::class);
}

public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}

public function submittedBy()
{
    return $this->belongsTo(User::class, 'submitted_by');
}
public function commissionPayments()
{
    return $this->hasMany(CommissionPayment::class);
}
}