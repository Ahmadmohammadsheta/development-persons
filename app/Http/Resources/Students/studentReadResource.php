<?php

namespace App\Http\Resources\students;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Records\RecordOnlyMissionResource;

class studentReadResource extends JsonResource
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
                'month_record'=> $this->monthRecord->sum('points'),
                'total_records'=> $this->totalRecords->sum('points'),
                'missions' => RecordOnlyMissionResource::collection($this->todayRecord),
            ]
        ];
    }
}
