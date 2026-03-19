<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsultantKyc extends Model
{
    use HasFactory;

    protected $table = 'consultant_kyc';

    protected $fillable = [
        'consultant_id',
        'document_type',
        'document_number',
        'file_name',
        'file_path',
        'file_size',
        'remarks',
        'uploaded_by',
        'is_verified',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime'
    ];

    // Relationships
    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereNull('is_verified');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeRejected($query)
    {
        return $query->where('is_verified', false);
    }
}
