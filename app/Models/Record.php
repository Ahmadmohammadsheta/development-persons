<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Record extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'mission_id', 'points'];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function mission() {
        return $this->belongsTo(Mission::class);
    }

    /**
     * Get the user's first name.
     */
    protected function todayRecord(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    /**
     * Get the user's first name.
     */
    protected function points(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $value : '1',
        );
    }
}
