<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lead_id', 'document_type', 'file_name', 'file_path',
        'file_size', 'uploaded_by', 'is_verified', 'remarks'
    ];
 public function documentSetting()
    {
        return $this->belongsTo(DocumentSetting::class, 'document_type', 'id');
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function getFilePathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}