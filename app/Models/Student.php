<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'birthdate'];


    /**
     * The missions that belong to the user.
     */
    public function missions(): BelongsToMany
    {
        return $this->belongsToMany(Mission::class);
        // return $this->belongsToMany(Student::class, 'mission_student', 'student_id', 'mission_id');
    }

    /**
     * The missions that belong to the user.
     */
    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
        // return $this->belongsToMany(Student::class, 'mission_student', 'student_id', 'mission_id');
    }

    /**
     * Get the user's first name.
     */
    protected function todayRecord(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Record::whereDate('created_at', Carbon::today())->where('student_id', $this->id)->get(),
        );
    }

}
