<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadCommunication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lead_id', 'type', 'direction', 'subject', 'content',  'call_status',
        'scheduled_at', 'completed_at', 'status', 'created_by', 'assigned_to'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}