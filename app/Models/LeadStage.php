<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStage extends Model
{
    protected $fillable = [
        'lead_id', 'stage', 'notes', 'updated_by', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
