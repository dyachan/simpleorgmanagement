<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorklogForUserResource extends JsonResource
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
            'proyect_name' => $this->proyect->name,
            'proyect_logo' => $this->proyect->logo,
            'proyect_id' => $this->proyect->id,
            'start' => $this->start,
            'end' => $this->end,
            'description' => $this->description
        ];
    }
}
