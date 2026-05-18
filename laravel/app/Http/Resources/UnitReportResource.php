<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'type'        => $this->type,
            'description' => $this->description,
            'status'      => $this->status,
            'unit_id'     => $this->unit_id,
            'booking_id'  => $this->booking_id,
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
