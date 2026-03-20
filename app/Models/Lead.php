<?php

// app/Models/Lead.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'mobile',
        'email',
        'city_id',
        'state_id',
        'qualification_id',
        'interested_course_id',
        'preferred_intake_id',
        'lead_source_id',
        'priority_id',
        'status',
        'notes',
        'consultant_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'interested_course_id');
    }

    public function intake()
    {
        return $this->belongsTo(Intake::class, 'preferred_intake_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }

    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'consultant_id');
    }
    // New relationships
    public function stages()
    {
        return $this->hasMany(LeadStage::class)->orderBy('created_at', 'desc');
    }

    public function currentStage()
    {
        return $this->hasOne(LeadStage::class)->latestOfMany();
    }

    public function documents()
    {
        return $this->hasMany(LeadDocument::class)->orderBy('created_at', 'desc');
    }

    public function communications()
    {
        return $this->hasMany(LeadCommunication::class)->orderBy('created_at', 'desc');
    }

    public function admissionRequests()
    {
        return $this->hasMany(AdmissionRequest::class)->orderBy('created_at', 'desc');
    }

    public function counsellor()
    {
        return $this->belongsTo(User::class, 'counsellor_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function hasDocumentType($typeId, $excludeRejected = true)
    {
        $query = $this->documents()->where('document_type', $typeId);
        if ($excludeRejected) {
            $query->where('is_verified', '!=', false);
        }
        return $query->exists();
    }
}
