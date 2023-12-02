<?php

namespace App\Http\Resources\Records;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordOnlyMissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->mission->id,
            'mission' => $this->mission->name,
            'points' => $this->points,
        ];
    }
}
