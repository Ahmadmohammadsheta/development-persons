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
            'today'=> Carbon::now()->format('Y-m-d'),
            'today_records' => [
                'sum_of_today_records'=> $this->todayRecord->sum('points'),
                'missions_of_today_records' => RecordOnlyMissionResource::collection($this->todayRecord),
            ],
            'month_records' => [
                'sum_of_month_records'=> $this->monthRecord->sum('points'),
                'missions_of_month_records'=> RecordOnlyMissionResource::collection($this->monthRecord),
            ],
            'total_records' => [
                'sum_of_total_records'=> $this->totalRecords->sum('points'),
                'missions_of_total_records'=> RecordOnlyMissionResource::collection($this->totalRecords),
            ]
        ];
    }
}
