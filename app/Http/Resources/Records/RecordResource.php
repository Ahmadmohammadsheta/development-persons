<?php

namespace App\Http\Resources\Records;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student' => $this->student->name,
            'mission' => $this->mission->name,
            'points' => $this->points,
        ];
    }
}
