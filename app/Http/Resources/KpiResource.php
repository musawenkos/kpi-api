<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class KpiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'description'   => $this->description,
            'status'        => $this->status,
            'assigned_date' => Carbon::parse($this->assigned_date)->toDateString(),
            'notes'         => $this->notes,
            'assignee'      => new UserResource($this->whenLoaded('assignee')),
            'assigner'      => new UserResource($this->whenLoaded('assigner')),
        ];
    }
}
