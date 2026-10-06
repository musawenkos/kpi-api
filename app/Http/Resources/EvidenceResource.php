<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvidenceResource extends JsonResource
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
            'kpi_id'        => $this->kpi_id,
            'evidence_name' => $this->evidence_name,
            'captured_by'   => $this->captured_by,
            'captured_date' => $this->captured_date->toIso8601String(),
            'mime_type'     => $this->mime_type,
            'size_bytes'    => $this->size_bytes,
            'sha256'        => $this->sha256,
            'download_url'  => url("/api/evidence/{$this->id}/download"),
        ];
    }
}
