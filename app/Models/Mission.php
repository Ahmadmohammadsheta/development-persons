<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit\Framework\MockObject\Builder\Stub;

class Mission extends Model
{
    use HasFactory;
    protected $fillable = ['name'];


    /**
     * The students that belong to the role.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
        // return $this->belongsToMany(Student::class)->withPivot('name', 'created_by');
        // return $this->belongsToMany(Student::class)->withTimestamps();
    }

    public function allStudents() {

        $student = Student::find(1);

        foreach ($student->missions as $mission) {
            echo $mission->pivot->created_at;
        }
    }

    public function laravelDoc() {
        return $this->belongsToMany(Mission::class)
                ->wherePivot('approved', 1);

        return $this->belongsToMany(Mission::class)
                        ->wherePivotIn('priority', [1, 2]);

        return $this->belongsToMany(Mission::class)
                        ->wherePivotNotIn('priority', [1, 2]);

        return $this->belongsToMany(Mission::class)
                        ->as('subscriptions')
                        ->wherePivotBetween('created_at', ['2020-01-01 00:00:00', '2020-12-31 00:00:00']);

        return $this->belongsToMany(Mission::class)
                        ->as('subscriptions')
                        ->wherePivotNotBetween('created_at', ['2020-01-01 00:00:00', '2020-12-31 00:00:00']);

        return $this->belongsToMany(Mission::class)
                        ->as('subscriptions')
                        ->wherePivotNull('expired_at');

        return $this->belongsToMany(Mission::class)
                        ->as('subscriptions')
                        ->wherePivotNotNull('expired_at');

        return $this->belongsToMany(Badge::class)
                ->where('rank', 'gold')
                ->orderByPivot('created_at', 'desc');
    }
}
