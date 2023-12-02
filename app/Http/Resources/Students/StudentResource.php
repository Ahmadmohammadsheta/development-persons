<?php

namespace App\Http\Resources\Students;

use App\Http\Resources\Missions\MissionResource;
use App\Http\Resources\Records\RecordOnlyMissionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'birthdate'=> $this->birthdate,
            'records' => [
                'total_of_today'=> $this->todayRecord->sum('points'),
                'missions' => RecordOnlyMissionResource::collection($this->todayRecord),
                'total_records'=> $this->records->sum('points'),
            ]
        ];
    }
}
