<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'state',
        'address',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
    // ✅ State Relationship - explicitly specify foreign key column
    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'id');
        // Parameters: (Model, foreign_key_on_consultants, primary_key_on_states)
    }

    // ✅ City Relationship - explicitly specify foreign key column
    public function city()
    {
        return $this->belongsTo(City::class, 'city', 'id');
        // Parameters: (Model, foreign_key_on_consultants, primary_key_on_cities)
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    // Add this relationship
    public function kycDocuments()
    {
        return $this->hasMany(ConsultantKyc::class, 'consultant_id');
    }

    // Helper: Check if a specific KYC type exists
    public function hasKycDocument($type, $excludeRejected = true)
    {
        $query = $this->kycDocuments()->where('document_type', $type);
        if ($excludeRejected) {
            $query->where('is_verified', '!=', false);
        }
        return $query->exists();
    }
    public function getUser()
    {
        return User::where('email', $this->email)->first();
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'consultant_id');
    }
}
