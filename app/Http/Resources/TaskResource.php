<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority,
            'status'      => $this->status,
            'due_date'    => $this->due_date?->toDateString(),
            'assigned_to' => $this->user?->only('id', 'name'),
            'ai_summary'  => $this->ai_summary,
            'ai_priority' => $this->ai_priority,
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}