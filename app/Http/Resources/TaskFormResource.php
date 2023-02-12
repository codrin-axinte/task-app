<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'due_date' => $this->due_date?->toDateTimeLocalString(),
            'parent_id' => TaskSelectOptionResource::make($this->parent),
            'priority' => $this->priority,
        ];
    }
}
